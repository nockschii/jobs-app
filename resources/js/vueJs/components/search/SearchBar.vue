<template>
  <div class="search-bar">
    <div class="search-container">
      <div class="search-input-group">
        <input 
          type="text" 
          v-model="searchQuery"
          placeholder="Suche nach Jobtitel, Stadt oder Land..."
          class="search-input"
          @keyup.enter="performSearch"
        />
        <button 
          v-if="searchQuery" 
          @click="clearSearch" 
          class="clear-search-btn"
          type="button"
        >
          ✕
        </button>
        <button @click="performSearch" class="search-btn" :disabled="loading">
          {{ loading ? 'Suche...' : 'Suchen' }}
        </button>
      </div>
      
      <div class="search-info" v-if="searchQuery">
        <small>Suche in Stellentiteln, Städten und Ländern</small>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SearchBar',
  props: {
    loading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      searchQuery: ''
    }
  },
  methods: {
    performSearch() {
      if (!this.searchQuery.trim()) {
        this.$emit('search', '');
        return;
      }
      this.$emit('search', this.searchQuery.trim());
    },
    clearSearch() {
      this.searchQuery = '';
      this.$emit('search', '');
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
  position: relative;
}

.search-input {
  flex: 1;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.clear-search-btn {
  position: absolute;
  right: 120px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #6c757d;
  cursor: pointer;
  padding: 0.25rem;
  font-size: 1rem;
}

.clear-search-btn:hover {
  color: #dc3545;
}

.search-btn {
  background: #007bff;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  min-width: 100px;
}

.search-btn:hover {
  background: #0056b3;
}

.search-btn:disabled {
  background: #6c757d;
  cursor: not-allowed;
}

.search-info {
  color: #6c757d;
  font-size: 0.875rem;
  margin-top: 0.25rem;
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