import axios from 'axios';
import router from './router';
import auth from './auth';

const api = {
  apiUrl: `${window.location.origin}/api`,
  post(path, body) {
    return axios.post(`${this.apiUrl}${path}`, body).catch(api.handleError);
  },
  get(path) {
    return axios.get(`${this.apiUrl}${path}`).catch(api.handleError);
  },
  handleError(err) {
    const code = err.response.status;
    if (code === 403) {
      console.log('forbidden');
    }
    if (code === 401) {
      console.log('unauthorized');
      auth.resetHeaders();
      // token expired or cache cleared so user has to be sent back to login
      router.push({ name: 'home' });
    }

    return Promise.reject(err);
  },
  user: {
    get() {
      return api.get(`/user`);
    }
  },
  email: {
    verify(uid, hash) {
      return api.get(`/verify/${uid}/${hash}`);
    }
  },
  password: {
    initReset(email) {
      return api.post('/reset/password/init', { email });
    },
    reset(uid, hash, password) {
      return api.post(`/reset/password/${uid}/${hash}`, { password });
    }
  },
};

export default api;