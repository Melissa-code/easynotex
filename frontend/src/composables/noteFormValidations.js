import { computed, ref } from 'vue';

export function useNoteFormValidations() {
  const errorMessage = ref({});
  const validCharsRegex = /^[\p{L}\p{N}\s\-_.,!?'"():;]+$/u; //Regex: accents français et lettres Unicode

  // Validation en temps réel computed
  const isFormValid = (formData) => computed(() => {
    const title = formData.value.title.trim();
    const content = formData.value.content.trim();
    const category = formData.value.category;

    return title.length >= 2 && 
      title.length <= 100 &&
      validCharsRegex.test(title) &&
      category !== '' && 
      content.length >= 3 &&
      content.length <= 5000 &&
      validCharsRegex.test(content);
  });

  // Validations with messages
  const validateForm = (formData) => {
    const title = formData.title.trim();
    const content = formData.content.trim();
    const category = formData.category;

    errorMessage.value = '';

    // Validation characters
    if (!validCharsRegex.test(title)) {
      errorMessage.value = 'Le titre contient des caractères non autorisés.';
      return false;
    }
    
    if (!validCharsRegex.test(content)) {
      errorMessage.value = 'Le contenu contient des caractères non autorisés.';
      return false;
    }

    // Validation length
    if (title.length < 2 || title.length > 100) {
      errorMessage.value = 'Le titre doit faire au moins 2 caractères et moins de 100 caractères.';
      return false;
    }

    if (content.length < 3 || content.length > 5000) {
      errorMessage.value = 'Le contenu doit faire au moins 3 caractères et moins de 5000 caractères.';
      return false;
    }

    //required fields
    if (!title || !category || !content) {
      errorMessage.value = 'Veuillez remplir les 3 champs obligatoires.';
      return false;
    }

    return true;
  };

  // Validation image
  const validateImage = (file) => {
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    
    if (!allowedTypes.includes(file.type)) {
      errorMessage.value = 'Format d\'image non supporté. Utilisez JPG, PNG, GIF ou WebP.';
      return false;
    }
    
    errorMessage.value = '';
    return true;
  };

  return {
    errorMessage,
    isFormValid,
    validateForm,
    validateImage
  };
}