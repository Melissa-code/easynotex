<script>
import NoteCardComponent from './NoteCardComponent.vue';

export default {
    name: 'NotesListComponent',
    props: {
        notes: {
            type: Array,
            default: () => [] 
        }
    },
    components: {
        NoteCardComponent
    }
};
</script>

<template>
    <div class="mx-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 my-10">
        <!-- No note found -->
        <div v-if="notes.length === 0"
            class="bg-white rounded-2xl border border-[--dark-green] overflow-hidden col-span-1">
            <!-- Title -->
            <div class="p-4 border-b border-b-[--dark-green] bg-[--yellow-light] text-center">
                <h3 class="text-[--dark-green]">Aucune note trouvée</h3>
            </div>
            <!-- Infos -->
            <div class="p-4">
                <ul>
                    <br><br>
                    <li class="mb-4 font-semibold">Pas de contenu disponible</li>
                    <br /><br /><br /><br />
                </ul>
            </div>
        </div>

        <!-- List of the cards -->
        <NoteCardComponent v-for="note in notes" :key="note.id" :note="note" />
    </div>
</template>
