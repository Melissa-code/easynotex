<script>
    import axios from 'axios';
    import { format } from 'date-fns';

    export default {
        name: 'NotesComponent',
        data() {
            return {
                notes: [],
            };
        },
        methods: {
            async fetchNotes() {
                try {
                    const response = await axios.get('http://127.0.0.1:8080/api/notes/user/3');
                    this.notes = response.data;
                } catch (error) {
                    console.error('Erreur lors de la récupération des notes:', error);
                }
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
        mounted() {
            this.fetchNotes();
        },
    };
</script>

<template>
    <section class="py-10 md:py-20">
        <div class="container mx-auto px-4">
            <!-- Page title h1 -->
            <div class="title">
                <h1 class="hidden">Toutes mes Notes</h1>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <!-- Cards -->
                <div class="bg-white rounded-2xl border border-[--dark-green] overflow-hidden" v-for="note in notes"
                    :key="note.id">
                    <!-- Title -->
                    <div class="p-4 border-b border-b-[--dark-green] bg-[--yellow-light] text-center">
                        <h3 class="text-[--dark-green]">{{ note.title.toUpperCase() }}</h3>
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
</style>
