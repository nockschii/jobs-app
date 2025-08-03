<template>
  <div class="job-posting">
    <div v-if="selectedJob" class="job-detail">
      <div class="job-detail-header">
        <h1>{{ selectedJob.title }}</h1>
        <span class="employment-badge">{{ selectedJob.employment_type }}</span>
      </div>
      
      <div class="company-info">
        <h3>{{ selectedJob.company?.name || 'Company not available' }}</h3>
        <p class="location">📍 {{ formatLocation(selectedJob) }}</p>
      </div>

      <div class="job-description">
        <h4>Job Description</h4>
        <p>{{ selectedJob.description || 'No description available.' }}</p>
      </div>

      <div class="job-requirements">
        <h4>Requirements</h4>
        <ul>
          <li>Experience with relevant technologies</li>
          <li>Strong communication skills</li>
          <li>Team player mentality</li>
        </ul>
      </div>

      <div class="apply-section">
        <button class="apply-btn">Apply Now</button>
      </div>
    </div>
    
    <div v-else class="no-selection">
      <p>Select a job from the list to view details</p>
    </div>

    <ErrorModal 
      :show="showError" 
      :message="errorMessage" 
      @close="closeError" 
    />
  </div>
</template>

<script>
import ErrorModal from '@components/ErrorModal.vue';

export default {
  name: 'JobPosting',
  components: {
    ErrorModal
  },
  props: {
    selectedJob: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      showError: false,
      errorMessage: ''
    }
  },
  methods: {
    formatLocation(job) {
      if (job.city && job.country) {
        return `${job.city}, ${job.country}`;
      }
      return job.location || 'Location not specified';
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
.job-posting {
  flex: 1;
  padding: 2rem;
  overflow-y: auto;
}

.loading {
  text-align: center;
  padding: 2rem;
  color: #6c757d;
  font-style: italic;
}

.job-detail-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid #eee;
  padding-bottom: 1rem;
}

.job-detail-header h1 {
  margin: 0;
  color: #303030;
}

.job-posting .employment-badge {
  background: #007bff;
  color: white;
  padding: 0.3rem 0.8rem;
  border-radius: 15px;
  font-size: 0.9rem;
}

.company-info {
  margin-bottom: 2rem;
}

.company-info h3 {
  margin: 0 0 0.5rem 0;
  color: #495057;
}

.job-posting .location {
  color:#495057;
  margin: 0;
}

.job-description, .job-requirements {
  margin-bottom: 2rem;
}

.job-description h4, .job-requirements h4 {
  color: #303030;
  margin-bottom: 1rem;
}

.job-requirements ul {
  list-style-type: disc;
  padding-left: 1.5rem;
}

.apply-btn {
  background: #28a745;
  color: white;
  border: none;
  padding: 0.8rem 2rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
}

.apply-btn:hover {
  background: #218838;
}

.no-selection {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #495057;
  font-style: italic;
}
</style>