<script>
  export default {
    name: 'SpinnerComponent',
    props: {
      size: {
        type: String,
        default: '80px'
      },
      borderWidth: {
        type: String,
        default: '8px'
      },
      fullHeight: {
        type: Boolean,
        default: true
      },
      color: {
        type: String,
        default: '#FFE66D'
      },
      backgroundColor: {
        type: String,
        default: '#1A535C'
      }
    }
  };
</script>

<template>
  <div class="spinner">
    <div class="loader"
      :style="{ 
        width: size, 
        height: size, 
        borderWidth: borderWidth,
        borderTopWidth: borderWidth,
        borderColor: backgroundColor,
        borderTopColor: color
      }"
    ></div>
  </div>
</template>

<style scoped>
 .spinner {
  display: flex;
  justify-content: center;
  align-items: center;
}

.spinner.full-height {
  height: 33vh;
}

.loader {
  border: 8px solid #1A535C; 
  border-top: 8px solid #FFE66D;
  border-radius: 50%;
  animation: spin 1.5s linear infinite; 
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
