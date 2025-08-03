import axios from 'axios';

const BASE_URL = '/api/jobs';

/**
 * Fetch all jobs
 * @returns {Promise}
 */
export function fetchJobs() {
  return axios.get(BASE_URL);
}

/**
 * Fetch a single job by ID
 * @param {number|string} id
 * @returns {Promise}
 */
export function fetchJob(id) {
  return axios.get(`${BASE_URL}/${id}`);
}

/**
 * Create a new job
 * @param {Object} jobData
 * @returns {Promise}
 */
export function createJob(jobData) {
  return axios.post(BASE_URL, jobData);
}

/**
 * Update an existing job
 * @param {number|string} id
 * @param {Object} jobData
 * @returns {Promise}
 */
export function updateJob(id, jobData) {
  return axios.put(`${BASE_URL}/${id}`, jobData);
}

/**
 * Delete a job by ID
 * @param {number|string} id
 * @returns {Promise}
 */
export function deleteJob(id) {
  return axios.delete(`${BASE_URL}/${id}`);
}

export default {
  fetchJobs,
  fetchJob,
  createJob,
  updateJob,
  deleteJob
};