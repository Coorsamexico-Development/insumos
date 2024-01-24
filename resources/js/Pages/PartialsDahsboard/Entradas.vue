<script setup>
import Datepicker from 'vuejs3-datepicker';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3'
// Main JS (in CommonJS format)
import VueTimepicker from 'vue3-timepicker'
// CS
import 'vue3-timepicker/dist/VueTimepicker.css'
//


var props = defineProps({
    categorias:Object,
});

const categoria_actual = ref(1);
let hoy = new Date();
let year = hoy.getFullYear();
let month = hoy.getMonth();
let day = hoy.getDate()
let hour = hoy.getHours();
let minutes = hoy.getMinutes();
let seconds = hoy.getSeconds();

if(month < 10)
{
  month = month + 1;
  month = '0'+month;
}

if(day < 10)
{
  day = '0' + day;
}

if(hour < 10)
{
    hour = '0' + hour;
}

if(minutes < 10)
{
  minutes = '0'+minutes;
}

console.log(hour+':'+minutes)

//Formulario para nuevo proyecto
const formNewEntrada = useForm({
    codigo:'',
    categoria:categoria_actual.value,
    cantidad: 0,
    fecha: year+'-'+month+'-'+day,
    hora: hour+':'+minutes,
    factura:''
  });

const cambiarCategoria = (categoria) => 
{
    categoria_actual.value = categoria.id
    formNewEntrada.categoria = categoria.id
}

const guardarNuevaEntrada = async () => 
{
   if(formNewEntrada.codigo !== '' && formNewEntrada.categoria !== 0 && formNewEntrada.cantidad !== 0 && formNewEntrada.fecha !== '' && formNewEntrada.hora !== '' )
   {
    formNewEntrada.fecha = formNewEntrada.fecha+' '+formNewEntrada.hora;
    formNewEntrada.post(route('saveEntrada'),
       {
        onSuccess : () => 
            {
               formNewEntrada.reset();
               close();
            },
        preserveScroll:true,
        preserveState:true
      });
   }
   else{
    alert('Todos los campos son requeridos')
   }
}

</script>
<template>
    <div class="p-3 bg-white border rounded-lg shadow-lg">
        <div class="flex flex-row justify-between"> <!--header-->
            <h1 class="font-semibold uppercase">Entrada</h1>
            <div>
                <button @click="cambiarCategoria(categoria)" :class="[ categoria_actual == categoria.id ? 'px-4 mx-2 border rounded-xl bg-[#344182]' : 'px-4 mx-2 border rounded-xl']" v-for="categoria in categorias" :key="categoria.id">
                    <p :class="[categoria_actual == categoria.id ? 'text-white' : 'text-[#C3C4CE] hover:text-[#344182]']">{{categoria.nombre}}</p>
                </button>
           </div>
        </div>
        <div>
            <form  class="grid grid-cols-2 grid-rows-2 my-4">
                <div class="flex flex-col mr-4">
                  <label class="py-2" htmlFor="codigo">Productos</label>
                  <input required v-model="formNewEntrada.codigo" id="codigo" type="text" placeholder="Escanee producto" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
                </div>
                <div class="flex flex-col">
                    <label class="py-2" htmlFor="cantidad">Cantidad</label>
                    <input required v-model="formNewEntrada.cantidad" id="cantidad" type="text" placeholder="Cantidad" class="w-full px-3 py-2 leading-tight text-gray-700 border border-none rounded shadow appearance-none focus:outline-none focus:shadow-outline bg-[#F6F6F9]" />
                </div>
                <div class="flex flex-col w-56 mt-2">
                    <label class="pb-2" htmlFor="fecha">Fecha</label>
                    <input required v-model="formNewEntrada.fecha" disabled type="date" class="px-3 py-2 leading-tight text-gray-700 border border-none rounded shadow appearance-none focus:outline-none focus:shadow-outline bg-[#F6F6F9]"/>
                </div>
                <div class="flex flex-col mt-2">
                    <label class="pb-2" htmlFor="hora">Hora</label>
                    <VueTimepicker :disabled="true" v-model="formNewEntrada.hora"  format="HH:mm"  class="timepicker"   input-width="15em" :placeholder="'Selecciona una hora'" />
                </div>
                <div class="flex flex-col mr-4">
                  <label class="py-2" htmlFor="factura">Factura</label>
                  <input required v-model="formNewEntrada.factura" id="factura" type="text" placeholder="Factura" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
                </div>
            </form>
            <div class="flex flex-row justify-center col-start-1 col-end-3 mt-2">
                 <button @click="guardarNuevaEntrada()" class="bg-[#2684D0] px-10 py-1 rounded-full">
                     <p class="text-white">Guardar</p>
                 </button>
             </div>
        </div>
    </div>
</template>
<style >
.timepicker  input[type="text"] {
        height: 30.8px;
        width: 201.48px;
        background-color: #F6F6F9;
        border: 1px;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        height:2.5rem;
        border-radius: 0.5rem;
        margin-left: -6px;
    }

.inputDate {
         width: 100%;
         cursor: default;
         background-color: red;
      }
</style>