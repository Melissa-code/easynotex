<script>
import axios from 'axios';

export default {
  name: 'CreateNoteView',
  data() {
    return {
      title: '',
      category: '',
      content: '',
      isFavorite: false
    };
  },
  methods: {
    async handleSubmit(event) {
      event.preventDefault();
      
      // Validation simple
      if (!this.title || !this.category || !this.content) {
        alert('Veuillez remplir tous les champs requis');
        return;
      }

      try {
        // Exemple d'appel API
        const response = await axios.post('/api/notes', {
          title: this.title,
          category: this.category,
          content: this.content,
          isFavorite: this.isFavorite
        });
        
        alert('Note créée avec succès !');
        // Rediriger vers la liste des notes
        this.$router.push('/');
      } catch (error) {
        console.error('Erreur lors de la création:', error);
        alert('Erreur lors de la création de la note');
      }
    }
  }
};
</script>

<template>
  <div class="container-fluid bg-yellow-200 p-4">
    <h1 class="text-2xl font-bold">Créer une nouvelle note</h1>
    
    <form @submit="handleSubmit" class="mt-4">
      <sl-input 
        name="title" 
        label="Titre" 
        v-model="title"
        required
      ></sl-input>
      <br />
      
      <sl-select 
        label="Catégorie" 
        v-model="category"
        clearable 
        required
      >
        <sl-option value="personnel">Personnel</sl-option>
        <sl-option value="travail">Travail</sl-option>
        <sl-option value="idees">Idées</sl-option>
        <sl-option value="important">Important</sl-option>
      </sl-select>
      <br />
      
      <sl-textarea 
        name="content" 
        label="Contenu" 
        v-model="content"
        required
      ></sl-textarea>
      <br />
      
      <sl-checkbox v-model="isFavorite">
        Note favorite
      </sl-checkbox>
      <br /><br />
      
      <sl-button type="submit" variant="primary">
        Créer
      </sl-button>
    </form>
  </div>
</template>