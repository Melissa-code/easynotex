<script>
import axios from "axios";

export default {
  name: "CreateNoteView",
  data() {
    return {
      title: "",
      category: "",
      content: "",
      isFavorite: false,
    };
  },
  methods: {
    async handleSubmit(event) {
      event.preventDefault();

      // Validation 
      if (!this.title || !this.category || !this.content) {
        alert("Veuillez remplir tous les champs requis");
        return;
      }

      try {
        // API
        const response = await axios.post("/api/notes/store_note", {
          title: this.title,
          category: this.category,
          content: this.content,
          isFavorite: this.isFavorite,
        });

        alert("Note créée avec succès !");
        // Redirect to the notes list
        this.$router.push("/");
      } catch (error) {
        console.error("Erreur lors de la création:", error);
        alert("Erreur lors de la création de la note");
      }
    },
  },
};
</script>

<template>
  <div class="flex justify-center items-center min-h-screen bg-[--yellow-light]">
    <div class="w-full max-w-xl bg-[--yellow] border border-[--dark-green] rounded-2xl">

      <!-- Title h1 -->
      <div class="bg-[--yellow]  border-b border-[--dark-green]">
        <h1 class="text-2xl font-bold text-center text-[--dark-green]">
          Ajouter une note
        </h1>
      </div>

      <!-- Form -->
        <form class="space-y-4 bg-white p-6 rounded-b-2xl">
          <!-- Title note -->
          <div class="flex items-center border-b border-teal-500 py-2">
            <input type="text" placeholder="TITRE DE LA NOTE *" aria-label="title_note"
              class="appearance-none bg-transparent border-none w-full placeholder-[#7A7A7A] mr-3 py-1 px-2 leading-tight focus:outline-none" />
          </div>
          <!-- Category -->
          <div class="relative flex items-center border-b border-teal-500 py-2">
            <select
              class="appearance-none bg-transparent border-none w-full text-[#7A7A7A] py-1 px-2 leading-tight focus:outline-none focus:bg-[#4ECDC4] focus:text-[#7A7A7A] transition-colors duration-200">
              <option disabled selected class="bg-[#4ECDC4] focus:text-[#7A7A7A]">Sélectionner une catégorie *</option>
              <option class="bg-[#4ECDC4] focus:text-[#7A7A7A]">Travail</option>
              <option class="bg-[#4ECDC4] focus:text-[#7A7A7A]">Personnel</option>
              <option class="bg-whitebg-[#4ECDC4] focus:text-[#7A7A7A]">Important</option>
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
            <div class="flex items-center border-b border-teal-500 py-2">
              <textarea placeholder="Contenu de la note *" aria-label="content_note" rows="1"
                class="resize-none appearance-none bg-transparent border-none w-full placeholder-[#7A7A7A] mr-3 py-1 px-2 leading-tight focus:outline-none"></textarea>
            </div>
            <small class="text-xs text-[--light-green] mt-1 ml-2">Appuyez sur Entrée pour ajouter une ligne</small>
          </div>
          <!-- Upload image -->
          <div class="flex items-center border-b border-teal-500 py-2 relative">
            <input type="file" id="image_upload" class="opacity-0 absolute inset-0 cursor-pointer" />
            <label for="image_upload" class="flex justify-between items-center w-full px-2 py-1 cursor-pointer">
              <span class="text-[#7A7A7A]">Choisir une image</span>
              <i class="far fa-image" style="color: #7A7A7A"></i>
            </label>
          </div>
          <!-- Favorite -->
          <div class="flex items-center py-2">
            <input type="checkbox" id="is_favorite" class="mr-2" />
            <label for="is_favorite" class=" text-[#7A7A7A]">Marquer comme favori</label>
          </div>

          <!-- Required fields note -->
          <div>
            <small>* Champs obligatoires</small>
          </div>

          <!-- Error message -->
          <div v-if="errorMessage" class="text-red-500 text-sm mt-2">
            <small class="text-red-500">Erreur : Veuillez remplir tous les champs requis.</small>
          </div>

          <!-- Buttons : back & submit -->
          <div class="flex justify-center gap-4 pt-4">
            <button type="button"
              class="bg-[--light-green] text-[--black] hover:bg-[--dark-green] hover:text-[--white] font-medium py-2 px-4 rounded-full">
              Retour
            </button>
            <button type="submit"
              class="btn-create-note rounded-full">
              Ajouter
            </button>
          </div>
      </form>
    </div>
  </div>
</template>
