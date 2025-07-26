import { ref, onMounted } from 'vue';
import axios from 'axios';

export function useCategoriesFetch() {
  const categories = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  const fetchCategories = async () => {
    if (process.env.NODE_ENV === "test") return;
    
    isLoading.value = true;
    error.value = null;

    try {
      const response = await axios.get(`${import.meta.env.VITE_API_URL}/api/categories`);
      categories.value = response.data.categories;
    } catch (err) {
      err.value = err;
      if (err.response) {
        console.error("Erreur lors de la récupération des catégories: ", err);
      } else {
        console.error("Erreur réseau lors de la récupération des catégories: ", err);
      }
    } finally {
      isLoading.value = false;
    }
  };

  onMounted(() => {
    fetchCategories();
  });

  return { categories, isLoading, error, fetchCategories};
} 