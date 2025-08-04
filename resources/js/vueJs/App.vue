<template>
    <Navigation 
      :user="currentUser"
      @open-login="showLoginModal = true" 
      @logout="handleLogout"
    />
    
    <HomePage />
    
    <LoginModal 
      :show="showLoginModal" 
      @close="showLoginModal = false" 
      @login-success="handleLoginSuccess" 
    />
</template>

<script>
import HomePage from './components/pages/HomePage.vue';
import LoginModal from './components/LoginModal.vue';
import Navigation from './components/Navigation.vue';
import axios from 'axios';    export default {
        name: 'App',
        components: {
            HomePage,
            LoginModal,
            Navigation,
        },
        data() {
            return {
                showLoginModal: false,
                currentUser: null
            };
        },
        mounted() {
            this.checkAuthStatus();
        },
        methods: {
            async checkAuthStatus() {
                const user = localStorage.getItem('user');
                
                if (user) {
                    try {
                        const response = await axios.get('/api/user');
                        this.currentUser = response.data;
                        localStorage.setItem('user', JSON.stringify(response.data));
                    } catch (error) {
                        this.clearAuthData();
                    }
                }
            },
            
            clearAuthData() {
                localStorage.removeItem('user');
                this.currentUser = null;
            },
            
            handleLoginSuccess(user) {
                this.currentUser = user;
                this.showLoginModal = false;
            },
            
            handleLogout() {
                this.clearAuthData();
                window.location.reload();
            }
        }
    };
</script>
