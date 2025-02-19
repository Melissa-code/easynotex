<script setup>
  import NavBarComponent from './components/NavBarComponent.vue';
  import NotesComponent from './components/NotesComponent.vue';
</script>

<template>
  <header>
    <NavBarComponent />
  </header>

  <main>
    <NotesComponent />
    
  </main>

  <footer>
    
  </footer>
</template>

<style scoped>
.container {
    background-color: lightgray;
    padding: 10px;
}

</style>
