import { createRouter, createWebHistory } from 'vue-router';
import Recipes from '../views/Recipes.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'recipes', component: Recipes},
  ],
});

export default router
