<script setup>
import { ref } from 'vue';
import { useRecipeStore, DAYS, SLOTS } from '../stores/recipes';
import RecipeCatalogue from '../components/RecipeCatalogue.vue';

const store = useRecipeStore();
const genCount = ref(7); // Nombre de repas par défaut

const catalogue = ref(null);
function openCatalogue(daySelected, slotSelected) {
  catalogue.value = {
    day: daySelected,
    slot: slotSelected,
  };
}


const recipeById = (id) => store.recipes.find(r => r.id === id)
</script>

<template>
  <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
      <h1 class="text-2xl font-bold">Planning de la semaine</h1>
      <p class="text-sm text-slate-500">Glisse une recette sur un créneau, ou clique-les pour les placer.</p>
    </div>
  </div>

  <!-- Barre de génération aléatoire -->
  <div class="mb-4 flex flex-wrap items-center gap-3">
    <button @click="store.generateMeals(genCount)" class="rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">
      🎲 Générer des repas
    </button>
    <label class="text-sm text-slate-500">Nombre :
      <input v-model.number="genCount" class="ml-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm"/>
    </label>
    <button @click.stop="store.clearPlanner()" class="rounded-xl px-4 py-2.5 text-sm font-semibold border border-black cursor-pointer">
      Vider le planning
    </button>
  </div>

  <div>
    <!-- ══ GRILLE DE LA SEMAINE ══ 7 colonnes en desktop, 2 en tablette, 1 en mobile. -->
    <div class="grid grid-cols-1 gap-2.5 md:grid-cols-2 lg:grid-cols-4">
      <section v-for="day in DAYS" :key="day" class="min-h-56 rounded-xl border border-slate-200 bg-white p-2.5">
        <h4 class="mb-2 text-center text-xs font-bold capitalize">{{ day }}</h4>
        <div v-for="slot in SLOTS" :key="slot"
          @click="openCatalogue(day, slot)"
          class="flex items-center justify-center mb-2 min-h-16 rounded-xl border-[1.5px] border-dashed p-2 text-xs cursor-pointer">
          <span class="mb-1 block text-[10px] font-bold uppercase tracking-wide text-slate-400">
            {{ slot }}
          </span>

          <template v-if="recipeById(store.planner[day][slot])">
            <div class="flex items-center gap-1.5 text-left">
              <span class="flex-1 text-xs font-semibold text-slate-800">
                {{ recipeById(store.planner[day][slot]).name }}
              </span>
              <button @click.stop="store.removeMeal(day, slot)"
                      class="px-1 text-slate-400 hover:text-red-500">✕</button>
            </div>
          </template>
          
        </div>
      </section>
    </div>
  </div>

  <RecipeCatalogue
    v-if="catalogue"
    :recipes="store.recipes"
    @add-meal-to-slot="store.addMeal(catalogue.day, catalogue.slot, $event); catalogue=null"
    @close="catalogue = null"
  />
</template>