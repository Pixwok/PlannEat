import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { api } from "../services/api";

export const useRecipeStore = defineStore('recipe', () => {
    const recipes = ref([]);
    const recipeDetail = ref(null);
    const loading = ref(false);

    // Récupère les ingrédients utilisable
    async function fetchIngredients(force = false) {
        if (ingredientsLoaded && !force) return; // Vérifie si les ingrédients sont en cache (evite de refaire une requête API)
        ingredients.value = await api.getIngredients();
        ingredientsLoaded = true;
    }

    // Liste les recettes
    async function fetchRecipes() {
        loading.value = true;
        try {
            recipes.value = await api.getRecipes();
        } finally {
            loading.value = true;
        }
    }

    // Détail d'une recette
    async function fetchRecipeById(id) {
        loading.value = true;
        try {
            recipeDetail.value = await api.getRecipeById(id);
        } finally {
            loading.value = true;
        }
    }

    // Sauvegarder une recette (modification ou création)
    async function saveRecipe(recipe) {
        if (recipe.id) {
            await api.updateRecipe(recipe.id, recipe);
            const i = recipes.value.findIndex(r => r.id === recipe.id);
            recipes.value[i] = recipe // Update liste recette dans la vue
        } else {
            const recipeCreated = await api.createRecipe(recipe);
            await fetchRecipes
        }
    }

    // Suppression recette
    async function removeRecipe(id) {
        await api.deleteRecipe(id);
        recipes.value = recipes.value.filter(r => r.id !== id)
    }

    return {
        recipes, recipeDetail,
        fetchIngredients,
        fetchRecipes, fetchRecipeById, saveRecipe, removeRecipe
    };
});