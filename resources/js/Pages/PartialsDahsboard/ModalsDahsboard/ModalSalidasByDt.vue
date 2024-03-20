<script setup>
 import { ref, watch } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import { useForm, router } from '@inertiajs/vue3'

 const emit = defineEmits(["close"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      salidas:Object
  });

  const close = () =>
  { 
     emit('close');
  };


</script>
<template>
  <DialogModal :maxWidth="'4xl'"  :show="show" @close="close()">
       <template #title>
          <button class="bg-[#C3C4CE] float-right rounded-full py-2 px-2" @click="close()">
            <svg id="Grupo_394" data-name="Grupo 394" xmlns="http://www.w3.org/2000/svg" width="11.344" height="11.344" viewBox="0 0 11.344 11.344">
             <path id="Trazado_1910" data-name="Trazado 1910" d="M71.01,81.695a.584.584,0,0,1-.412-1L80.774,70.521a.584.584,0,0,1,.825.825L71.423,81.523A.585.585,0,0,1,71.01,81.695Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
             <path id="Trazado_1911" data-name="Trazado 1911" d="M81.187,81.695a.579.579,0,0,1-.412-.171L70.6,71.347a.584.584,0,0,1,.825-.825L81.6,80.7a.584.584,0,0,1-.412,1Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
           </svg>
          </button>
          <div class="">
            <h1 class="text-[#0A0F2C]  text-2xl">Salidas por DT's</h1>
          </div>
       </template>
       <template #content>
          <table class="w-full" style="font-family: 'Montserrat';">
            <thead>
                <tr class="border-b">
                  <th>DT</th>
                  <th>Cantidad</th>
                  <th>Fecha</th>
                  <th>Cliente</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="salida in salidas" :key="salida.id">
                  <td class="py-1 text-center">
                    {{ salida.dt }}
                  </td>
                  <td class="text-center">
                    {{salida.cantidad}}
                  </td>
                  <td class="text-center">
                    {{ salida.new_date }}
                  </td>
                  <td class="text-center">
                    {{salida.cliente}}
                  </td>
                </tr>
            </tbody>
          </table>
       </template>
  </DialogModal>
</template>