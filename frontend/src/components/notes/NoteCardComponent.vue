<template>
  <div class="bg-white rounded-2xl border border-[--dark-green] overflow-hidden">
      <!-- Title -->
      <div class="p-4 border-b border-b-[--dark-green] bg-[--yellow-light] text-center">
          <h3 class="text-[--dark-green]">{{ truncatedTitle }}</h3>
      </div>
      <!-- Infos -->
      <div class="p-4">
          <ul>
              <li class="text-sm mb-4">{{ displayedDate }}</li>
              <li class="mb-4 font-semibold flex justify-between">
                  <span>{{ note.category_name }}</span>
                  <span v-if="note.isFavorite === 1" class="favorite-icon" v-html="displayFavorite()"></span>
              </li>
              <li class="text-justify">{{ truncatedContent }}</li>
          </ul>
      </div>
  </div>
</template>

<script>
  export default {
    name: 'NoteCardComponent',
    props: {
        note: Object, 
    },
    computed: {
        truncatedTitle() {
            return this.truncate(this.note.title.toUpperCase(), 50);
        },
        truncatedContent() {
            return this.truncate(this.note.content, 150);
        },
        displayedDate() {
            return new Date(this.note.updated_at) > new Date(this.note.created_at)
                ? `Modifiée le ${this.formatDate(this.note.updated_at)}`
                : `Créée le ${this.formatDate(this.note.created_at)}`;
        }
    },
    methods: {
        formatDate(dateString) {
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('fr-FR', options);
        },
        truncate(text, maxlength) {
            return text.length > maxlength ? text.slice(0, maxlength -1) + '…' : text;
        },
        displayFavorite() {
            return `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="size-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                </svg>
            `;
        }
    }
  };
</script>

<style scoped>
  .favorite-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: var(--dark-green);
  }
</style>
