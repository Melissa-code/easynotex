<script>
  import NotesListComponent from '../components/notes/NotesListComponent.vue'; // Chemin correct
  import CategorySelectComponent from '../components/notes/CategorySelectComponent.vue';
  import axios from 'axios';

  export default {
      name: 'NotesView',
      components: {
          NotesListComponent,
          CategorySelectComponent,
      },
      data() {
          return {
              notes: [],
              showFavorites: false,
              selectedCategory: "",
          };
      },
      computed: {
          filteredNotes() {
              return this.selectedCategory
                  ? this.notes.filter(note => note.category_id === this.selectedCategory)
                  : this.notes;
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
    <section class="py-4">
        <div class="container mx-auto px-4">
            <div class="pb-4 flex justify-end items-center">
                <span class="mr-2">Trier par </span>
                <button class="rounded-full mr-2" @click="toggleFavorites">
                    {{ showFavorites ? "Récentes" : "Favoris" }}
                </button>
                <CategorySelectComponent @category-selected="handleCategorySelection" />
            </div>
            <NotesListComponent :notes="filteredNotes" />
        </div>
    </section>
</template>
