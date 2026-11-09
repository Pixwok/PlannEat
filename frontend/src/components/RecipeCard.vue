<script setup>
defineProps({ recipe: { type: Object, required: true } })

// Action possible
const emit = defineEmits(['open', 'edit', 'delete'])
</script>

<template>
  <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white
                  shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
    <!-- Vignette : l'emoji sur fond dégradé pastel (à remplacer par photo un jour) -->
    <div @click="$emit('open', recipe.id)" class="grid h-28 place-items-center bg-gradient-to-br from-emerald-50 to-slate-50 text-5xl cursor-pointer">
      {{ recipe.image ?? '🍽️' }}
    </div>

    <div class="p-3.5">
      <span class="mb-1 inline-block rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">
        {{ recipe.difficulty ?? 'Facile' }} <!--Remplacer par les tags-->
      </span>
      <h3 class="font-semibold">{{ recipe.name }}</h3>
      <p class="mt-1 flex gap-3 text-xs text-slate-500">
        <span>⏱️ {{ recipe.time }} min</span>
      </p>

      <div class="mt-2.5 flex gap-1">
        <button @click="$emit('edit', recipe)"
                class="rounded-lg px-2 py-1 text-sm text-slate-500 hover:bg-slate-100 cursor-pointer">✏️ Modifier
        </button>
        <button @click="$emit('delete', recipe.id)"
                class="rounded-lg px-2 py-1 text-sm text-slate-500 hover:bg-red-50 hover:text-red-500 cursor-pointer">🗑️ Supprimer
        </button>
      </div>
    </div>
  </article>
</template>