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
      <JobPosting :selected-job="selectedJob" />
    </div>
  </div>
</template>

<script>
import SearchBar from '../search/SearchBar.vue';
import JobList from '../jobs/JobList.vue';
import JobPosting from '../jobs/JobPosting.vue';
import { searchJobs } from '@api/searchApi.js';
import { fetchJobs } from '@api/jobsApi.js';

export default {
  name: 'HomePage',
  components: {
    SearchBar,
    JobList,
    JobPosting
  },
  data() {
    return {
      selectedJob: null,
      allJobs: [],
      searchResults: [],
      searchLoading: false,
      isSearchActive: false,
      loadingAllJobs: false
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
        console.error('Failed to load all jobs:', error);
      } finally {
        this.loadingAllJobs = false;
      }
    },
    handleJobSelection(job) {
      this.selectedJob = job;
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
        console.error('Search failed:', error);
        this.searchResults = [];
      } finally {
        this.searchLoading = false;
      }
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