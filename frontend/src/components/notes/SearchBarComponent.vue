<template>
  <div>

    <section class="h-20 flex items-center bg-[--yellow-light]">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center px-4">
          <div></div> 
          <div class="col-span-2 relative">
            <!-- input-->    
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke-width="1.5" 
              stroke="currentColor" 
              class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>     
            <input 
              type="text" 
              placeholder="Rechercher une note" 
              class=" border border-gray-300 text-gray-500 w-full p-2 pl-10 border rounded-full focus:ring-1 focus:ring-green-900 focus:outline-none" 
            />
          </div>
          <div></div> 
        </div>
      </div>
    </section>

  </div>
</template>


<script>
    export default {
        data() {
            return {
                //
            };
        },
    };
</script>


<style>
  
</style>