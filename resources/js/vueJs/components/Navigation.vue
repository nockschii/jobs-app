<template>
  <nav class="navigation bg-gray-800 text-white">
    <div class="nav-brand">
      <a href="/">Job Board</a>
    </div>
    <div class="nav-right">
      <div v-if="user" class="nav-menu" ref="menuContainer">
        <button class="menu-btn" @click="toggleMenu">
          Menu ▼
        </button>
        <div class="dropdown bg-gray-800 text-white" v-show="showMenu" @click.stop>
          <a href="/jobs/create">Add Job</a>
          <a href="#" @click="showMenu = false">My Jobs</a>
          <a href="#" @click="showMenu = false">My Companies</a>
        </div>
      </div>

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
  mounted() {
    document.addEventListener('click', this.closeMenu);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.closeMenu);
  },
  methods: {
    toggleMenu(event) {
      event.stopPropagation();
      this.showMenu = !this.showMenu;
    },
    closeMenu() {
      this.showMenu = false;
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

.menu-btn {
  padding: 0.5rem 1rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.menu-btn:hover {
  background: #DBEAFE;
  color: black;
}

.dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  min-width: 150px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  z-index: 1000;
}

.dropdown a {
  display: block;
  padding: 0.5rem 1rem;
  text-decoration: none;
  border-bottom: 1px solid #eee;
}

.dropdown a:hover {
  background: #DBEAFE;
  color: black;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.welcome-text {
  color: white;
  font-weight: 500;
}

.login-btn,
.logout-btn {
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