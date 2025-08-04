<template>
  <nav class="navigation">
    <div class="nav-brand">
      <a href="/">Job Board</a>
    </div>
    <div class="nav-right">
      <!-- Menu nur für eingeloggte User -->
      <div v-if="user" class="nav-menu">
        <button class="menu-btn" @click="toggleMenu">
          Menu ▼
        </button>
        <div class="dropdown" v-show="showMenu">
          <a href="#">Add Job</a>
          <a href="#">My Jobs</a>
          <a href="#">My Companies</a>
        </div>
      </div>
      
      <!-- Show different content based on login status -->
      <div v-if="user" class="user-info">
        <span class="welcome-text">Hi, {{ user.name }}!</span>
        <button class="logout-btn" @click="handleLogout">Logout</button>
      </div>
      <button v-else class="login-btn" @click="openLoginModal">Login</button>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navigation',
  props: {
    user: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      showMenu: false
    }
  },
  methods: {
    toggleMenu() {
      this.showMenu = !this.showMenu;
    },
    openLoginModal() {
      this.$emit('open-login');
    },
    handleLogout() {
      this.$emit('logout');
    }
  }
}
</script>

<style scoped>
.navigation {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 2px solid #ccc;
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.nav-menu {
  position: relative;
}

.dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  border: 1px solid #ccc;
  min-width: 150px;
}

.dropdown a {
  display: block;
  padding: 0.5rem 1rem;
  text-decoration: none;
  border-bottom: 1px solid #eee;
}

.dropdown a:hover {
  background: #f5f5f5;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.welcome-text {
  color: #333;
  font-weight: 500;
}

.login-btn, .logout-btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.login-btn {
  background: #4f46e5;
  color: white;
}

.login-btn:hover {
  background: #4338ca;
}

.logout-btn {
  background: #ef4444;
  color: white;
}

.logout-btn:hover {
  background: #dc2626;
}
</style>