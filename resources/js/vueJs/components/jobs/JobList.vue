<template>
  <div class="job-list">
    <h2>Available Jobs</h2>
    <div v-if="loading" class="loading">
      Loading jobs...
    </div>
    <div v-else class="jobs-container">
      <JobCard 
        v-for="job in jobs" 
        :key="job.id" 
        :job="job" 
        @click="selectJob(job)"
      />
    </div>
    <ErrorModal 
      :show="showError" 
      :message="errorMessage" 
      @close="closeError" 
    />
  </div>
</template>

<script>
import JobCard from './JobCard.vue';
import ErrorModal from '@components/ErrorModal.vue';
import { fetchJobs } from '@api/jobsApi.js';

export default {
  name: 'JobList',
  components: {
    JobCard,
    ErrorModal
  },
  data() {
    return {
      jobs: [],
      loading: false,
      showError: false,
      errorMessage: ''
    }
  },
  async mounted() {
    await this.loadJobs();
  },
  methods: {
    async loadJobs() {
      this.loading = true;
      try {
        const response = await fetchJobs();
        this.jobs = response.data;
      } catch (error) {
        this.showErrorModal('Failed to load jobs: ' + (error.response?.data?.message || error.message));
      } finally {
        this.loading = false;
      }
    },
    selectJob(job) {
      this.$emit('job-selected', job);
    },
    showErrorModal(message) {
      this.errorMessage = message;
      this.showError = true;
    },
    closeError() {
      this.showError = false;
      this.errorMessage = '';
    }
  }
}
</script>

<style scoped>
.job-list {
  flex: 1;
  padding: 1rem;
  border-right: 1px solid #ddd;
  overflow-y: auto;
}

.jobs-container {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.loading {
  text-align: center;
  padding: 2rem;
  color: #6c757d;
  font-style: italic;
}

h2 {
  margin-bottom: 1rem;
  color: #303030;
}
</style>