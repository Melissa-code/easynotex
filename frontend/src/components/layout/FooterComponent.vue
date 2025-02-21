
<template>
  <div>
    <!-- Footer -->
    <section class="light border-t border-t-green-800 text-black">
        <div class="w-full max-w-screen-xl mx-auto px-2 sm:px-6 lg:px-8 md:py-1">
            <div class="sm:flex sm:items-center sm:justify-between pt-2">
                <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="../../assets/logo.svg" class="h-8" alt="EasyNotex logo">
                    <span class="self-center text-2xl font-bold whitespace-nowrap text-black">
                      EasyNotex
                    </span>
                </a>
                <ul class="flex flex-wrap items-center text-sm">
                  <li>
                    <a href="#" class="me-4 md:me-6" target="_blank">Mentions légales</a>
                  </li>
                  <li>
                      <a href="#" class="me-4 md:me-6" target="_blank">Politique de confidentialité</a>
                  </li>
                  <li>
                      <a href="#" class="me-4 md:me-6" target="_blank">Gestion des cookies</a>
                  </li>
                  <li>
                      <a href="#" class="me-4 md:me-6" target="_blank">CGU</a>
                  </li>
                </ul>
            </div>
            <span class="block text-sm sm:text-center mt-1 md:mt-0">
              © {{ currentYear }} 
              <a href="#" class="brand">
                EasyNotex
              </a>
              - tous droits réservés 
            </span>
      </div>
    </section>
  </div>
</template>


<script>
    export default {
        data() {
            return {
                currentYear: new Date().getFullYear(),
            };
        },
    };
</script>

<style scoped>
    
</style>