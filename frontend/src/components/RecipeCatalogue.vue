<script setup>
import { ref, computed } from "vue";

const props = defineProps({recipes: { type: Object, required: true }});
const emit = defineEmits(['addMealToSlot', 'close']);

/* Recherche de recette */
const search = ref('')
const filteredRecipes = computed(() => {
  const q = search.value.normalize('NFD').trim().toLowerCase()
  if (!q) return props.recipes
  return props.recipes.filter(r =>
    r.name.toLowerCase().includes(q) ||
    (r.ingredients ?? []).some(i => i.name.toLowerCase().includes(q))
  )
});

</script>

<template>
    <Transition appear name="fade">
        <div class="fixed inset-0 bg-black/50 z-40" @click="$emit('close')"></div>
    </Transition>
    <Transition appear name="slide">
        <div class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 flex flex-col p-5">
            <header class="flex">
                <button @click="$emit('close')" class="text-gray-400 cursor-pointer hover:text-gray-700">X</button>
                <div class="flex justify-between items-center my-2">
                    <div class="mb-5 flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5">
                        <span>🔍</span>
                        <input v-model="search" placeholder="Rechercher une recette ou un ingrédient…" class="w-full bg-transparent outline-none" />
                    </div>
                </div>
            </header>
            <div class="overflow-y-auto">
                <div v-if="filteredRecipes.length === 0"
                    class="rounded-2xl border-2 border-dashed border-slate-200 bg-white p-14 text-center text-slate-500">
                    <div class="mb-2 text-4xl">🍽️</div>
                    Aucune recette ne correspond à ta recherche.
                </div>
                <div v-else class="">
                    <div v-for="recipe in filteredRecipes" :key="recipe.id" 
                        class="flex items-center gap-2.5 px-2 py-2 text-sm">
                        <div class="flex gap-2">
                            <span class="text-xl">{{ recipe.name }}</span>
                            <button @click="$emit('addMealToSlot', recipe.id)" class="inline-flex h-5 w-5 cursor-pointer rounded-full items-center text-white bg-primary hover:bg-primary-dark">
                                +
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style>
/* Le fond : opacité 0 → 1 */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Le panneau : hors écran à droite → à sa place */
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateX(100%);
}
</style>