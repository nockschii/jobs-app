<template>
  <div class="job-card bg-blue-100" @click="$emit('click')">
    <div class="job-card__header">
      <h3 class="job-card__title">{{ job.title }}</h3>
      <span class="job-card__employment-type">{{ job.employment_type }}</span>
    </div>
    <div class="job-card__company">{{ job.company?.name || 'Company not available' }}</div>
    <div class="job-card__location">{{ formatLocation(job) }}</div>
  </div>
</template>

<script>
export default {
  name: 'JobCard',
  props: {
    job: {
      type: Object,
      required: true
    }
  },
  emits: ['click'],
  methods: {
    formatLocation(job) {
      if (job.city && job.country) {
        return `${job.city}, ${job.country}`;
      }
      return job.location || 'Location not specified';
    }
  }
}
</script>

<style scoped>
.job-card {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.job-card:hover {
  background-color: #f8f9fa;
}

.job-card__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}

.job-card__title {
  margin: 0;
  color: #303030;
  font-size: 1.1rem;
}

.job-card__employment-type {
  background: #e9ecef;
  padding: 0.2rem 0.5rem;
  border-radius: 12px;
  font-size: 0.8rem;
  color: #495057;
}

.job-card__company {
  color: #495057;
  margin-bottom: 0.25rem;
}

.job-card__location {
  color: #888;
  font-size: 0.9rem;
}
</style>