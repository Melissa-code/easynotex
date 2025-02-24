<script>
    import axios from 'axios';
    import NoteCardComponent from './NoteCardComponent.vue';
    import CategorySelectComponent from './CategorySelectComponent.vue';

    export default {
        name: 'NotesComponent',
        components: {
            NoteCardComponent,
            CategorySelectComponent,
        },
        data() {
            return {
                notes: [],
                showFavorites: false,
                selectedCategory: "",
            };
        },
        // propr recalculées automatiquement lorsque ces données changent
        computed: {
            filteredNotes() {
                if (this.selectedCategory) {
                    return this.notes.filter(note => {
                        //console.log(typeof note.category_id)
                        //console.log(typeof this.selectedCategory)
                        return note.category_id === this.selectedCategory; 
                    });
                }
                return this.notes;
            }
        },
        methods: {
            async fetchNotes() {
                try {
                    const url = this.showFavorites
                        ? 'http://127.0.0.1:8080/api/notes/favorite/user/3'
                        : 'http://127.0.0.1:8080/api/notes/user/3';

                    const response = await axios.get(url);

                    this.notes = response.data;
                    //console.log("Notes récupérées :", this.notes);
                } catch (error) {
                    console.error('Erreur lors de la récupération des notes:', error);
                }
            },
            toggleFavorites() {
                this.showFavorites = !this.showFavorites;
                this.fetchNotes();
            },
            handleCategorySelection(categoryId) {
                this.selectedCategory = categoryId;
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
            <!-- Button: Sort by favoris -->
            <div class="pb-10 md:pb-20 flex justify-end items-center">
                <span class="mr-2">Trier par </span>
                <button class="rounded-full mr-2" @click="toggleFavorites">
                    {{ showFavorites ? "Récentes" : "Favoris" }}
                </button>
                <!-- Select: Sort by category -->
                <CategorySelectComponent @category-selected="handleCategorySelection" />
            </div>
            <!-- Notes list -->
            <div class="mx-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 my-10">
                <NoteCardComponent v-for="note in filteredNotes" :key="note.id" :note="note" />
            </div>
        </div>
    </section>
</template>

<style></style>