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
        'subject' => 'Passwort vergessen?',
        'title' => 'Passwort vergessen?',
        'preview' => 'Neues Passwort vergeben',
        'paragraph' => 'Bitte klicke auf folgende Schaltfläche, um dein Passwort neu zu vergeben.',
        'ctaLink' => url("/password/reset/{$user->id}/{$hash}"),
        'ctaText' => 'Neues Passwort vergeben',
        'info' => 'Solltest du diese Email nicht beantragt haben, kannst du sie einfach ignorieren.'
      ]));
    }

    protected function sendEmailVerificationMail($user, $hash)
    {
      Mail::to($user)->send(new CtaMail([
        'subject' => 'Email Verifizierung',
        'title' => 'Email Verifizierung',
        'preview' => 'Email Verifizierung',
        'paragraph' => 'Bitte bestätige deine Email mit einem Klick auf die folgende Schaltfläche.',
        'ctaLink' => url("/verify/email/{$user->id}/{$hash}"),
        'ctaText' => 'Email Verifizieren',
        'info' => 'Solltest du keinen Account erstellt haben, kannst du diese Email ignorieren.'
      ]));
    }
}
