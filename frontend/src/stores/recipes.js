import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { api } from "../services/api";

export const DAYS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
export const SLOTS = ['midi', 'soir'];

export const useRecipeStore = defineStore('recipe', () => {
    const recipes = ref([]);
    const loading = ref(false);
    const recipeDetails = ref({});
    const detailsLoading = ref(false);

    const planner = ref(
        Object.fromEntries(DAYS.map(day => [day, { midi: null, soir: null }]))
    );

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
        if (recipeDetails.value[id]) return recipeDetails.value[id] // déjà en cache
        const detail = await api.getRecipeById(id)
        recipeDetails.value[id] = detail 
        return detail
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

    // Gestion du planning
    function addMeal(day, slot, recipeId) {
        planner.value[day][slot] = recipeId;
    }

    function removeMeal(day, slot) {
        planner.value[day][slot] = null;
    }

    function clearPlanner() {
        DAYS.forEach(day => SLOTS.forEach(slot => {
            planner.value[day][slot] = null
        }));
    }

    function generateMeals(numberMeal) {
        // Créneau libre
        const emptySlot = [];
        DAYS.forEach(day => SLOTS.forEach(slot => {
            if (!planner.value[day][slot]) emptySlot.push([day, slot])
        }));
        //Tirage au sort
        for (let i = 0; i < numberMeal && emptySlot.length > 0; i++) {
            const index = Math.floor(Math.random() * emptySlot.length);
            const [day, slot] = emptySlot.splice(index, 1)[0];
            const recipe = recipes.value[Math.floor(Math.random() * recipes.value.length)];
            planner.value[day][slot] = recipe.id;
        }
    }

    // Récupération des ID de recette dans planning
    const plannedRecipeIds = computed(() => {
        const ids = new Set() // Set = pas de doublon si une recette est planifiée 3 fois
        DAYS.forEach(day => SLOTS.forEach(slot => {
        const id = planner.value[day][slot]
        if (id) ids.add(id)
        }))
        return [...ids]
    })

    async function fetchMissingDetails(ids) {
        const missing = [...new Set(ids)].filter(id => id && !recipeDetails.value[id])
        if (missing.length === 0) return // test cache
        detailsLoading.value = true
        try {
            await Promise.all(missing.map(id => fetchRecipeById(id)))
        } finally {
            detailsLoading.value = false
        }
    }

    function fetchPlannerDetails() {
        return fetchMissingDetails(plannedRecipeIds.value)
    }

    // Gestion dynamique de la liste de course
    const shoppingList = computed(() => {
        const map = new Map();
        DAYS.forEach(day => SLOTS.forEach(slot => {
            const id = planner.value[day][slot]
            const detail = id ? recipeDetails.value[id] : null
            if (!detail) return
            const ingredients = detail.ingredients ?? []
            ingredients.forEach(ing => {
                const key = `${ing.name}`
                if (!map.has(key)) map.set(key, { name: ing.name, quantity: [], unit: ing.unit })
                map.get(key).quantity.push(ing.quantity) // cumule des ingrédients
            })
        }))
        return [...map.values()]
    });

    return {
        recipes, recipeDetails, planner,
        fetchIngredients,
        fetchRecipes, fetchRecipeById, saveRecipe, removeRecipe,
        addMeal, removeMeal, clearPlanner, generateMeals,
        fetchPlannerDetails,
        shoppingList
    };
});