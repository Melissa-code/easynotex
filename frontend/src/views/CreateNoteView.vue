<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useNotificationStore } from '../stores/notifications.js';
import { useCategoriesFetch } from '../composables/categoriesFetch.js';
import { useNoteFormValidations } from '../composables/noteFormValidations.js';
import { useFileUpload } from '../composables/fileUpload.js';
import { useNotesApi } from '../composables/notesApi.js';
import SpinnerComponent from '../components/shared/SpinnerComponent.vue'; 
import CategorySelectComponent from '../components/notes/CategorySelectComponent.vue'; 

export default {
  name: "CreateNoteView",
  components: {
    SpinnerComponent,
    CategorySelectComponent
  },    
  setup() {
    const router = useRouter();
    const notificationStore = useNotificationStore();

    // Use composables
    const { categories } = useCategoriesFetch();
    const errorMessage = ref('');
    const { isFormValid, validateForm, validateImage } = useNoteFormValidations();
    const { selectedFile, handleFileUpload, resetFileInput } = useFileUpload(validateImage);
    const { isSubmitting, createNote } = useNotesApi();  

    const formData = ref({
      title: "",
      category: "",
      content: "",
      image: null,
      isFavorite: false,
    });

    const isOpen = ref(false)// if select is open 

    // Validation (en temps réel avec computed)
    const isFormValidComputed = isFormValid(formData);

    //Prevent default form submission and validate required fields
    const submitForm = async (event) => {
      event.preventDefault();

      // Validations data
      if (!validateForm(formData.value)) {
        return;
      }

      if (isSubmitting.value) return; // avoid multiple submissions
      
      // create note
      const result = await createNote(formData.value, selectedFile.value);
      
      if (result.success) {
        console.log('Note créée !!! :', result.data);
        
        // Reset form data
        formData.value = {
          title: "",
          category: "",
          content: "",
          image: null,
          isFavorite: false,
        };
        resetFileInput(); // Reset file input too

        router.push('/');
        
        notificationStore.setSuccess('Note créée avec succès !');
      } else {
        console.log('Error resultat :', result); 
        errorMessage.value = result.error;
      }
    }

    return { 
      formData, 
      errorMessage, 
      handleFileUpload, 
      submitForm, 
      isFormValid: isFormValidComputed, 
      isSubmitting,  
      isOpen,
      categories,
      selectedFile
    };
  }
};
</script>
  
<template>
  <div class="container-create-note flex items-center justify-center bg-[--yellow-light]">

    <div class="w-full max-w-xl max-h-full bg-[--yellow] border border-[--dark-green] rounded-2xl flex flex-col overflow-hidden">
      <!-- Title h1 -->
      <div class="bg-[--yellow]  border-b border-[--dark-green]">
        <h1 class="text-2xl font-bold text-center text-[--dark-green]">
          Ajouter une note
        </h1>
      </div>

      <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-4 bg-white p-6 rounded-b-2xl flex-1 overflow-y-auto">
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
              @click="isOpen = !isOpen"
              class="appearance-none bg-transparent border-none w-full text-[#7A7A7A] py-1 px-2 leading-tight focus:outline-none focus:bg-[#4ECDC4] focus:text-[#7A7A7A] transition-colors duration-200">
              <option value="" class="bg-[#4ECDC4] focus:text-[#7A7A7A]">Sélectionner une catégorie *</option>
              <option v-for="category in categories" :key="category.id" :value="category.id" class="light-green">
                {{ category.name }}
              </option>
            </select> 
            <!-- Arrow icon -->
            <div class="pointer-events-none absolute right-2 text-[#7A7A7A] transition-transform duration-300"
              :class="{ 'rotate-180': isOpen }">
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

          <!-- Required fields note indication -->
          <div>
            <small class="text-[--light-green]">* Champs obligatoires</small>
          </div>

          <!-- Error messages -->
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
              :disabled="isSubmitting"
              :class="{ 'opacity-100 cursor-not-allowed': isSubmitting }"
              class="btn-create-note rounded-full flex items-center justify-center "
            >      
              <span class="flex items-center gap-2">
               <SpinnerComponent 
                  v-if="isSubmitting" 
                  size="16px" 
                  border-width="2px" 
                  :full-height="false"
                  color="#F7FFF7"
                  background-color="#FFE66D"
                />
                {{ isSubmitting ? 'Ajout'  : 'Ajouter' }}
              </span>
            </button>
          </div>
      </form>
    </div>
  </div>
</template>