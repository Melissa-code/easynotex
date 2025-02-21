<script setup>
  import NavBarComponent from './components/layout/NavBarComponent.vue';
  import NotesComponent from './components/notes/NotesComponent.vue';
  import FooterComponent from './components/layout/FooterComponent.vue';
  
  import SearchBarComponent from './components/notes/SearchBarComponent.vue';
  import CreateNoteBannerComponent from './components/notes/CreateNoteBannerComponent.vue';
  import PaginationComponent from './components/shared/PaginationComponent.vue';
</script>

<template>
  <div>
    <header>
      <NavBarComponent />
    </header>

    <main>
      <SearchBarComponent />
      <CreateNoteBannerComponent />
      <NotesComponent />
      <PaginationComponent />
    </main>

    <footer>
      <FooterComponent />
    </footer>
  </div>
</template>

<style scoped>
.container {
    background-color: lightgray;
    padding: 10px;
}

</style>
