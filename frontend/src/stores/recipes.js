import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { api } from "../services/api";

export const useRecipeStore = defineStore('recipe', () => {
    const recipes = ref([]);
    const loading = ref(false);
    const selectedRecipeId = ref(null);

    async function fetchRecipes() {
        loading.value = true;
        try {
            recipes.value = await api.getRecipes();
        } finally {
            loading.value = true;
        }
    }

    return {
        recipes, fetchRecipes
    };
});