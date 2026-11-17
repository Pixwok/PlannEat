<script setup>
import { onMounted, computed } from "vue";
import { useRecipeStore } from "../stores/recipes.js";

const store = useRecipeStore();

onMounted(() => store.fetchPlannerDetails())

/* Résumé du planning (affiché en haut de page) */
const plannedCount = computed(() => {
  let n = 0
  for (const day in store.planner)
    for (const slot in store.planner[day])
      if (store.planner[day][slot]) n++
  return n
})
/* Nombre total d'articles, pour l'en-tête */
const totalItems = computed(() => store.shoppingList.length);

</script>

<template>
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
        <h1 class="text-2xl font-bold">Liste de courses</h1>
        <p class="text-sm text-slate-500">
            {{ plannedCount }} repas planifiés · {{ totalItems }} article(s)
        </p>
        </div>
    </div>

    <!-- État vide -->
    <div v-if="totalItems === 0"
        class="rounded-2xl border-2 border-dashed border-slate-200 bg-white p-14 text-center text-slate-500">
        <div class="mb-2 text-4xl">🛒</div>
        Ton planning est vide. Planifie des repas et la liste se remplira toute seule !
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <label v-for="item in store.shoppingList" :key="item.name"
            class="flex cursor-pointer items-center gap-3 border-b border-slate-100 px-4 py-2.5 last:border-0">
        <input type="checkbox" class="h-4 w-4 accent-primary" />
        <span class="flex-1">{{ item.name }}</span>
        <span class="text-xs text-slate-500">{{ item.quantity.reduce((accumulator, currentValue) => accumulator + currentValue, 0) }} {{ item.unit }}</span>
    </label>
    </div>
</template>