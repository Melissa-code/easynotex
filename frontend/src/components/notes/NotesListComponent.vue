<script>
  import NoteCardComponent from './NoteCardComponent.vue';

  export default {
      name: 'NotesListComponent',
      props: {
          notes: Array
      },
      components: {
          NoteCardComponent
      }
  };
</script>

<template>
    <div class="mx-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 my-10">
        <NoteCardComponent v-for="note in notes" :key="note.id" :note="note" />
    </div>
</template>
