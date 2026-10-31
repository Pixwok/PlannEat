<script setup>
import { ref, computed } from 'vue';
import { useRecipeStore } from '../stores/recipes';

const store = useRecipeStore();
</script>

<template>
  <!-- En-tête : titre à gauche, action à droite. flex-wrap = passe à la ligne sur petit écran. -->
  <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
      <h1 class="text-2xl font-bold">Mes recettes</h1>
      <p class="text-sm text-slate-500">{{ store.recipes.length }} recettes au catalogue</p>
    </div>
  </div>
</template>