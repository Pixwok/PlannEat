<script setup>
import { ref } from 'vue';
import { useRecipeStore } from '../stores/recipes';

const store = useRecipeStore();
const genCount = ref(7); // Nombre de repas par défaut

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
    <button @click="" class="rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark">
      🎲 Générer des repas
    </button>
    <label class="text-sm text-slate-500">Nombre :
      <input v-model.number="genCount" class="ml-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm"/>
    </label>
  </div>

  <div class="grid items-start gap-4 lg:grid-cols-[260px_1fr]">
    <!-- ══ PALETTE DES RECETTES (colonne à gauche en desktop, au-dessus en mobile) ══ -->
    <aside class="rounded-2xl border border-slate-200 bg-white p-3.5">
      <h3 class="mb-2 text-sm font-semibold">📚 Catalogue</h3>
      <div v-for="recipe in store.recipes" :key="recipe.id"
           draggable="true"
           @dragstart="onDragStart(recipe)"
           @click="toggleSelect(recipe)"
           class="flex cursor-grab items-center gap-2.5 rounded-lg px-2 py-2 text-sm
                  hover:bg-slate-50"
           :class="{ 'bg-emerald-100': store.selectedRecipeId === recipe.id }">
        <span class="text-xl">{{ recipe.emoji ?? '🍽️' }}</span>{{ recipe.name }}
      </div>
      <p class="mt-2.5 text-xs leading-relaxed text-slate-500">
        💡 Astuce mobile : tape une recette (elle s'illumine), puis tape un créneau.
      </p>
    </aside>

    <!-- ══ GRILLE DE LA SEMAINE ══
         7 colonnes en desktop, 2 en tablette, 1 en mobile. -->
    <div class="grid grid-cols-1 gap-2.5 min-[900px]:grid-cols-7 md:grid-cols-2">
      <section v-for="day in DAYS" :key="day"
               class="min-h-56 rounded-xl border border-slate-200 bg-white p-2.5">
        <h4 class="mb-2 text-center text-xs font-bold capitalize">{{ day }}</h4>

        <!-- dragover.prevent = indispensable, sinon le navigateur refuse le dépôt -->
        <div v-for="slot in SLOTS" :key="slot"
             @dragover.prevent
             @drop.prevent="onDrop(day, slot)"
             @click="tapSlot(day, slot)"
             class="mb-2 min-h-16 rounded-xl border-[1.5px] border-dashed p-2 text-center text-xs"
             :class="recipeById(store.planner[day][slot])
                      ? 'border-solid border-slate-200 bg-slate-50'
                      : 'border-slate-200 text-slate-400'">

          <span class="mb-1 block text-[10px] font-bold uppercase tracking-wide text-slate-400">
            {{ SLOT_LABELS[slot] }}
          </span>

          <!-- Créneau rempli : nom + bouton retirer -->
          <template v-if="recipeById(store.planner[day][slot])">
            <div class="flex items-center gap-1.5 text-left">
              <span>{{ recipeById(store.planner[day][slot]).emoji }}</span>
              <span class="flex-1 text-xs font-semibold text-slate-800">
                {{ recipeById(store.planner[day][slot]).name }}
              </span>
              <button @click.stop="store.removeMeal(day, slot)"
                      class="px-1 text-slate-400 hover:text-red-500">✕</button>
            </div>
          </template>
          <!-- Sinon : texte fantôme -->
          <template v-else>Glisser ici</template>
        </div>
      </section>
    </div>
  </div>
</template>