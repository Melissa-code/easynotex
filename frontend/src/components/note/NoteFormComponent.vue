<script>
import { ref, computed } from 'vue';
import SpinnerComponent from '../shared/SpinnerComponent.vue';

export default {
  name: "NoteFormComponent",
  components: {
    SpinnerComponent
  },
  props: {
    formData: {
      type: Object,
      required: true
    },
    categories: {
      type: Array,
      default: () => []
    },
    selectedFile: {
      type: Object,
      default: null
    },
    isSubmitting: {
      type: Boolean,
      default: false
    },
    errorMessage: {
      type: String,
      default: ''
    }
  },
  emits: ['submit', 'file-upload', 'back', 'update:form-data'],
  setup(props, { emit }) {
    const isOpen = ref(false);
    
    // Computed pour gérer le v-model bidirectionnel
    const localFormData = computed({
      get() {
        return props.formData;
      },
      set(value) {
        emit('update:form-data', value);
      }
    });

    return {
      isOpen,
      localFormData
    };
  }
};
</script>

<template>
  <!-- Form -->
  <form 
    @submit.prevent="$emit('submit', $event)" 
    class="space-y-4 bg-white p-6 rounded-b-2xl flex-1 overflow-y-auto"
    data-testid="note-creation-form"
  >
    <!-- Title note -->
    <div class="flex items-center border-b border-[--light-green] py-2">
      <input 
        type="text" 
        placeholder="TITRE DE LA NOTE *" 
        aria-label="title_note" 
        v-model="localFormData.title"
        class="appearance-none bg-transparent border-none w-full placeholder-[#7A7A7A] mr-3 py-1 px-2 leading-tight focus:outline-none" 
        required
      />
    </div>
    
    <!-- Category -->
    <div class="relative flex items-center border-b border-[--light-green] py-2">
      <select
        required
        v-model="localFormData.category"
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
          v-model="localFormData.content"
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
        @change="$emit('file-upload', $event)"
        accept="image/*"
        aria-label="image_upload"
      />
      <label for="image_upload" class="flex justify-between items-center w-full px-2 py-1 cursor-pointer">
        <span class="text-[#7A7A7A]">
          {{ selectedFile ? selectedFile.name : 'Choisir une image' }}
        </span>
        <i class="far fa-image" style="color: rgb(122, 122, 122);"></i>
      </label>
    </div>
    
    <!-- Favorite -->
    <div class="flex items-center py-2">
      <input 
        type="checkbox" 
        id="is_favorite" 
        class="mr-2 cursor-pointer accent-[--light-green] focus:ring-[--dark-green] focus:ring-opacity-50" 
        v-model="localFormData.isFavorite"
        aria-label="is_favorite"
      />
      <label for="is_favorite" class=" text-[#7A7A7A]">Marquer comme favori</label>
    </div>

    <!-- Required fields note indication -->
    <div>
      <small class="text-[--light-green] required-fields">* Champs obligatoires</small>
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
        @click="$emit('back')">
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
          {{ isSubmitting ? 'Ajout'  : 'Créer' }}
        </span>
      </button>
    </div>
  </form>
</template>