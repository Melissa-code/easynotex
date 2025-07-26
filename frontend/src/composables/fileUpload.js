import { ref } from 'vue';

export function useFileUpload(validateImage) {
  const selectedFile = ref(null);

  const handleFileUpload = (event) => {
    const file = event.target.files[0];

    if (file) {
      if (validateImage(file)) {
        selectedFile.value = file;
      } else {
        // Reset input file if validation fails
        console.error('Le fichier sélectionné n\'est pas valide.');
        event.target.value = '';
        selectedFile.value = null;
      }
    }
  };

  const resetFileInput = (inputId = '#image_upload') => {
    const fileInput = document.querySelector(inputId);
    if (fileInput) {
      fileInput.value = '';
      selectedFile.value = null;
    }
  };

  return {
    selectedFile,
    handleFileUpload,
    resetFileInput
  };
}