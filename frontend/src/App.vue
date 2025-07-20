<script setup>
  import NavBarComponent from './components/layout/NavBarComponent.vue';
  import FooterComponent from './components/layout/FooterComponent.vue';
</script>

<template>
  <div class="">
    <!-- navigation -->
    <header class="">
      <NavBarComponent />
    </header>

    <main class="">
      <RouterView />
    </main>

    <footer class="">
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
