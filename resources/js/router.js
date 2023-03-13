import { createRouter, createWebHistory } from 'vue-router';
import HomeView from './views/HomeView.vue';
import LoginView from './views/LoginView.vue';
import ComparisonView from './views/ComparisonView.vue';
import UniqueView from './views/UniqueView.vue';

const routes = [
    {
        path: '/',
        component: HomeView,
    },
    {
        path: '/login',
        component: LoginView,
    },
    {
        path: '/searcher',
        component: ComparisonView,
    },
    {
        path: '/unique-results',
        component: UniqueView,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
