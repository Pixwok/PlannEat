<script setup>
import { ref } from "vue";
import { api } from "../services/api";

defineProps({ tag: { type: Object, required: true } });
const emit = defineEmits(['close', 'save']);

const form = ref({
    name: '',
});

const submit = async() => {
    const payload = {
        name: form.value.name
    };

    emit('save', payload);
}

</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/45 p-5"
       @click.self="$emit('close')">
    <form @submit.prevent="submit"
          class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6">

      <h2 class="mb-4 text-lg font-bold">Nouveau tag</h2>

      <div class="mb-3">
        <label class="mb-1 block text-sm font-semibold">Nom du tag</label>
        <input v-model="form.name" placeholder="Ex : Economique"
               class="w-full rounded-lg border border-slate-200 px-3 py-2 focus:border-primary outline-none" />
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