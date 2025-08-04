import axios from 'axios';

const BASE_URL = '/api/companies';

/**
 * Fetch all companies
 * @returns {Promise}
 */
export function fetchCompanies() {
  return axios.get(BASE_URL);
}

/**
 * Fetch a single company by ID
 * @param {number|string} id
 * @returns {Promise}
 */
export function fetchCompanyDetails(id) {
  return axios.get(`${BASE_URL}/${id}`);
}

/**
 * Create a new company
 * @param {Object} companyData
 * @returns {Promise}
 */
export function createCompany(companyData) {
  return axios.post(BASE_URL, companyData);
}

/**
 * Update an existing company
 * @param {number|string} id
 * @param {Object} companyData
 * @returns {Promise}
 */
export function updateCompany(id, companyData) {
  return axios.put(`${BASE_URL}/${id}`, companyData);
}

/**
 * Delete a company by ID
 * @param {number|string} id
 * @returns {Promise}
 */
export function deleteCompany(id) {
  return axios.delete(`${BASE_URL}/${id}`);
}
