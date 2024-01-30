<script setup>
import Datepicker from 'vuejs3-datepicker';
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3'
// Main JS (in CommonJS format)
import VueTimepicker from 'vue3-timepicker'
// CS
import 'vue3-timepicker/dist/VueTimepicker.css'
import axios from 'axios';

var props = defineProps({
    categorias:Object,
});

const categoria_actual = ref(1);
//Formulario para nuevo proyecto
const formNewSalida = useForm({
    codigo:'',
    categoria:categoria_actual.value,
    cantidad: 0,
    dt:'',
    stage:'',
    cliente:'',
    clase:'',
    destino:''
  });

const message = ref('');  
watch(formNewSalida, (newForm) => 
{
  if(newForm.dt !== '') //si el dt cambia buscaremos y setearemos los datos
  {
    console.log(newForm.dt )
    axios.get(route('consultaInformacion',{dt:newForm.dt}))
    .then(resonse => 
    {
      console.log(resonse)
      formNewSalida.stage = resonse.data.stage;
      formNewSalida.cliente = resonse.data.cliente;
      formNewSalida.clase = resonse.data.categoria_stage;
      formNewSalida.destino = resonse.data.destino;
    })
    .catch(err => 
    {
      console.log(err.response)
    })
  }
  else
  {
      formNewSalida.stage = '';
      formNewSalida.cliente = '';
      formNewSalida.clase = '';
      formNewSalida.destino = '';
  }
});

const cambiarCategoria = (categoria) => 
{
    categoria_actual.value = categoria.id
    formNewSalida.categoria = categoria.id
}

const guardarNuevaSalida =  () => 
{
   if(formNewSalida.codigo !== '' && formNewSalida.categoria !== -1 && formNewSalida.cantidad !== 0 && formNewSalida.dt !== '' && formNewSalida.stage !== '' && formNewSalida.cliente !== '' && formNewSalida.clase !== '' && formNewSalida.destino !== '' ) 
   {
     try 
     {
       formNewSalida.post(route('saveSalida'),
       {
        onSuccess : () => 
            {
               formNewSalida.reset();
               close();
            },
         preserveScroll:true,
         preserveState:true
       }
       );
     } 
     catch (error) 
     {
      
     }
   }
   else
   {
     alert('Todos los campos son requeridos')
   }
}

const listDts = ref([]);
const consultarDts = () => 
{
   axios.get(route('verDts')).then(response => 
   {
     listDts.value = response.data
   })
   .catch(err => 
   {

   });
}

</script>
<template>
    <div class="p-4 bg-white border rounded-lg shadow-lg">
        <div class="flex flex-row justify-between"> <!--header-->
            <h1 class="font-semibold uppercase">Salida</h1>
            <div>
               
               <button @click="cambiarCategoria(categoria)" :class="[ categoria_actual == categoria.id ? 'px-4 mx-2 border rounded-xl bg-[#344182]' : 'px-4 mx-2 border rounded-xl']" v-for="categoria in categorias" :key="categoria.id">
                    <p :class="[categoria_actual == categoria.id ? 'text-white' : 'text-[#C3C4CE] hover:text-[#344182]']">{{categoria.nombre}}</p>
                </button>
               
           </div>
        </div>
        <form class="grid grid-cols-2 my-4">
           <div class="flex flex-col mr-4">
              <label class="py-2" htmlFor="codigo">Productos</label>
              <input id="codigo" v-model="formNewSalida.codigo" type="text" placeholder="Escanee producto" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
           </div>
           <div class="flex flex-col" >
              <label class="py-2" htmlFor="cantidad">Cantidad</label>
              <input id="cantidad" v-model="formNewSalida.cantidad" type="number" placeholder="Cantidad" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
           </div>
           <div class="flex flex-col mr-4" v-if="categoria_actual == 1">
              <label class="py-2" htmlFor="dt" >DT</label>
              <input @focus="consultarDts()" id="dt" list="lisDts"  v-model="formNewSalida.dt" type="text" placeholder="DT" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]">
              <datalist id="lisDts">
                <option v-for="dt in listDts" :key="dt.id" :value="dt.referencia" >
                    {{ dt.referencia }}
                </option>
              </datalist>
           </div>
           <div class="flex flex-col" v-if="categoria_actual == 1">
              <label class="py-2" htmlFor="stage">Stage</label>
              <input v-model="formNewSalida.stage" disabled id="stage" placeholder="Stage" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
           </div>
           <div class="flex flex-col mr-4" v-if="categoria_actual == 1">
              <label class="py-2" htmlFor="cliente">Cliente</label>
              <input v-model="formNewSalida.cliente" disabled id="cliente" type="text" placeholder="Cliente" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
           </div>
           <div class="flex flex-col" v-if="categoria_actual == 1">
              <label class="py-2" htmlFor="clase">Clase</label>
              <input v-model="formNewSalida.clase" disabled id="clase"  placeholder="Clase" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
           </div>
           <div class="flex flex-col w-full col-start-1 col-end-3" v-if="categoria_actual == 1">
              <label class="py-2" htmlFor="destino">Destino</label>
              <input v-model="formNewSalida.destino" disabled id="destino" type="text" placeholder="Destino" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
           </div>
         </form>
         <div class="flex flex-row justify-center col-start-1 col-end-3 mt-2">
               <button @click="guardarNuevaSalida()" class="bg-[#2684D0] px-10 py-1 rounded-full">
                   <p class="text-white">Guardar</p>
               </button>
           </div>
    </div>
</template>