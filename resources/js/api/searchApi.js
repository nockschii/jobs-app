import axios from 'axios';

const BASE_URL = '/api/search';

/**
 * Search for jobs based on a search term
 * Searches in title, city, and country with OR logic
 * @param {string} searchterm - The search term to look for
 * @returns {Promise} - Promise that resolves to the search results
 */
export function searchJobs(searchterm) {
  return axios.get(BASE_URL, {
    params: { searchterm }
  });
}

export function storeSearchTerm(searchterm, userInfo = {}) {
  return axios.post(`${BASE_URL}/store`, { searchterm, userInfo });
}

export default {
  searchJobs,
  storeSearchTerm
};