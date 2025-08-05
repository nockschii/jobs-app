<template>
    <Navigation :user="currentUser" @open-login="showLoginModal = true" @logout="handleLogout" />

    <HomePage :search-clear-key="searchClearKey" />

    <LoginModal :show="showLoginModal" @close="showLoginModal = false" @login-success="handleLoginSuccess" />
</template>

<script>
import HomePage from './components/pages/HomePage.vue';
import LoginModal from './components/LoginModal.vue';
import Navigation from './components/Navigation.vue';
import SearchBar from './components/search/SearchBar.vue';
import axios from 'axios';
export default {
    name: 'App',
    components: {
        HomePage,
        LoginModal,
        Navigation,
        SearchBar,
    },
    data() {
        return {
            showLoginModal: false,
            currentUser: null,
            searchClearKey: 0
        };
    },
    mounted() {
        this.checkAuthStatus();
    },
    methods: {
        async checkAuthStatus() {
            try {
                const response = await axios.get('/api/user');
                this.currentUser = response.data;
            } catch (error) {
                this.currentUser = null;
            }
        },

        handleLoginSuccess(user) {
            this.currentUser = user;
            this.showLoginModal = false;
            this.searchClearKey++;
        },

        async handleLogout() {
            try {
                await axios.post('/api/logout');
            } catch (e) { }
            this.currentUser = null;
            window.location.reload();
        }
    }
};
</script>
