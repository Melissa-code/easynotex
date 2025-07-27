<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useNotificationStore } from '../stores/notifications.js';
import { useCategoriesFetch } from '../composables/categoriesFetch.js';
import { useNoteFormValidations } from '../composables/noteFormValidations.js';
import { useFileUpload } from '../composables/fileUpload.js';
import { useNotesApi } from '../composables/notesApi.js';
import NoteFormComponent from '../components/note/NoteFormComponent.vue';

export default {
  name: "CreateNoteView",
  components: {
    NoteFormComponent
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
      isFavorite: 0,
    });

    // Validation (en temps réel avec computed)
    const isFormValidComputed = isFormValid(formData);

    //Prevent default form submission and validate required fields
    const submitForm = async (event) => {
      event.preventDefault();

      if (!validateForm(formData.value)) {
        return;
      }

      if (isSubmitting.value) return; // avoid multiple submissions btw clicks
      
      // create note
      const result = await createNote(formData.value, selectedFile.value);
      
      if (result.success) {
        console.log('Note créée  :', result.data);
        
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

    const handleBack = () => {
      router.push('/');
    }

    const updateFormData = (newFormData) => {
      formData.value = { ...newFormData };
    }

    return { 
      formData, 
      errorMessage, 
      handleFileUpload, 
      submitForm, 
      handleBack,
      updateFormData,
      isFormValid: isFormValidComputed, 
      isSubmitting,  
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
      <div class="bg-[--yellow] border-b border-[--dark-green]">
        <h1 class="text-2xl font-bold text-center text-[--dark-green]">
          Créer une nouvelle note
        </h1>
      </div>

      <!-- NoteFormComponent -->
      <NoteFormComponent 
        v-model:form-data="formData"
        :categories="categories"
        :selected-file="selectedFile"
        :is-submitting="isSubmitting"
        :error-message="errorMessage"
        @submit="submitForm"
        @file-upload="handleFileUpload"
        @back="handleBack"
      />
      
    </div>
  </div>
</template>