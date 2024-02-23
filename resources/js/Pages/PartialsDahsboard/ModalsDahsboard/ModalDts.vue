<script setup>
 import { reactive, ref, watch, onUpdated  } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import { useForm, router } from '@inertiajs/vue3'
 import { pickBy } from "lodash";
 import PaginationAxios from '@/Components/PaginationAxios.vue';

 const emit = defineEmits(["close"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      dts:Object
  });

  const close = () =>
  { 
     emit('close');
  };

  const  params = reactive({
    busqueda:''
  });


  const dtsCambiantes = ref(props.dts)

  onUpdated(() => 
  {
      dtsCambiantes.value = props.dts;
  })

  watch(params, () => 
  {
    const clearParams = pickBy({ ...params });
    console.log(clearParams)
    try 
    {
      //comsultamos para setear los dts
     axios.get(route('getDts', {params:clearParams}))
      .then(response => 
      {
         console.log(response.data)
         dtsCambiantes.value = response.data
      }).catch(err => 
      {
        console.log(err)
      })
    } 
    catch (error) 
    {
        
    }
  });

  const reconsultar = async (page='') => 
  {
    const  params = pickBy({page});
    await axios.get('/getDts', {params}).then((response)=> 
    {
      //console.log(response);
      dtsCambiantes.value = response.data
    });
  }

</script>
<template>
  <DialogModal :maxWidth="'3xl'"  :show="show" @close="close()">
       <template #content>
          <button class="bg-[#C3C4CE] float-right rounded-full py-2 px-2" @click="close()">
            <svg id="Grupo_394" data-name="Grupo 394" xmlns="http://www.w3.org/2000/svg" width="11.344" height="11.344" viewBox="0 0 11.344 11.344">
             <path id="Trazado_1910" data-name="Trazado 1910" d="M71.01,81.695a.584.584,0,0,1-.412-1L80.774,70.521a.584.584,0,0,1,.825.825L71.423,81.523A.585.585,0,0,1,71.01,81.695Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
             <path id="Trazado_1911" data-name="Trazado 1911" d="M81.187,81.695a.579.579,0,0,1-.412-.171L70.6,71.347a.584.584,0,0,1,.825-.825L81.6,80.7a.584.584,0,0,1-.412,1Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
           </svg>
          </button>
          <div class="" style="font-family: 'Montserrat';">
            <div class="flex flex-row items-center pb-2 align-middle">
                <h1 class="text-[#0A0F2C]  text-2xl mr-2 font-semibold">Dt's</h1>
                <div class="ml-8">
                    <input v-model="params.busqueda" class="w-full px-3 py-2 leading-tight text-[#C3C4CE] border-[#C3C4CE] rounded-full appearance-none  focus:text-[#0A0F2C] focus:border-[#C3C4CE]"  placeholder="BUSCAR"/>
                </div>
            </div>
            <div>
               <table class="w-full">
                  <thead class="py-2 border-b">
                    <tr>
                        <td class="py-2 font-semibold text-center">Dt's</td>
                        <td class="py-2 font-semibold text-center">Stage</td>
                        <td class="py-2 font-semibold text-center">Cliente</td>
                        <td class="py-2 font-semibold text-center">Destino</td>
                        <td class="py-2 font-semibold text-center">Clase</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="dt in dtsCambiantes.data" :key="dt.id">
                        <td class="text-center">
                            {{ dt.referencia }}
                        </td>
                        <td  class="text-center">
                            {{ dt.stage }}
                        </td>
                        <td  class="text-center">
                            {{ dt.cliente }}
                        </td>
                        <td  class="text-center">
                            {{ dt.destino }}
                        </td>
                        <td class="flex items-center justify-center py-1">
                            <div v-if="dt">
                                <button class="bg-[#344182] rounded-full px-4 py-1 text-white flex flex-row justify-between items-center" v-if="dt.categoria_stage == 1">
                                   <span class="bg-[#70BBF6] block w-2 h-2 mr-2 rounded-full"></span>
                                   <p>Inbound</p>
                                </button>
                                <button class="bg-[#2684D0] rounded-full px-4 py-1 text-white flex flex-row justify-between items-center" v-else>
                                    <span class="bg-[#70BBF6] block w-2 h-2 mr-2 rounded-full"></span>
                                    Outbound
                                </button>
                            </div>
                        </td>
                    </tr>
                  </tbody>
               </table>
            </div>
            <PaginationAxios :pagination="dtsCambiantes" @loadPage="reconsultar($event)" />
          </div>
       </template>
  </DialogModal>
</template>