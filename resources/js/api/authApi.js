import axios from 'axios';

/**
 * Login user
 * @param {Object} credentials - { email, password }
 * @returns {Promise}
 */
export function login(credentials) {
  return axios.post('/api/login', credentials);
}

/**
 * Logout user
 * @returns {Promise}
 */
export function logout() {
  return axios.post('/api/logout');
}

/**
 * Get authenticated user
 * @returns {Promise}
 */
export function getUser() {
  return axios.get('/api/user');
}
