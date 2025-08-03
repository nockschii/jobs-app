<template>
  <div class="job-list">
    <div class="job-list-header">
      <h2>{{ searchActive ? 'Search Results' : 'Available Jobs' }}</h2>
      <span v-if="searchActive" class="results-count">
        {{ jobs.length }} {{ jobs.length === 1 ? 'result' : 'results' }} found
      </span>
    </div>
    
    <div v-if="loading" class="loading">
      {{ searchActive ? 'Searching...' : 'Loading jobs...' }}
    </div>
    
    <div v-else-if="jobs.length === 0 && searchActive" class="no-results">
      <p>No jobs found for your search.</p>
      <small>Try different keywords or check spelling.</small>
    </div>
    
    <div v-else-if="jobs.length === 0" class="no-jobs">
      <p>No jobs available at the moment.</p>
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

export default {
  name: 'JobList',
  components: {
    JobCard,
    ErrorModal
  },
  props: {
    jobs: {
      type: Array,
      default: () => []
    },
    loading: {
      type: Boolean,
      default: false
    },
    searchActive: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      showError: false,
      errorMessage: ''
    }
  },
  methods: {
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

.job-list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.results-count {
  color: #6c757d;
  font-size: 0.875rem;
  font-weight: normal;
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

.no-results, .no-jobs {
  text-align: center;
  padding: 2rem;
  color: #6c757d;
}

.no-results p, .no-jobs p {
  margin-bottom: 0.5rem;
  font-size: 1.125rem;
}

.no-results small {
  color: #9ca3af;
}

h2 {
  margin: 0;
  color: #303030;
  font-size: 1.25rem;
}
</style>