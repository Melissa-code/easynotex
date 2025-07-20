<script>
import NotesListComponent from '../components/notes/NotesListComponent.vue'
import CategorySelectComponent from '../components/notes/CategorySelectComponent.vue'
import SearchBarComponent from '../components/notes/SearchBarComponent.vue'
import CreateNoteBannerComponent from '../components/notes/CreateNoteBannerComponent.vue'
import PaginationComponent from '../components/shared/PaginationComponent.vue'
import NotificationComponent from '../components/shared/NotificationComponent.vue'; 
import axios from 'axios'

  export default {
      name: 'NotesView',
      components: {
          NotesListComponent,
          CategorySelectComponent,
          SearchBarComponent, 
          CreateNoteBannerComponent, 
          NotificationComponent,
          PaginationComponent,
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
                ? `${import.meta.env.VITE_API_URL}/api/notes/favorite/user/1`
                : `${import.meta.env.VITE_API_URL}/api/notes/user/1`;

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
          },
      
      },
      mounted() {
          this.fetchNotes();
      }
  };
</script>

<template>
    <div>
        <!-- search a note -->
        <SearchBarComponent />
        <!-- link to create a new note -->
        <CreateNoteBannerComponent />
        <section class="py-4">
            <div class="container mx-auto px-4">
                <div class="pb-4 flex justify-end items-center">
                    <span class="mr-2">Trier par </span>
                    <!-- sort by favorites (button) -->
                    <button class="rounded-full mr-2" @click="toggleFavorites">
                        {{ showFavorites ? "Récentes" : "Favoris" }}
                    </button>
                    <!-- sort by category (select) -->
                    <CategorySelectComponent @category-selected="handleCategorySelection" />
                </div>
                <!-- notification success/error -->
                <NotificationComponent />
                <!-- notes list -->
                <NotesListComponent :notes="filteredNotes" />
            </div>
        </section>
        
        <section>
            <PaginationComponent />
        </section> 
    </div>
</template>
