<script setup>
import { computed, onMounted, ref } from "vue";
import { api } from "@/services/api";
import TagModal from "@/components/TagModal.vue";

const tags = ref();

onMounted(async () => {
  const response = await api.getTags();
  tags.value = response;
})

const editingTag = ref(null);
const showModalCreation = () => (editingTag.value = {});

const createTag = async (data) => {
    api.createTags(data);
    tags.value = await api.getTags();
}

const deleteTag = (id) => {
    api.deleteTags(id);
    tags.value = tags.value.filter(r => r.id !== id)
}

</script>

<template>
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold">Mes tags</h1>
        <button @click="showModalCreation" class="rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark transition-colors">
        ➕ Ajouter un tags
        </button>
    </div>
    
    <div class="rounded-md border border-slate-200 bg-white shadow-sm p-3">
        <div v-for="tag in tags" :key="tag.id">
            <div class="border-b border-gray-400 py-3">
                <span class="text-lg">{{ tag.name }}</span>
                <button @click="deleteTag(tag.id)"
                        class="rounded-lg px-2 py-1 text-sm text-slate-500 hover:bg-red-50 hover:text-red-500 cursor-pointer">🗑️ Supprimer
                </button>
            </div>
        </div>
    </div>

    <TagModal
    v-if="editingTag"
    :tag="editingTag"
    @close="editingTag = null"
    @save="createTag($event); editingTag = null"
    />
</template>