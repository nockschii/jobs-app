<template>
    <Navigation />
    <HomePage />
</template>

<script>
    import HomePage from './components/pages/HomePage.vue';
    import Navigation from './components/Navigation.vue';

    export default {
        name: 'App',
        components: {
            HomePage,
            Navigation,
        }
    };
</script>
