<script setup>
 import { onUpdated, reactive, ref, watch } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import { useForm, router } from '@inertiajs/vue3';
 import SpinProgress from '@/Components/SpinProgress.vue'
 import axios from 'axios';
 import { pickBy } from "lodash";
 import PaginationAxios from '@/Components/PaginationAxios.vue';

 const emit = defineEmits(["close"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      stages:Object
  });

  const close = () =>
  { 
     emit('close');
  };

  const showSpinner = ref(false);
  const cambioDeStatus = async (stage) => 
  {
      showSpinner.value = true;
      try 
      {
         await axios.post(route('cambioStage', {stage:stage})).then(response => 
         {
            console.log(response);
            showSpinner.value = false;
         })
         .catch(err => 
         {
            console.log(err)
            showSpinner.value = false;
         });
      } 
      catch (error) 
      {
        
      }
  }

//Nuevos stages indivuduales
//Formulario para nuevo proyecto
const formNewStage = reactive(
 {
   nombre:'',
   categoria_stage_id:-1
 }
);
const addNewStage = ref(false)
const addNewStageFunction = () => 
{
   addNewStage.value = true;
}

const stagesCambiantes = ref(props.stages);
onUpdated(() => 
{
  stagesCambiantes.value = props.stages;
})

const createNewStage = async () => 
{
  try 
  {
     await axios.post(route('createStage', {formulario:formNewStage}))
     .then(response => 
     {
       //console.log(response)
       addNewStage.value = false;
       stagesCambiantes.value = response.data
       formNewStage.categoria_stage_id = -1;
       formNewStage.nombre = '';
     }).catch(err => 
     {

     });
  } 
  catch (error) 
  {
    
  }
}

const reconsultar = async (page='') => 
{
  const  params = pickBy({page});
    await axios.get('/getStages', {params}).then((response)=> 
    {
      console.log(response);
      stagesCambiantes.value = response.data
    });
}

</script>
<template >
  <DialogModal :maxWidth="'xl'"  :show="show" @close="close()" >
       <template #content>
        <button class="bg-[#C3C4CE] float-right rounded-full py-2 px-2" @click="close()">
            <svg id="Grupo_394" data-name="Grupo 394" xmlns="http://www.w3.org/2000/svg" width="11.344" height="11.344" viewBox="0 0 11.344 11.344">
             <path id="Trazado_1910" data-name="Trazado 1910" d="M71.01,81.695a.584.584,0,0,1-.412-1L80.774,70.521a.584.584,0,0,1,.825.825L71.423,81.523A.585.585,0,0,1,71.01,81.695Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
             <path id="Trazado_1911" data-name="Trazado 1911" d="M81.187,81.695a.579.579,0,0,1-.412-.171L70.6,71.347a.584.584,0,0,1,.825-.825L81.6,80.7a.584.584,0,0,1-.412,1Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
           </svg>
          </button>
          <div class="" style="font-family: 'Montserrat';">
            <div class="flex flex-row items-center pb-2 align-middle">
                <h1 class="text-[#0A0F2C]  text-2xl mr-2 font-semibold">Stage</h1>
                <button  @click="addNewStageFunction()" class="bg-[#70BBF6] rounded-full flex flex-row justify-center items-center w-6 h-6">
                    <p class="font-semibold text-white">+</p>
                </button> 
            </div>
            <div v-if="addNewStage" class="">
                <div >
                    <div class="flex flex-row justify-around my-2">
                       <div class="flex flex-col">
                         <label class="py-1 font-semibold">Nombre de stage</label>
                         <input required class="w-full px-3 py-0 leading-tight text-gray-700 border rounded-full shadow appearance-none focus:outline-none focus:shadow-outline bg-[#F6F6F9]" v-model="formNewStage.nombre" />
                       </div> 
                       <div class="flex flex-col ">
                          <label class="py-1 font-semibold">Categoria de stage</label>
                          <select required v-model="formNewStage.categoria_stage_id" class="py-0 border-none rounded-full custom-select" :class="formNewStage.categoria_stage_id == 1 ? 'bg-[#344182] text-white' : 'bg-[#2684D0] text-white'">
                            <option value="1">
                             Inbound
                            </option>
                            <option value="2">
                                Outbound
                            </option>
                          </select>
                       </div> 
                    </div>
                    <div class="flex flex-row justify-center">
                        <button @click="createNewStage()" class="bg-[#2684D0] px-10 py-1 rounded-full">
                            <p class="text-white">Guardar</p>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t-2 border-[#C3C5CE] my-2" v-if="stages.length !== 0">
                <div class="flex flex-row justify-between px-4 py-2" v-for="stage in stagesCambiantes.data" :key="stage.id">
                   <div>
                     {{ stage.nombre }}
                   </div>
                   <div class="flex flex-row">
                      <select @change="cambioDeStatus(stage)" class="py-0 border-none rounded-full custom-select"  :class="stage.categoria_stage_id == 1 ? 'bg-[#344182] text-white' : 'bg-[#2684D0] text-white'"  v-model="stage.categoria_stage_id" >
                         <option value="1">
                             Inbound
                         </option>
                         <option value="2">
                             Outbound
                         </option>
                      </select>
                      <SpinProgress :inprogress="showSpinner" class="w-4 h-4 ml-2" v-if="showSpinner" />
                   </div>
                </div>
            </div>
          </div>
          <PaginationAxios :pagination="stagesCambiantes" @load-page="reconsultar($event)" />
       </template>
  </DialogModal>
</template>
<style>
.custom-select  :after {
  position: absolute;
  content: "";
  top: 14px;
  right: 10px;
  width: 0;
  height: 0;
  border: 6px solid transparent;
  border-color: #fff transparent transparent transparent;
}
</style>