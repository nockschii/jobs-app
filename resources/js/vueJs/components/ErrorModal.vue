<template>
  <div v-if="show" class="error-modal-overlay" @click="closeModal">
    <div class="error-modal" @click.stop>
      <div class="error-modal__header">
        <h3>Error</h3>
        <button @click="closeModal" class="close-btn">&times;</button>
      </div>
      <div class="error-modal__body">
        <p>{{ message }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ErrorModal',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    message: {
      type: String,
      default: 'An error occurred'
    },
    autoClose: {
      type: Number,
      default: 5000 // 5 seconds
    }
  },
  emits: ['close'],
  watch: {
    show(newVal) {
      if (newVal && this.autoClose > 0) {
        setTimeout(() => {
          this.closeModal();
        }, this.autoClose);
      }
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
    }
  }
}
</script>

<style scoped>
.error-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.error-modal {
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
}

.error-modal__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #e9ecef;
  background: #f8d7da;
  color: #721c24;
}

.error-modal__header h3 {
  margin: 0;
  font-size: 1.25rem;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #721c24;
  padding: 0;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-btn:hover {
  background: rgba(0, 0, 0, 0.1);
  border-radius: 50%;
}

.error-modal__body {
  padding: 1rem;
}

.error-modal__body p {
  margin: 0;
  color: #495057;
  line-height: 1.5;
}
</style>
