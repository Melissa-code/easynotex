<script>
  import { ref, watch } from 'vue';
  import { useCategoriesFetch } from '../../composables/categoriesFetch.js';

  /**
   * CategorySelectComponent to select a category from a dropdown
   * Emits the selected category ID or null for "all categories"
   */
  export default {
    name: 'CategorySelectComponent',
    setup(props, { emit }) {
      const { categories, isLoading, error } = useCategoriesFetch();
      const selectedCategory = ref("");

      const emitSelection = () => {
        if (selectedCategory.value === "") {
          // Emit 'null' to indicate "all categories"
          emit("category-selected", null);
        } else {
          emit("category-selected", selectedCategory.value);
        }
      };

      watch(selectedCategory, () => {
        emitSelection();
      });

      return {
        categories,
        isLoading,
        error,
        selectedCategory
      };
    },
    emits: ['category-selected']
  };
</script>

<template>
  <!-- Sort by category (select the category) -->
  <div class="grid shrink-0 grid-cols-1 focus-within:relative">
    <select id="category" name="category" aria-label="Category" 
      class="appearance-none col-start-1 row-start-1 w-full rounded-full focus:outline-none" 
      v-model="selectedCategory" 
      :disabled="isLoading"
    >
      <!-- options values: categories -->
      <option value="" class="">{{ isLoading ? 'Chargement...' : 'Catégories' }}</option>
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
