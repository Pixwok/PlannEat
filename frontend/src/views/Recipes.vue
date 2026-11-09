<script setup>
import { ref, computed } from 'vue';
import { useRecipeStore } from '../stores/recipes';
import RecipeCard from "../components/RecipeCard.vue";
import RecipeModal from "../components/RecipeModal.vue";
import RecipeDetail from '../components/RecipeDetail.vue';

const store = useRecipeStore();

/* Recherche de recette */
const search = ref('')
const filteredRecipes = computed(() => {
  const q = search.value.trim().toLowerCase()
  if (!q) return store.recipes
  return store.recipes.filter(r =>
    r.name.toLowerCase().includes(q) ||
    (r.ingredients ?? []).some(i => i.name.toLowerCase().includes(q))
  )
});

// Edition et création recette
const editingRecipe = ref(null);
const showModalCreation = () => (editingRecipe.value = {});
async function showModalEdition(id) {
  await store.fetchRecipeById(id); 
  editingRecipe.value = store.recipeDetail;
  store.recipeDetail = null;
}

// Detail recette
function openRecipe(id) {
  store.fetchRecipeById(id);
}

</script>

<template>
  <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
      <h1 class="text-2xl font-bold">Mes recettes</h1>
      <p class="text-sm text-slate-500">{{ store.recipes.length }} recettes au catalogue</p>
    </div>
     <button @click="showModalCreation" class="rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark transition-colors">
      ➕ Nouvelle recette
    </button>
  </div>
   <!-- Barre de recherche -->
  <div class="mb-5 flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5">
    <span>🔍</span>
    <!-- v-model = liaison à double sens : le champ écrit dans `search`,
         et si `search` change (par code), le champ se met à jour aussi. -->
    <input v-model="search" placeholder="Rechercher une recette ou un ingrédient…" class="w-full bg-transparent outline-none" />
  </div>
  <!-- État vide (aucun résultat) -->
  <div v-if="filteredRecipes.length === 0"
       class="rounded-2xl border-2 border-dashed border-slate-200 bg-white p-14 text-center text-slate-500">
    <div class="mb-2 text-4xl">🍽️</div>
    Aucune recette ne correspond à ta recherche.
  </div>
  <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <RecipeCard
      v-for="recipe in filteredRecipes"
      :key="recipe.id"
      :recipe="recipe"
      @open="openRecipe(recipe.id)"
      @edit="showModalEdition(recipe.id)"
      @delete="store.removeRecipe($event)"
    />
  </div>
  
  <RecipeModal
    v-if="editingRecipe"
    :recipe="editingRecipe"
    @close="editingRecipe = null"
    @save="store.saveRecipe($event); editingRecipe = null"
  />

  <RecipeDetail
    v-if="store.recipeDetail"
    :recipe="store.recipeDetail"
    @close="store.recipeDetail = null"
  />
</template>