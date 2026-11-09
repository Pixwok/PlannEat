<script setup>
import { reactive, ref, onMounted } from "vue";
import { formatToSqlTime, formatTimeToMin } from "../utils/format"
import { api } from "../services/api";

const props = defineProps({recipe: { type: Object, required: true } });
const emit = defineEmits(['close', 'save']);

// Unités disponible pour ingrédients
const UNITS = ['g', 'kg', 'ml', 'unité', 'c à soupe', 'c à café'];
const ingredients = ref([]);

// Charger les ingrédients pour les menus déroulants
onMounted(async () => {
  const response = await api.getIngredients();
  ingredients.value = response;
})

const recipeInfo = props.recipe?.recipe ?? {};
const recipeIng = props.recipe?.ingredients ?? [];
const recipeSteps = props.recipe?.steps ?? [];

const form = ref({
    name: recipeInfo.name ?? '',
    time: recipeInfo.time ?? '',
    note: recipeInfo.note ?? '',
    ingredients: recipeIng ?? [],
    steps: recipeSteps ?? [],
});

function addIngredient() {
  form.value.ingredients.push({
    uidVue : Date.now(),
    id_ingredients: null,
    quantity: null,
    unit: 'g',
  });
}
function removeIngredient(index) {
  form.value.ingredients.splice(index, 1);
}

function addStep() {
  form.value.steps.push({
    uidVue : Date.now(),
    step_number: null,
    step_description: '',
  });
}

function removeStep(index) {
  form.value.steps.splice(index, 1)
}


const submit = () => {
  if (!form.value.name.trim()) return
  // On renvoie { id: undefined } en création → le store saura qu'il faut un POST
  const payload = {
    name: form.value.name,
    time: form.value.time,
    note: form.value.note,
    ingredients: form.value.ingredients.map(ing => ({
      id_ingredients: ing.id_ingredients,
      quantity: ing.quantity,
      unit: ing.unit,
    })),
    steps: form.value.steps.map((step, i) => ({
      step_number: i + 1,
      step_description: step.step_description,
    })),
  }
  emit('save', {...payload, id: recipeInfo.id});
}

</script>

<template>
  <!-- Fond assombri, clic dessus = fermer. @submit.prevent = ne pas recharger la page -->
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/45 p-5"
       @click.self="$emit('close')">
    <form @submit.prevent="submit"
          class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6">

      <h2 class="mb-4 text-lg font-bold">
        {{ recipeInfo.id ? '✏️ Modifier la recette' : '➕ Nouvelle recette' }}
      </h2>

      <div class="mb-3">
        <label class="mb-1 block text-sm font-semibold">Nom de la recette</label>
        <input v-model="form.name" placeholder="Ex : Lasagnes aux légumes"
               class="w-full rounded-lg border border-slate-200 px-3 py-2 focus:border-primary outline-none" />
      </div>

      <div class="mb-3 flex gap-3">
        <div class="flex-1">
          <label class="mb-1 block text-sm font-semibold">Temps</label>
          <input v-model.number="form.time" type="number"
                 class="w-full rounded-lg border border-slate-200 px-3 py-2 outline-none" />
        </div>
      </div>
      <div class="mb-3 flex gap-3">
        <div class="flex-1">
          <label class="mb-1 block text-sm font-semibold">Note perso</label>
          <input v-model.number="form.note" placeholder="Description, note perso, indication,..."
                 class="w-full rounded-lg border border-slate-200 px-3 py-2 outline-none" />
        </div>
      </div>

      <!--Gestion des ingrédients-->
      <div class="mb-3">
        <label class="mb-1 block text-sm font-semibold">Ingrédients</label>
        <div v-for="(ing, index) in form.ingredients" :key="ing.uidVue" class="mb-2 flex gap-2">
          <select v-model="ing.id_ingredients" class="w-28 rounded-lg border border-slate-200 px-2 py-1.5 text-sm">
            <option v-for="i in ingredients" :key="i.id" :value="i.id">{{ i.name }}</option>
          </select>
          <input v-model="ing.quantity" placeholder="200 g"
                 class="w-24 rounded-lg border border-slate-200 px-3 py-1.5 text-sm outline-none" />
          <select v-model="ing.unit"
            class="w-28 rounded-lg border border-slate-200 px-2 py-1.5 text-sm">
            <option v-for="u in UNITS" :key="u" :value="u">{{ u }}</option>
          </select>
          <button type="button" @click="removeIngredient(index)"
                  class="px-2 text-slate-400 hover:text-red-500">✕</button>
        </div>
        <button type="button" @click="addIngredient"
                class="text-sm font-semibold text-primary hover:text-primary-dark">+ Ajouter un ingrédient</button>
      </div>

      <!--Gestion des étapes-->
      <div class="mb-3">
        <label class="mb-1 block text-sm font-semibold">Étapes</label>
        <div v-for="(step, index) in form.steps" :key="step.uidVue" class="mb-2 flex items-center gap-2">
          <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">
            {{ index + 1 }}
          </span>
          <textarea v-model="step.step_description" :placeholder="`Étape ${index + 1}…`"
                    rows="2"
                    class="flex-1 rounded-lg border border-slate-200 px-3 py-1.5 text-sm outline-none" >
          </textarea>
          <button type="button" @click="removeStep(index)"
                  class="px-2 text-slate-400 hover:text-red-500">✕</button>
        </div>
        <button type="button" @click="addStep()"
                class="text-sm font-semibold text-primary hover:text-primary-dark">+ Ajouter une étape</button>
      </div>

      <div class="mt-5 flex justify-end gap-2.5">
        <button type="button" @click="$emit('close')"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Annuler
        </button>
        <button type="submit"
                class="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-dark">
          Enregistrer
        </button>
      </div>
    </form>
  </div>
</template>