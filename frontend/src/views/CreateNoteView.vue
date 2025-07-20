<script>
import { ref, computed } from 'vue';
import axios from "axios";
import { useRouter } from 'vue-router';

export default {
  name: "CreateNoteView",
  setup() {
    const router = useRouter();

    const formData = ref({
      title: "",
      category: "",
      content: "",
      image: null,
      isFavorite: false,
    });

    const isFormValid = computed(() => {
    const title = formData.value.title.trim();
    const content = formData.value.content.trim();
    const category = formData.value.category;
    // Regex : accents français et lettres Unicode
    const validCharsRegex = /^[\p{L}\p{N}\s\-_.,!?'"():;]+$/u;

    return title.length >= 2 && 
      title.length <= 100 &&
      validCharsRegex.test(title) &&
      category !== '' && 
      content.length >= 3 &&
      content.length <= 5000 &&
      validCharsRegex.test(content);
    });

    const errorMessage = ref('')

    // Handle file upload and validate image type
    const handleFileUpload = (event) => {
      const file = event.target.files[0];

      if (file) {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']

        if (allowedTypes.includes(file.type)) {
          formData.value.image = file
          errorMessage.value = ''
        } else {
          errorMessage.value = 'Format d\'image non supporté. Utilisez JPG, PNG, GIF ou WebP.'
          event.target.value = ''
        }
      }
    };

    //Prevent default form submission and validate required fields
    const submitForm = async (event) => {
      event.preventDefault();

      // Validate required fields
      const title = formData.value.title.trim();
      const content = formData.value.content.trim();
      const validCharsRegex = /^[\p{L}\p{N}\s\-_.,!?'"():;]+$/u;

      if (!validCharsRegex.test(title)) {
        errorMessage.value = 'Le titre contient des caractères non autorisés.';
        return;
      }
      if (!validCharsRegex.test(content)) {
        errorMessage.value = 'Le contenu contient des caractères non autorisés.';
        return;
      }

      if (!title || !formData.value.category || !content) {
        errorMessage.value = 'Veuillez remplir les 3 champs obligatoires.';
        return;
      }

      if (title.length < 2 || title.length > 100) {
        errorMessage.value = 'Le titre doit faire au moins 2 caractères et moins de 100 caractères.';
        return;
      }

      if (content.length < 3 || content.length > 5000) {
        errorMessage.value = 'Le contenu doit faire au moins 3 caractères et moins de 5000 caractères.';
        return;
      }
      
      errorMessage.value = ''; 

      try {
        // Create a new FormData object
        const dataToSend = new FormData()
        dataToSend.append('title', title);
        dataToSend.append('category_id', parseInt(formData.value.category));   
        dataToSend.append('content', content);
        dataToSend.append('isFavorite', formData.value.isFavorite ? 1 : 0);
        if (formData.value.image) {
          dataToSend.append('image', formData.value.image);
        } 
        // TODO: Récupérer l'ID de l'utilisateur depuis le store ou le contexte
        dataToSend.append('user_id', 1); 

        const response = await axios.post(`${import.meta.env.VITE_API_URL}/api/notes/store_note`, dataToSend, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        console.log('Note créé !!! :', response.data);
        
        // Reset form data
        formData.value = {
          title: "",
          category: "",
          content: "",
          image: null,
          isFavorite: false,
        };
        // inputs type="file" non liés bidirectionnellement avec v-model comme les autres inputs (navigateur garde le nom du fichier)
        const fileInput = document.querySelector('#image_upload');
        if (fileInput) fileInput.value = '';

        router.push('/');
        
        alert('Note créée avec succès !');

      } catch (error) {
        console.error('Error creating note:', error);
        if (error.response) {
          errorMessage.value = `Erreur ${error.response.status}: ${error.response.data.message || 'Erreur serveur'}`;
        } else {
          errorMessage.value = 'Erreur de connexion. Vérifiez que votre serveur est démarré.';
        }
      }
    }

    return { formData, errorMessage, handleFileUpload, submitForm, isFormValid };
  }
};
</script>
  
<template>
  <div class="flex justify-center items-center min-h-screen bg-[--yellow-light]">
    <div class="w-full max-w-xl bg-[--yellow] border border-[--dark-green] rounded-2xl mx-8">

      <!-- Title h1 -->
      <div class="bg-[--yellow]  border-b border-[--dark-green]">
        <h1 class="text-2xl font-bold text-center text-[--dark-green]">
          Ajouter une note
        </h1>
      </div>

      <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-4 bg-white p-6 rounded-b-2xl">
          <!-- Title note -->
          <div class="flex items-center border-b border-[--light-green] py-2">
            <input 
              type="text" 
              placeholder="TITRE DE LA NOTE *" 
              aria-label="title_note" 
              v-model="formData.title"
              class="appearance-none bg-transparent border-none w-full placeholder-[#7A7A7A] mr-3 py-1 px-2 leading-tight focus:outline-none" 
              required
            />
          </div>
          <!-- Category -->
          <div class="relative flex items-center border-b border-[--light-green] py-2">
            <select
              required
              v-model="formData.category"
              class="appearance-none bg-transparent border-none w-full text-[#7A7A7A] py-1 px-2 leading-tight focus:outline-none focus:bg-[#4ECDC4] focus:text-[#7A7A7A] transition-colors duration-200">
              <option value="" class="bg-[#4ECDC4] focus:text-[#7A7A7A]">Sélectionner une catégorie *</option>
              <option value="1" class="bg-[#4ECDC4] focus:text-[#7A7A7A]">Note personnelle *</option>
              <option value="3" class="bg-[#4ECDC4] focus:text-[#7A7A7A]">Finances-Administratif *</option>
            </select>
            <!-- Arrow icon -->
            <div class="pointer-events-none absolute right-2 text-[#7A7A7A] group-focus-within:text-white">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M5.516 7.548L10 12.032l4.484-4.484-1.06-1.06L10 9.91 6.576 6.488z" />
              </svg>
            </div>
          </div>
          <!-- Content -->
          <div class="mb-2">
            <div class="flex items-center border-b border-[--light-green] py-2">
              <textarea 
                required
                placeholder="Contenu de la note *" 
                aria-label="content_note" 
                rows="1"
                v-model="formData.content"
                class="resize-none appearance-none bg-transparent border-none w-full placeholder-[#7A7A7A] mr-3 py-1 px-2 leading-tight focus:outline-none">
              </textarea>
            </div>
            <small class="text-xs text-[--light-green] mt-1 ml-2">Appuyez sur Entrée pour ajouter une ligne</small>
          </div>
          <!-- Upload image -->
          <div class="flex items-center border-b border-[--light-green] py-2 relative">
            <input 
              type="file" 
              id="image_upload" 
              class="opacity-0 absolute inset-0 cursor-pointer" 
              @change="handleFileUpload"
              accept="image/*"
              aria-label="image_upload"
            />
            <label for="image_upload" class="flex justify-between items-center w-full px-2 py-1 cursor-pointer">
              <span class="text-[#7A7A7A]">
                {{ formData.image ? formData.image.name : 'Choisir une image' }}
              </span>
              <i class="far fa-image" style="color: #7A7A7A"></i>
            </label>
          </div>
          <!-- Favorite -->
          <div class="flex items-center py-2">
            <input 
              type="checkbox" 
              id="is_favorite" 
              class="mr-2 cursor-pointer accent-[--light-green] focus:ring-[--dark-green] focus:ring-opacity-50" 
              v-model="formData.isFavorite"
              aria-label="is_favorite"
            />
            <label for="is_favorite" class=" text-[#7A7A7A]">Marquer comme favori</label>
          </div>

          <!-- Required fields note -->
          <div>
            <small class="text-[--light-green]">* Champs obligatoires</small>
          </div>

          <!-- Error message -->
          <div v-if="errorMessage" class="text-red-500 text-sm mt-2">
            <p class="text-red-500 font-bold">ERREUR : {{ errorMessage }}</p>
          </div>

          <!-- Buttons : back & submit -->
          <div class="flex justify-center gap-4 pt-4">
            <button 
              type="button"
              class="bg-[--light-green] text-[--black] hover:bg-[--dark-green] hover:text-[--white] font-medium py-2 px-4 rounded-full btn-back"
              @click="$router.push('/')">
              Retour
            </button>
            <button 
              type="submit"
              class="btn-create-note rounded-full"
              >
              Ajouter
            </button>
          </div>
      </form>
    </div>
  </div>
</template>


<style scoped>

.custom-checkbox {
  accent-color: var(--light-green);
  border: 2px solid var(--light-green) !important;
  border-radius: 4px;
}

</style>
