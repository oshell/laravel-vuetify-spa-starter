<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\EmailVerification;
use App\PasswordReset;
use App\Mail\CtaMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    public $loginAfterSignUp = false;

    public function register(Request $request)
    {

      $birth = $request->birth ? date('Y-m-d', strtotime($request->birth)) : null;

      try {
        $user = User::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => bcrypt($request->password)
        ]);
      } catch(QueryException $e) {
        return response()->json(['error' => 'QueryException', 'message' => $e->getMessage()], 409);
      }


      $hash = AuthController::createRandomHash();
      EmailVerification::create([
        'uid' => $user->id,
        'hash' => $hash
      ]);

      $this->sendEmailVerificationMail($user, $hash);
      
      $token = auth()->login($user);
      return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
      $credentials = $request->only(['email', 'password']);

      if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }

      return $this->respondWithToken($token);
    }

    public function getAuthUser(Request $request)
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL()
      ]);
    }

    public static function createRandomHash($len=32)
    {
      return substr(md5(openssl_random_pseudo_bytes(20)),-$len);
    }

    public static function unauthorized()
    {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    public static function forbidden()
    {
      return response()->json(['error' => 'Forbidden'], 403);
    }

    public function verifyEmail($uid, $hash)
    {
      $verification = EmailVerification::where('uid', $uid)->first();
      if (!$verification) {
        return response()->json(['error' => 'No outstanding verification for that user'], 400);
      }
      $valid = $verification->hash === $hash;

      if ($valid) {
        $user = User::find($uid);
        $dateTime = new \DateTime();
        $user->email_verified_at = $dateTime->getTimestamp();
        $user->save();
        EmailVerification::where('uid', $uid)->delete();
      }

      return response()->json(['valid' => $valid], 200);
    }

    public function initPasswordReset(Request $request)
    {
      $user = User::where('email', $request->email)->first();
      if (!$user) {
        return response()->json(['error' => 'No user found with that email'], 422);
      }

      $hash = AuthController::createRandomHash();
      PasswordReset::create([
        'uid' => $user->id,
        'hash' => $hash
      ]);

      $this->sendPasswordResetMail($user, $hash);
      return response()->json(['success' => true], 200);
    }

    public function resetPassword(Request $request, $uid, $hash)
    {
      $reset = PasswordReset::where('uid', $uid)->first();
      if (!$reset) {
        return response()->json(['error' => 'No outstanding reset for that user'], 400);
      }
      $valid = $reset->hash === $hash;

      if ($valid) {
        $user = User::find($uid);
        $user->password = bcrypt($request->password);
        $user->save();
        PasswordReset::where('uid', $uid)->delete();
      }

      return response()->json(['valid' => $valid], 200);
    }

    protected function sendPasswordResetMail($user, $hash)
    {
      Mail::to($user)->send(new CtaMail([
        'subject' => 'Forgot Password?',
        'title' => 'Forgot Password?',
        'preview' => 'Reset Password.',
        'paragraph' => 'Click on the Button to reset your password.',
        'ctaLink' => url("/password/reset/{$user->id}/{$hash}"),
        'ctaText' => 'Reset Password',
        'info' => 'If you did not request this password simply ignore this email.'
      ]));
    }

    protected function sendEmailVerificationMail($user, $hash)
    {
      Mail::to($user)->send(new CtaMail([
        'subject' => 'Email Verification',
        'title' => 'Email Verification',
        'preview' => 'Email Verification',
        'paragraph' => 'Click on the Button to verify your Email.',
        'ctaLink' => url("/verify/email/{$user->id}/{$hash}"),
        'ctaText' => 'Verify Email',
        'info' => 'If you did not create an account, you can ignore this email.'
      ]));
    }
}
