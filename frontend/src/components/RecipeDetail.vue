<script setup>

const props = defineProps({recipe: { type: Object, required: true }});
const emit = defineEmits(['close']);

</script>

<template>
    <Transition appear name="fade">
        <div class="fixed inset-0 bg-black/50 z-40" @click="$emit('close')"></div>
    </Transition>
    <Transition appear name="slide">
        <div class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 flex flex-col p-5">
            <header>
                <button @click="$emit('close')" class="text-gray-400 cursor-pointer hover:text-gray-700">X</button>
                <div class="flex justify-between items-center my-2">
                    <h2 class="font-bold text-lg text-gray-800">{{ recipe.recipe.name }}</h2>
                    <p class="mt-1 flex gap-3 text-s text-slate-500"><span>⏱️ {{ recipe.recipe.time }} min</span></p>
                </div>
                <!--Tags à ajouter-->
                <p v-if="recipe.recipe.note"><strong>Info :</strong> {{ recipe.recipe.note }}</p>
            </header>
            <div class="overflow-y-auto">
                <div>
                    <h3>Ingrédients</h3>
                    <ul v-for="ing in recipe.ingredients">
                        <li>{{ ing.name }} : {{ ing.quantity }} {{ ing.unit }}</li>
                    </ul>
                </div>
                <div>
                    <h3>Etapes</h3>
                    <ul v-for="step in recipe.steps">
                        <li><span class="rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">{{ step.step_number }}</span> {{ step.step_description }}</li>
                    </ul>
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