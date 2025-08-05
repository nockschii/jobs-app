<template>
  <!-- Modal Overlay -->
  <div v-if="show" class="modal-overlay" @click="closeModal">
    <div class="modal-content" @click.stop>
      <!-- Modal Header -->
      <div class="modal-header">
        <h2>Login</h2>
        <button @click="closeModal" class="close-btn">&times;</button>
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleSubmit">
        <!-- Email -->
        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" type="email" required v-model="form.email" placeholder="Email address">
        </div>

        <!-- Password -->
        <div class="form-group">
          <label for="password">Password</label>
          <input id="password" type="password" required v-model="form.password" placeholder="Password">
        </div>

        <!-- Remember Me -->
        <div class="form-group">
          <label class="checkbox-label">
            <input type="checkbox" v-model="form.remember">
            Remember me
          </label>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="error-message">
          {{ error }}
        </div>

        <!-- Submit Button -->
        <button type="submit" :disabled="loading" class="submit-btn">
          {{ loading ? 'Signing in...' : 'Sign in' }}
        </button>

        <!-- Demo Info -->
        <div class="demo-info">
          <p><strong>Demo:</strong> ralph@email.com / Test123!</p>
          <button type="button" @click="fillTestUser" class="demo-btn">
            Test User einfüllen
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { login } from '@api/authApi.js';

export default {
  name: 'LoginModal',
  props: {
    show: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      loading: false,
      error: '',
      form: {
        email: '',
        password: '',
        remember: false
      }
    };
  },
  methods: {
    closeModal() {
      this.$emit('close');
      this.clearForm();
    },

    clearForm() {
      this.form = {
        email: '',
        password: '',
        remember: false
      };
      this.error = '';
    },

    fillTestUser() {
      this.form.email = 'ralph@email.com';
      this.form.password = 'Test123!';
    },

    async handleSubmit() {
      this.loading = true;
      this.error = '';

      try {
        const response = await login(this.form);

        if (response.data && response.data.user) {
          this.$emit('login-success', response.data.user);
          this.closeModal();
        } else {
          console.log('Unexpected response structure:', response.data);
          this.error = 'Unexpected response from server';
        }

      } catch (error) {
        if (error.response?.status === 422) {
          this.error = 'Invalid credentials';
        } else if (error.response?.status === 419) {
          this.error = 'Session expired. Please refresh the page.';
        } else {
          this.error = error.response?.data?.message || 'Login failed. Please try again.';
        }
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 8px;
  padding: 2rem;
  width: 100%;
  max-width: 400px;
  margin: 1rem;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.modal-header h2 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: bold;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #666;
}

.close-btn:hover {
  color: #333;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-group input[type="email"],
.form-group input[type="password"] {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.form-group input:focus {
  outline: none;
  border-color: #4f46e5;
}

.checkbox-label {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.checkbox-label input {
  margin-right: 0.5rem;
}

.error-message {
  background: #fee;
  color: #c53030;
  padding: 0.75rem;
  border-radius: 4px;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.submit-btn {
  width: 100%;
  background: #4f46e5;
  color: white;
  padding: 0.75rem;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  margin-bottom: 1rem;
}

.submit-btn:hover {
  background: #4338ca;
}

.submit-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.demo-info {
  background: #f0f9ff;
  padding: 1rem;
  border-radius: 4px;
  text-align: center;
}

.demo-info p {
  margin: 0 0 0.5rem 0;
  font-size: 0.9rem;
  color: #1e40af;
}

.demo-btn {
  background: #2563eb;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  font-size: 0.9rem;
  cursor: pointer;
}

.demo-btn:hover {
  background: #1d4ed8;
}
</style>
