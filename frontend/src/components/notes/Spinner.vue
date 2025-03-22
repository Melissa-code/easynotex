<script>
  export default {
    name: 'Spinner',
  };
</script>

<template>
  <div class="spinner">
    <div class="loader"></div>
  </div>
</template>

<style scoped>
 .spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 33vh;
}

.loader {
  border: 8px solid #1A535C; 
  border-top: 8px solid #FFE66D;
  border-radius: 50%;
  width: 80px;  
  height: 80px; 
  animation: spin 1.5s linear infinite; 
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
