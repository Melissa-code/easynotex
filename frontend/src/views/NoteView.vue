<script>
  import NoteComponent from '../components/note/NoteComponent.vue';
  import Swal from 'sweetalert2';
  import axios from 'axios';

  export default {
      name: 'NoteView',
      props: {
        notes: {
            type: Array,
            default: () => []
        }
      },
      data() {
        return {
          note : {},
          loading: true,  // start loading state
        };
      },
      components: {
        NoteComponent
      },
      methods: {
        async fetchNote() {
          try {
            const noteId = this.$route.params.id;
            console.log('ID de la note récupéré :', noteId);
            const url = `${import.meta.env.VITE_API_URL}/api/notes/${noteId}`;
            const response = await axios.get(url);
            this.note = response.data;
            console.log(this.note)
          } catch (error) {
              console.error('Erreur lors de la récupération de la note n° ', error);
          }
        },
      },
      mounted() {
          this.fetchNote();
      }
    }
</script>

<template>
  <div class="container-fluid bg-[--yellow-light]">
    <NoteComponent v-if="note" :note="note" />
  </div>
</template>