import { ref } from 'vue';
import axios from 'axios';

export function useNotesApi() {
  const isSubmitting = ref(false);

  const createNote = async (formData, image = null) => {
    isSubmitting.value = true;
    
    try {
      const dataToSend = new FormData();
      dataToSend.append('title', formData.title.trim());
      dataToSend.append('category_id', parseInt(formData.category));   
      dataToSend.append('content', formData.content.trim());
      dataToSend.append('isFavorite', formData.isFavorite ? 1 : 0);
      
      if (image) {
        dataToSend.append('image', image);
      }
      
      // TODO: Récupérer l'ID de l'utilisateur depuis le store ou le contexte
      dataToSend.append('user_id', 1); 

      const response = await axios.post(`${import.meta.env.VITE_API_URL}/api/notes/store_note`, dataToSend, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      return { success: true, data: response.data };
    } catch (error) {
      console.error('Error creating note:', error);
      
      let errorMessage;
      if (error.response) {
        errorMessage = `Erreur ${error.response.status}: ${error.response.data.message || 'Erreur serveur'}`;
      } else {
        errorMessage = 'Erreur de connexion. Vérifiez que votre serveur est démarré.';
      }
      
      return { success: false, error: errorMessage };
    } finally {
      isSubmitting.value = false;
    }
  };

  // TODO: updateNote function 

  return {
    isSubmitting,
    createNote,
    // TODO : updateNote
  };
}