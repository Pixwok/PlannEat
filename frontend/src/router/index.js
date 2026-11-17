import { createRouter, createWebHistory } from 'vue-router';
import Recipes from '../views/Recipes.vue';
import Tags from "../views/Tags.vue";
import Planner from '../views/Planner.vue';
import ShoppingList from '../views/ShoppingList.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'recipes', component: Recipes},
    { path: '/tags', name: 'tags', component: Tags},
    { path: '/planner', name: 'planner', component: Planner},
    { path: '/shopping', name: 'shopping', component: ShoppingList},
  ],
});

export default router
