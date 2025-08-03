<template>
  <div class="home-page">
    <div class="search-section">
      <SearchBar @search="handleSearch" :loading="searchLoading" />
    </div>
    
    <div class="content">
      <JobList 
        :jobs="displayJobs" 
        :loading="searchLoading" 
        :search-active="isSearchActive"
        @job-selected="handleJobSelection" 
      />
      <JobPosting 
        :selected-job="selectedJobDetails" 
        :loading="loadingJobDetails"
        @error="handleJobError"
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
import SearchBar from '../search/SearchBar.vue';
import JobList from '../jobs/JobList.vue';
import JobPosting from '../jobs/JobPosting.vue';
import ErrorModal from '../ErrorModal.vue';
import { searchJobs } from '@api/searchApi.js';
import { fetchJobs, fetchJobDetails } from '@api/jobsApi.js';

export default {
  name: 'HomePage',
  components: {
    SearchBar,
    JobList,
    JobPosting,
    ErrorModal
  },
  data() {
    return {
      selectedJob: null,
      selectedJobDetails: null,
      allJobs: [],
      searchResults: [],
      searchLoading: false,
      isSearchActive: false,
      loadingAllJobs: false,
      loadingJobDetails: false,
      showError: false,
      errorMessage: ''
    }
  },
  computed: {
    displayJobs() {
      return this.isSearchActive ? this.searchResults : this.allJobs;
    }
  },
  async mounted() {
    await this.loadAllJobs();
  },
  methods: {
    async loadAllJobs() {
      this.loadingAllJobs = true;
      try {
        const response = await fetchJobs();
        this.allJobs = response.data;
      } catch (error) {
        this.handleApiError(error, 'Loading jobs');
      } finally {
        this.loadingAllJobs = false;
      }
    },
    async handleJobSelection(job) {
      this.selectedJob = job;
      this.selectedJobDetails = null; // Reset previous details
      this.loadingJobDetails = true;
      
      try {
        const response = await fetchJobDetails(job.id);
        this.selectedJobDetails = response.data;
      } catch (error) {
        this.handleApiError(error, 'Job details loading');
        this.selectedJob = null;
        this.selectedJobDetails = null;
      } finally {
        this.loadingJobDetails = false;
      }
    },
    async handleSearch(searchTerm) {
      if (!searchTerm || searchTerm.trim() === '') {
        // Keine Suche - zeige alle Jobs
        this.isSearchActive = false;
        this.searchResults = [];
        return;
      }

      this.searchLoading = true;
      this.isSearchActive = true;
      
      try {
        const response = await searchJobs(searchTerm);
        this.searchResults = response.data;
      } catch (error) {
        this.handleApiError(error, 'Search');
        this.searchResults = [];
      } finally {
        this.searchLoading = false;
      }
    },
    handleApiError(error, context = 'Operation') {
      console.error(`${context} failed:`, error);
      this.showError = true;
      
      let errorMessage;
      if (context === 'Search') {
        errorMessage = `Search failed: ${error.response?.data?.message || error.message || 'Unable to perform search. Please check your connection and try again.'}`;
      } else if (context === 'Job details loading') {
        errorMessage = `Failed to load job details: ${error.response?.data?.message || error.message || 'Unknown error occurred'}`;
      } else {
        errorMessage = `${context} failed: ${error.response?.data?.message || error.message || 'An error occurred'}`;
      }
      
      this.errorMessage = errorMessage;
    },
    handleJobError(errorMessage) {
      this.showError = true;
      this.errorMessage = errorMessage;
    },
    closeError() {
      this.showError = false;
      this.errorMessage = '';
    }
  }
}
</script>

<style scoped>
.home-page {
  display: flex;
  flex-direction: column;
  height: calc(100vh - 60px);
}

.search-section {
  padding: 1rem;
  border-bottom: 1px solid #ddd;
  background: #f8f9fa;
}

.content {
  display: flex;
  flex: 1;
  overflow: hidden;
}
</style>