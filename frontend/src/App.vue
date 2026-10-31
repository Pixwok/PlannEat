<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useRecipeStore } from './stores/recipes'
const store = useRecipeStore()
// Chargement des recettes UNE fois, au démarrage de l'application
if (store.recipes.length === 0) store.fetchRecipes()
</script>

<template>
  <div class="flex min-h-screen">
    <!-- ══ SIDEBAR : cachée sur mobile (hidden md:flex) ══ -->
    <!-- md = écran ≥ 768px. En dessous : invisible, la barre du bas prend le relais. -->
    <aside class="hidden md:flex w-60 flex-col gap-1 border-r border-slate-200 bg-white p-4">
      <div class="mb-4 flex items-center gap-2.5 text-lg font-bold">
        <span class="grid h-9 w-9 place-items-center rounded-xl bg-primary text-white">🍳</span>
        PlannEat
      </div>

      <!-- RouterLink = un <a> qui change de page sans recharger.
           La classe "active" s'applique automatiquement quand la route est courante. -->
      <RouterLink to="/" class="rounded-lg px-3 py-2.5 font-medium text-slate-500 hover:bg-slate-100
                                [&.router-link-active]:bg-emerald-100 [&.router-link-active]:text-emerald-700">
        📖 Recettes
      </RouterLink>
    </aside>

    <!-- ══ CONTENU DE LA PAGE COURANTE ══ -->
    <main class="flex-1 px-4 pb-24 pt-6 md:px-8 md:pb-8">
      <div class="mx-auto max-w-6xl">
        <RouterView />
      </div>
    </main>

    <!-- ══ BARRE DE NAVIGATION MOBILE : visible seulement < 768px ══ -->
    <nav class="fixed inset-x-0 bottom-0 z-40 flex justify-around border-t border-slate-200
                bg-white px-2 pb-2 pt-1.5 md:hidden">
      <RouterLink to="/" class="flex flex-col items-center gap-0.5 rounded-lg px-3 py-1.5 text-xs
                         text-slate-500 [&.router-link-active]:text-emerald-700">
        <span class="text-xl">📖</span>Recettes
      </RouterLink>
    </nav>
  </div>
</template>

<style scoped></style>
