<script>
    import axios from 'axios';
    import NoteCardComponent from './NoteCardComponent.vue'; 

    export default {
        name: 'NotesComponent',
        components: {
            NoteCardComponent 
        },
        data() {
            return {
                notes: [],
                showFavorites: false,
            };
        },
        methods: {
            async fetchNotes() {
                try {
                    const url = this.showFavorites 
                        ? 'http://127.0.0.1:8080/api/notes/favorite/user/3'
                        : 'http://127.0.0.1:8080/api/notes/user/3';

                    const response = await axios.get(url);
                    this.notes = response.data;
                } catch (error) {
                    console.error('Erreur lors de la récupération des notes:', error);
                }
            },
            toggleFavorites() {
                this.showFavorites = !this.showFavorites;
                this.fetchNotes();
            }
        },
        mounted() {
            this.fetchNotes();
        }
    };
</script>

<template>
    <section class="py-5">
        <div class="container mx-auto px-4">
            <!-- Sort by favoris -->
            <div class="pb-10 md:pb-20 flex justify-end items-center">
                <span class="mr-2">Trier par </span>
                <button class="rounded-full mr-2"  @click="toggleFavorites" >
                    {{ showFavorites ? "Récentes" : "Favoris" }}
                </button>
                <!-- Sort by category -->
                <div class="grid shrink-0 grid-cols-1 focus-within:relative">
                    <select id="choose-category" name="choose-category" aria-label="Choose-category"
                        class="appearance-none col-start-1 row-start-1 w-full rounded-full py-1.5 pr-10 pl-4 py-2 
                        text-white focus:outline-2 focus:-outline-offset-2 focus:outline-green-200 
                        sm:text-sm/6 bg-[--dark-green] font-weight-700">
                        <option class="text-sm light-green">Catégorie</option>
                        <option class="text-sm light-green">Créativité-Loisirs</option>
                        <option class="text-sm light-green">Voyages-Sorties</option>
                    </select>
                    <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end 
                        text-white sm:size-4"
                        viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd"
                            d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd">
                        </path>
                    </svg>
                </div>
            </div>
            <!-- Notes list -->
            <div class="mx-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 my-10">
                <NoteCardComponent v-for="note in notes" :key="note.id" :note="note" />
            </div>
        </div>
    </section>
</template>

<style scoped></style>