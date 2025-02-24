<script>
  import axios from "axios";

  export default {
    name: 'CategorySelectComponent',
    data() {
      return {
        categories: [],
        selectedCategory: ""
      };
    },
    mounted() {
      this.fetchCategories();
    },
    methods: {
      async fetchCategories() {
        try {
          const response = await axios.get("http://127.0.0.1:8080/api/categories");
          this.categories = response.data.categories;
        } catch (error) {
          console.error("Erreur lors de la récupération des catégories", error);
        }
      },
      // event"category-selected" (with value this.selectedCategory) to parent
      emitSelection() {
        this.$emit("category-selected", this.selectedCategory);
      }
    }
  };
</script>

<template>
  <!-- Sort by category -->
  <div class="grid shrink-0 grid-cols-1 focus-within:relative">
    <select id="category" name="category" aria-label="Category" 
      class="appearance-none col-start-1 row-start-1 w-full rounded-full focus:outline-none" 
      v-model="selectedCategory" 
      @change="emitSelection"
    >
      <!-- options values -->
      <option value="" class="">Catégorie</option>
      <option v-for="category in categories" :key="category.id" :value="category.id" class="light-green">
        {{ category.name }}
      </option>
    </select>
    <!-- chevron icon -->
    <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end 
        text-white sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
      <path fill-rule="evenodd"
        d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
        clip-rule="evenodd">
      </path>
    </svg>
  </div>
</template>

<style></style>