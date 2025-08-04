<template>
  <div class="job-list">
    <div class="job-list-header">
      <h2>{{ searchActive ? 'Suchergebnisse' : 'Verfügbare Stellen' }}</h2>
      <span v-if="searchActive" class="results-count">
        {{ jobs.length }} {{ jobs.length === 1 ? 'Ergebnis' : 'Ergebnisse' }} gefunden
      </span>
    </div>
    
    <div v-if="loading" class="loading">
      {{ searchActive ? 'Suche läuft...' : 'Lade Stellenausschreibungen...' }}
    </div>
    
    <div v-else-if="jobs.length === 0 && searchActive" class="no-results">
      <p>Keine Stellenausschreibungen für Ihre Suche gefunden.</p>
      <small>Versuchen Sie andere Suchbegriffe oder überprüfen Sie die Schreibweise.</small>
    </div>
    
    <div v-else-if="jobs.length === 0" class="no-jobs">
      <p>Momentan sind keine Stellenausschreibungen verfügbar.</p>
    </div>
    
    <div v-else class="jobs-container">
      <JobCard 
        v-for="job in jobs" 
        :key="job.id" 
        :job="job" 
        @click="selectJob(job)"
      />
    </div>
  </div>
</template>

<script>
import JobCard from './JobCard.vue';

export default {
  name: 'JobList',
  components: {
    JobCard
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
    return {}
  },
  methods: {
    selectJob(job) {
      this.$emit('job-selected', job);
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