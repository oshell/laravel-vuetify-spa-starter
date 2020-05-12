import axios from 'axios';
import router from './router';
import api from './api';

const auth = {
  init: function() {
    const token = this.loggedIn();
    if (token) {
      axios.defaults.headers.common = {'Authorization': `Bearer ${token}`};
    }
  },
  loggedIn: function() {
    if (!localStorage) return null;
    return localStorage.getItem('token');
  },
  user: function() {
    return JSON.parse(localStorage.getItem('user') || '{}');
  },
  setToken: function(token) {
    localStorage.setItem('token', token);
  },
  login: function(email, password) {
    return axios
      .post(`${window.location.origin}/api/login`, { email, password })
      .then(response => {
        const token = response.data['access_token'];
        localStorage.setItem('token', token);
        axios.defaults.headers.common = {'Authorization': `Bearer ${token}`};
        api.user
          .get()
          .then(response => {
            localStorage.setItem('user', JSON.stringify(response.data));
          })
      })
      .catch(err => {
        console.log(err.response);
        return Promise.reject(err.response);
      })
  },
  register: async function(name, email, password) {
    const response = await axios.post(`${window.location.origin}/api/register`, {
      name, email, password
    });
    if (response.error) return false;
    const token = response.data['access_token'];
    localStorage.setItem('token', token);
    axios.defaults.headers.common = {'Authorization': `Bearer ${token}`};
    api.user
      .get()
      .then(response => {
        localStorage.setItem('user', JSON.stringify(response.data));
      });
  },
  resetHeaders: function() {
    localStorage.removeItem('token');
    axios.defaults.headers.common = {'Authorization': '' };
  },
  logout: function() {
    return axios.get(`${window.location.origin}/api/logout`)
      .then(res => {
        return auth.resetHeaders();
      }).catch(err => {
        return auth.resetHeaders();
      });
  },
};

export default auth;