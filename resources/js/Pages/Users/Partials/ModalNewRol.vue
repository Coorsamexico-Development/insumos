<script setup>
 import { reactive, ref, watch, onUpdated  } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import InputLabel from '@/Components/InputLabel.vue';
 import TextInput from '@/Components/TextInput.vue';
 import { useForm } from '@inertiajs/vue3'

 const emit = defineEmits(["close"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      }
  });

  const close = () =>
  { 
     emit('close');
  };

  //Formulario
   const formNewRol = useForm({
       nombre: '',
    });

  const saveNewRol = () => 
   {
      formNewRol.post(route('saveRole'),{
        onSuccess : () => 
            {
               formNewRol.reset();
               close();
            },
        preserveScroll:true,
        preserveState:true
      });
   }
</script>
<template>
    <DialogModal :maxWidth="'xl'"  :show="show" @close="close()">
      <template #title>
         <div class="flex flex-row justify-between">
            <h1 class="text-2xl">Nuevo rol</h1>
            <button class="bg-[#C3C4CE] float-right rounded-full py-2 px-2" @click="close()">
               <svg id="Grupo_394" data-name="Grupo 394" xmlns="http://www.w3.org/2000/svg" width="11.344" height="11.344" viewBox="0 0 11.344 11.344">
                <path id="Trazado_1910" data-name="Trazado 1910" d="M71.01,81.695a.584.584,0,0,1-.412-1L80.774,70.521a.584.584,0,0,1,.825.825L71.423,81.523A.585.585,0,0,1,71.01,81.695Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
                <path id="Trazado_1911" data-name="Trazado 1911" d="M81.187,81.695a.579.579,0,0,1-.412-.171L70.6,71.347a.584.584,0,0,1,.825-.825L81.6,80.7a.584.584,0,0,1-.412,1Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
              </svg>
           </button>
         </div>
       </template>
       <template #content>
         <InputLabel value="Nombre del rol:" class="my-2" />
         <TextInput v-model="formNewRol.nombre" class="w-full" />
         <button @click="saveNewRol" class="w-full my-2 bg-[#697FEA] text-white text-lg rounded-full">
             Guardar nuevo rol
         </button>
       </template>
    </DialogModal>
</template>