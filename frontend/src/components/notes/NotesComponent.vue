<script>
    import axios from 'axios';
    import { format } from 'date-fns';

    export default {
        name: 'NotesComponent',
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
                //Change state
                this.showFavorites = !this.showFavorites; 
                //load notes by state
                this.fetchNotes(); 
            },
            formatDate(dateString) {
                return format(new Date(dateString), 'dd/MM/yyyy');
            },
            getDisplayedDate(note) {
                if (new Date(note.updated_at) > new Date(note.created_at)) {
                    return `Modifiée le ${this.formatDate(note.updated_at)}`;
                }
                return `Créée le ${this.formatDate(note.created_at)}`;
            },
            diplayFavorite(isFavorite) {
                if (isFavorite === 1) {
                    return `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>
                    `;
                } 
            },
            truncate(text, maxlength) {
                return (text.length > maxlength) ?
                    text.slice(0, maxlength - 1) + '…' : text;
            },
        },
        mounted: function () {
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
            <!-- Page title h1 -->
            <div class="title">
                <h1 class="hidden">Toutes mes Notes</h1>
            </div>
            <div class="mx-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <!-- Cards -->
                <div class="bg-white rounded-2xl border border-[--dark-green] overflow-hidden" v-for="note in notes"
                    :key="note.id">
                    <!-- Title -->
                    <div class="p-4 border-b border-b-[--dark-green] bg-[--yellow-light] text-center">
                        <h3 class="text-[--dark-green]">{{ truncate(note.title.toUpperCase(), 50) }}</h3>
                    </div>
                    <!-- Infos -->
                    <div class="p-4">
                        <ul>
                            <li class="text-sm mb-4">{{ getDisplayedDate(note) }}</li>
                            <li class="mb-4 font-semibold flex justify-between">
                                <span class="">{{ note.category_name }}</span>
                                <span v-if="note.isFavorite === 1" class="favorite-icon"
                                    v-html="diplayFavorite(note.isFavorite)">
                                </span>
                            </li>
                            <li class="text-justify">{{ truncate(note.content, 150) }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
    .favorite-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px; 
        height: 20px; 
        border-radius: 50%; 
        background-color: var(--dark-green);
    }

    select  {
        font-size: 1rem; 
        font-weight: 500;
    }
</style>
