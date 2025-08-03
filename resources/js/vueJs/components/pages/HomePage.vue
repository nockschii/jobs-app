<template>
  <div class="home-page">
    <div class="search-section">
      <SearchBar @search="handleSearch" />
    </div>
    
    <div class="content">
      <JobList @job-selected="handleJobSelection" />
      <JobPosting :selected-job="selectedJob" />
    </div>
  </div>
</template>

<script>
import SearchBar from '../search/SearchBar.vue';
import JobList from '../jobs/JobList.vue';
import JobPosting from '../jobs/JobPosting.vue';

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
      searchQuery: ''
    }
  },
  methods: {
    handleJobSelection(job) {
      this.selectedJob = job;
    },
    handleSearch(query) {
      this.searchQuery = query;
      // TODO: API call für search
      console.log('Searching for:', query);
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