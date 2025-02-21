<script>
export default {
  data() {
    return {
      //
    };
  },
};
</script>

<template>
  <div class="flex justify-center ">
    <nav class="flex justify-between items-center mb-4">
      <a href="#" class="flex items-center px-3 text-sm text-gray-500">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
          class="size-4 me-3">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Précédent
      </a>
        <div>
          <a href="#" class="px-3 pb-1 text-sm text-white bg-[--dark-green] rounded-md hover:bg-gray-300">1</a>
          <a href="#" class="px-3 py-1 text-sm text-gray-500">2</a>
          <a href="#" class="px-3 py-1 text-sm text-gray-500">3</a>
        </div>
      <a href="#" class="flex items-center px-3 py-1 text-sm text-gray-500">
        Suivant
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="size-4 ms-3">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
        </svg>
      </a>
    </nav>
  </div>
</template>

<style scoped></style>