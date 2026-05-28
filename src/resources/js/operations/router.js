import { createRouter, createWebHistory } from 'vue-router';
import { bootstrapAuth, hasToken } from './auth';
import AppLayout from './views/AppLayout.vue';
import DashboardView from './views/DashboardView.vue';
import LoginView from './views/LoginView.vue';
import ResourceView from './views/ResourceView.vue';

export const routes = [
    { path: '/', redirect: '/app/dashboard' },
    { path: '/app/login', name: 'login', component: LoginView, meta: { guest: true } },
    {
        path: '/app',
        component: AppLayout,
        meta: { auth: true },
        children: [
            { path: 'dashboard', name: 'dashboard', component: DashboardView },
            { path: 'brands', name: 'brands', component: ResourceView, props: { resourceName: 'brands' } },
            { path: 'campaigns', name: 'campaigns', component: ResourceView, props: { resourceName: 'campaigns' } },
            { path: 'prospects', name: 'prospects', component: ResourceView, props: { resourceName: 'prospects' } },
            { path: 'creators', name: 'creators', component: ResourceView, props: { resourceName: 'creators' } },
            { path: 'social-accounts', name: 'social-accounts', component: ResourceView, props: { resourceName: 'socialAccounts' } },
            { path: 'cities', name: 'cities', component: ResourceView, props: { resourceName: 'cities' } },
            { path: 'tags', name: 'tags', component: ResourceView, props: { resourceName: 'tags' } },
        ],
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to) => {
    if (to.meta.auth) {
        if (!hasToken()) {
            return { name: 'login', query: { redirect: to.fullPath } };
        }

        await bootstrapAuth();
        if (!hasToken()) {
            return { name: 'login', query: { redirect: to.fullPath } };
        }
    }

    if (to.meta.guest && hasToken()) {
        await bootstrapAuth();
        if (hasToken()) {
            return { name: 'dashboard' };
        }
    }
});
