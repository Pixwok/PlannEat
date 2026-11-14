import { createRouter, createWebHistory } from 'vue-router';
import Recipes from '../views/Recipes.vue';
import Tags from "../views/Tags.vue";
import Planner from '../views/Planner.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'recipes', component: Recipes},
    { path: '/tags', name: 'tags', component: Tags},
    { path: '/planner', name: 'planner', component: Planner},
  ],
});

export default router
