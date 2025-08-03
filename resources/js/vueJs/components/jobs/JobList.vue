<template>
  <div class="job-list">
    <h2>Available Jobs</h2>
    <div class="jobs-container">
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
  data() {
    return {
      jobs: [
        { 
          id: 1, 
          title: 'Frontend Developer', 
          company: 'Tech Corp', 
          location: 'Vienna',
          employment_type: 'Full-time'
        },
        { 
          id: 2, 
          title: 'Backend Developer', 
          company: 'StartupXYZ', 
          location: 'Remote',
          employment_type: 'Contract'
        },
        { 
          id: 3, 
          title: 'Full Stack Developer', 
          company: 'Digital Agency', 
          location: 'Salzburg',
          employment_type: 'Part-time'
        }
      ]
    }
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

.jobs-container {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

h2 {
  margin-bottom: 1rem;
  color: #303030;
}
</style>