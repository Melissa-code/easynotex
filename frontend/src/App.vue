<script setup>
  import NavBarComponent from './components/layout/NavBarComponent.vue';
  import NotesComponent from './components/notes/NotesComponent.vue';
  import FooterComponent from './components/layout/FooterComponent.vue';
  
  import SearchBarComponent from './components/notes/SearchBarComponent.vue';
  import CreateNoteBannerComponent from './components/notes/CreateNoteBannerComponent.vue';
</script>

<template>
  <header>
    <NavBarComponent />
  </header>

  <main>
    <SearchBarComponent />
    <CreateNoteBannerComponent />
    <NotesComponent />
  </main>

  <footer>
    <FooterComponent />
  </footer>
</template>

<style scoped>
.container {
    background-color: lightgray;
    padding: 10px;
}

</style>
