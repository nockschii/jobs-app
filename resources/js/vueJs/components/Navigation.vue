<template>
  <nav class="navigation">
    <div class="nav-brand">
      <a href="/">Job Board</a>
    </div>
    <div class="nav-right">
      <div class="nav-menu">
        <button class="menu-btn" @click="toggleMenu">
          Menu ▼
        </button>
        <div class="dropdown" v-show="showMenu">
          <a href="#">Add Job</a>
          <a href="#">My Jobs</a>
          <a href="#">My Companies</a>
        </div>
      </div>
      <button class="login-btn">Login</button>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navigation',
  data() {
    return {
      showMenu: false
    }
  },
  methods: {
    toggleMenu() {
      this.showMenu = !this.showMenu;
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
</style>