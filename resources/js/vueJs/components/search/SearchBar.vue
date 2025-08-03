<template>
  <div class="search-bar">
    <div class="search-container">
      <div class="search-input-group">
        <input 
          type="text" 
          v-model="searchQuery"
          placeholder="Search for job title..."
          class="search-input"
          @keyup.enter="performSearch"
        />
        <button @click="performSearch" class="search-btn">Search</button>
      </div>
      
      <div class="filters">
        <select v-model="locationFilter" class="filter-select">
          <option value="">All Locations</option>
          <option value="Vienna">Vienna</option>
          <option value="Remote">Remote</option>
          <option value="Salzburg">Salzburg</option>
        </select>
        
        <select v-model="employmentTypeFilter" class="filter-select">
          <option value="">All Types</option>
          <option value="Full-time">Full-time</option>
          <option value="Part-time">Part-time</option>
          <option value="Contract">Contract</option>
        </select>
        
        <button @click="clearFilters" class="clear-btn">Clear</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SearchBar',
  data() {
    return {
      searchQuery: '',
      locationFilter: '',
      employmentTypeFilter: ''
    }
  },
  methods: {
    performSearch() {
      const searchData = {
        query: this.searchQuery,
        location: this.locationFilter,
        employment_type: this.employmentTypeFilter
      };
      this.$emit('search', searchData);
    },
    clearFilters() {
      this.searchQuery = '';
      this.locationFilter = '';
      this.employmentTypeFilter = '';
      this.performSearch();
    }
  }
}
</script>

<style scoped>
.search-bar {
  width: 100%;
}

.search-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.search-input-group {
  display: flex;
  gap: 0.5rem;
}

.search-input {
  flex: 1;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.search-btn {
  background: #007bff;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
}

.search-btn:hover {
  background: #0056b3;
}

.filters {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.filter-select {
  padding: 0.4rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.clear-btn {
  background: #6c757d;
  color: white;
  border: none;
  padding: 0.4rem 0.8rem;
  border-radius: 4px;
  cursor: pointer;
}

.clear-btn:hover {
  background: #545b62;
}
</style>