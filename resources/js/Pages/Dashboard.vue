<script setup>
import { ref, watch, computed, reactive } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import Entradas from './PartialsDahsboard/Entradas.vue';
import Salidas from './PartialsDahsboard/Salidas.vue'
import ButtonUploadBolo from '@/Components/ButtonUploadBolo.vue'
import SpinProgress from '@/Components/SpinProgress.vue'
//modales
import ModalNewProduct from './PartialsDahsboard/ModalsDahsboard/ModalNewProduct.vue';
import ModalStages from './PartialsDahsboard/ModalsDahsboard/ModalStages.vue';
import ModalDts from './PartialsDahsboard/ModalsDahsboard/ModalDts.vue';
import ModalMovimientosByProducto from './PartialsDahsboard/ModalsDahsboard/ModalMovimientosByProducto.vue';
import ModalWatchProducInfo from './PartialsDahsboard/ModalsDahsboard/ModalWatchProducInfo.vue';
import ModalGraph from './PartialsDahsboard/ModalsDahsboard/ModalGraph.vue'
import { pickBy } from "lodash";
//codigo
import VueBarcode from '@chenfengyuan/vue-barcode';
import axios from 'axios';

var props = defineProps({
    categorias:Object,
    productos:Object,
    filters: Object,
});



//Funciones para agregar productos modal
const modalNewProduct = ref(false);
const openModalNewProduct = () => 
{
    modalNewProduct.value = true;
}
const closeModalNewProduct = () => 
{
    modalNewProduct.value = false;
}
// fin /Funciones para agregar productos modal

//Funciones para los filtros por categoria de productos
const params = reactive
({
   categoria:1,
   inventario_actual:1   
});


let categoriaDownload = ref({categoria:1})
const categoria_actual = ref(1);
const cambiarCategoria = (categoria) => 
{
    categoria_actual.value = categoria.id; //se setea la categoria actual dependiendo el click
    categoriaDownload.value.categoria = categoria.id;
    params.categoria = categoria.id
}

watch(params, () => 
{
  const clearParams = pickBy({ ...params });

  //consultamos nuevamente la pag para filtirar
  router.visit(route('dashboard'), 
   {
     preserveScroll:true,
     preserveState:true,
     replace:true,
     data:clearParams,
     only:['productos'],
   })
});


//Funciones para stages
const stageModal = ref(false)
const stages = ref([]);
const openModalStages = async () => 
{
  try
  {
    consultarStages()
  }
  catch(err) 
  {
    
  }

}

const consultarStages = () => 
{
     axios.get(route('getStages'))
    .then(response => 
    {
       console.log(response.data)
       stages.value = response.data;
       stageModal.value = true;
    })
    .catch(err=> 
    {

    });
} 

const closeModalStages = () => 
{
    stageModal.value = false;
    stages.value = [];
}
//end funciones stages

//Importacion de bolo
//Form
const formNewThings = useForm({
  bolo: null,
  hola:'text'
})
const bolo = ref(null);
const showSpinner = ref(false);

watch(bolo, (boloCargado) => 
{
    formNewThings.bolo = boloCargado
    showSpinner.value = true;
   try 
   {
      if(formNewThings.bolo !== null)
      {
         //console.log(formNewThings)
         formNewThings.post(route('importBolo'),
         {
            onSuccess: () => 
            {
               formNewThings.reset();
               bolo.value = null;
               showSpinner.value = false;
            },
            onError:(err) => 
            {
              console.log(err);
              formNewThings.reset();
              showSpinner.value = false;
              bolo.value = null;
            },
            onFinish: () => 
            {
                formNewThings.reset();
                bolo.value = null;
                showSpinner.value = false; 
            }
         }
         ) 
      }
   } 
   catch (error) 
   {
     console.log(error)  
     showSpinner.value = false
   }
});

//Funciones para modal de los dts
const modalDts = ref(false);
const dts = ref([]);
const openModalDts = async () => 
{
    try 
    {
      //comsultamos para setear los dts
      await axios.get(route('getDts'))
      .then(response => 
      {
         console.log(response.data)
         dts.value = response.data;
         modalDts.value = true;
      }).catch(err => 
      {
        console.log(err)
      })
    } 
    catch (error) 
    {
        
    }
}

const closeModalDts = () => 
{
    modalDts.value = false;
    dts.value = [];
}

//Checar movimientos por producto
const modalMovimientosByProducto = ref(false);
const movimientosByProducto = ref([]);
const productoForMovimientos = ref({});
const ultimo_corte = ref(null);
const openModalMovimientosByProducto = async (producto) => 
{
  //console.log(producto)
  productoForMovimientos.value = producto;
  await axios.get(route('getEntradasByProducto',{producto_id:producto.producto_id, categoria_id:producto.categoria_id}))
  .then(response => 
  {
     console.log(response.data)
     //movimientosByProducto.value = response.data;
     ultimo_corte.value = response.data.ultimo_corte;
     let salidas = response.data.salidas;
     let entradas = response.data.entradas;
     let movimientosTotales = [];
     for (let index = 0; index < entradas.length; index++) 
     {
        const entrada = entradas[index];
        let newEntradaType = 
        {
            cantidad: entrada.cantidad,
            dt:'',
            fecha: new Date(entrada.fecha),
            fecha_string:entrada.fecha.substring(0,10),
            hora:entrada.fecha.substring(11,19),
            stage:'',
            tipo:'entrada',
            categoria_producto_id:entrada.categorias_producto_id,
            id:entrada.id
        }
        movimientosTotales.push(newEntradaType)
     }

     for (let index1 = 0; index1 < salidas.length; index1++) 
     {
        const salida = salidas[index1];
        let newSalidaType = 
        {
           cantidad:salida.cantidad,
           dt:salida.dt,
           fecha:new Date(salida.created_at),
           fecha_string:salida.created_at.substring(0,10),
           hora:salida.created_at.substring(11,19),
           stage:salida.stage,
           tipo:'salida',
           categoria_producto_id: salida.categorias_producto_id,
           id:salida.id
        } 
        movimientosTotales.push(newSalidaType)
     }

     //console.log(movimientosTotales)
     
     movimientosByProducto.value = movimientosTotales.sort(function (a, b)
     {
        let fecha1 = new Date(a.fecha);
        let fecha2 = new Date(b.fecha);
        return fecha2 - fecha1;
     })
     modalMovimientosByProducto.value = true;
     //console.log(movimientosByProducto.value);
  })
  .catch(err => 
  {
    console.log(err)
  })
}

const closeModalMvimientosByProducto = () => 
{
  modalMovimientosByProducto.value = false;
  movimientosByProducto.value = [];
}

const newProductos = computed(() =>
  {
    let nuevosProductos = props.productos;

    for (let index = 0; index < nuevosProductos.length; index++) 
    {
      const productos = nuevosProductos[index];
      let newData = [];
      let totalEntradas = 0;
      let totalSalidas = 0;
      for (let index2 = 0; index2 < productos.entradas.length; index2++) 
      {
        const entrada = productos.entradas[index2];
        totalEntradas += entrada.cantidad;
      }
      for (let index3 = 0; index3 < productos.salidas.length; index3++) 
      {
        const salida = productos.salidas[index3];
        totalSalidas += salida.cantidad;
      }

      if(productos.corte_diario == null)
      {
        productos.corte_diario = {cantidad_inicial:0}
      }
      productos.totalEntradas = totalEntradas;
      productos.totalSalidas = totalSalidas;
    }

    return nuevosProductos;
  }
)

const watchInfoProd =  ref(false)
const producto_select = ref(null);
const openWatchInfoProd = (producto) => 
{
  producto_select.value = producto;
  watchInfoProd.value = true
}

const closeWatchInfoProd = () =>
{
  producto_select.value = null;
  watchInfoProd.value = false;
}

const modalGraph = ref(false);
const salidasForModal = ref([]);
const openModalGraph = (producto) => 
{
  //console.log(producto)
  //consulta 
  try 
  {
    axios.get(route('getSalidas',{producto})).then(response => 
    {
       //console.log(response.data)
      ///salidasForModal.value = response.data;
      let arraySalidas = [];
      for (let index = 0; index < response.data.length; index++) 
      {
        const salida = response.data[index];
        let newObjSalida = {
           cantidad: salida.cantidad,
           fecha: salida.created_at.substring(0,10)
        };

        if(arraySalidas.length == 0)
        {
           arraySalidas.push(newObjSalida);
        }
        else
        {
          for (let index2 = 0; index2 < arraySalidas.length; index2++)  //recorremos los objetos del arreglo
          {
            const objetoSalida = arraySalidas[index2];
            if(objetoSalida.fecha == salida.created_at.substring(0,10)) //si son de la misma fecha se suma
            {
                objetoSalida.cantidad += salida.cantidad;
            }
            else
            {
              arraySalidas.push(newObjSalida);
            }
          }
        }
      }

      //console.log(arraySalidas);
      salidasForModal.value = arraySalidas;
      modalGraph.value = true;
    })
    .catch(err=> 
    {
      console.log('error')
    })
  } 
  catch (error) 
  {
    
  }

}

const closeModalGraph = () => 
{
   modalGraph.value = false;
}

</script>

<template>
    <Head title="Inventario" />
    <div class='min-h-screen md:flex bg-[#F5F6FA]' style="font-family: 'Montserrat';"> <!--Contenedor-->
        <SideBar />
        <div class="w-full p-6"> <!--Inicio del apartado de modulos-->
            <h1 class="text-2xl">¡Bienvenido de nuevo, {{ $page.props.auth.user.name }} !</h1>
            <div class="grid w-full grid-cols-7">
                <div class="">
                  <h3 class="mt-2 text-2xl font-semibold tracking-wide uppercase">Inventario</h3>
               </div>
               <div class="flex flex-row justify-between col-start-3 col-end-5"> <!--Botones de importacion-->
                 <button @click="openModalStages()" class="flex flex-row items-center justify-between px-4 mx-1 border rounded-full shadow-lg">
                    <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21.783" height="18.63" viewBox="0 0 21.783 18.63">
                      <defs>
                        <clipPath id="clip-path">
                          <rect id="Rectángulo_2722" data-name="Rectángulo 2722" width="21.783" height="18.63" fill="#2684d0"/>
                        </clipPath>
                      </defs>
                      <g id="Grupo_4442" data-name="Grupo 4442" transform="translate(7 5)">
                        <g id="Grupo_4441" data-name="Grupo 4441" transform="translate(-7 -5)" clip-path="url(#clip-path)">
                          <path id="Trazado_4273" data-name="Trazado 4273" d="M73.1,3.64v7.794a.333.333,0,0,0,.2.314l6.282,3.121v-8Zm7.048,11.229,6.292-3.121a.341.341,0,0,0,.186-.314V3.64L80.143,6.869v8ZM86.3,3.159,80.015.038a.353.353,0,0,0-.314,0L76.766,1.5l6.439,3.2ZM76.118,1.824l-2.7,1.335,6.439,3.21,2.7-1.345Z" transform="translate(-69.093 0.663)" fill="#2684d0" fill-rule="evenodd"/>
                          <path id="Trazado_4274" data-name="Trazado 4274" d="M11.582,253.74a.373.373,0,0,1-.175-.044L1.7,248.513a.373.373,0,0,1,.021-.668l1.933-.883a.373.373,0,1,1,.309.678l-1.248.57,8.873,4.737,9.365-4.757-2.126-.969a.373.373,0,0,1,.309-.678l2.83,1.29a.373.373,0,0,1,.014.671l-10.228,5.2A.372.372,0,0,1,11.582,253.74Z" transform="translate(-0.817 -235.793)" fill="#2684d0"/>
                        </g>
                      </g>
                    </svg>
                    <p class="text-[#2684D0] hover:text-[#344182] text-sm">Stage</p>
                 </button>
                 <button @click="openModalDts()" class="flex flex-row items-center justify-between px-4 mx-1 border rounded-full shadow-lg">
                    <svg class="mr-6" xmlns="http://www.w3.org/2000/svg" width="22" height="13.933" viewBox="0 0 22 13.933">
                      <g id="camion-de-carga" transform="translate(0 -112.204)">
                        <g id="Grupo_4443" data-name="Grupo 4443" transform="translate(0 112.204)">
                          <path id="Trazado_4274" data-name="Trazado 4274" d="M454.75,395.756a1.87,1.87,0,1,0,1.87,1.87A1.872,1.872,0,0,0,454.75,395.756Zm0,2.42a.55.55,0,1,1,.55-.55A.551.551,0,0,1,454.75,398.176Z" transform="translate(-436.6 -385.563)" fill="#2684d0"/>
                          <path id="Trazado_4275" data-name="Trazado 4275" d="M401.471,179.518H398.24a.44.44,0,0,0-.44.44v8.58a.44.44,0,0,0,.44.44h.467a.439.439,0,0,0,.439-.373,2.535,2.535,0,0,1,5.009,0,.438.438,0,0,0,.439.373h.466a.44.44,0,0,0,.44-.44v-4.29a.439.439,0,0,0-.1-.282l-3.589-4.29A.44.44,0,0,0,401.471,179.518Zm-2.791,3.52v-2.2a.44.44,0,0,1,.44-.44h1.938a.44.44,0,0,1,.338.158l1.833,2.2a.44.44,0,0,1-.338.722H399.12A.44.44,0,0,1,398.68,183.038Z" transform="translate(-383.5 -177.098)" fill="#2684d0"/>
                          <path id="Trazado_4276" data-name="Trazado 4276" d="M.44,124.084H1.567a.439.439,0,0,0,.439-.373,2.426,2.426,0,0,1,2.5-2.144,2.426,2.426,0,0,1,2.5,2.144.438.438,0,0,0,.439.373H12.98a.44.44,0,0,0,.44-.44v-11a.44.44,0,0,0-.44-.44H.44a.44.44,0,0,0-.44.44v11A.44.44,0,0,0,.44,124.084Z" transform="translate(0 -112.204)" fill="#2684d0"/>
                          <path id="Trazado_4277" data-name="Trazado 4277" d="M75.31,395.756a1.87,1.87,0,1,0,1.87,1.87A1.872,1.872,0,0,0,75.31,395.756Zm0,2.42a.55.55,0,1,1,.55-.55A.551.551,0,0,1,75.31,398.176Z" transform="translate(-70.8 -385.563)" fill="#2684d0"/>
                        </g>
                      </g>
                    </svg>
                    <p class="text-[#2684D0] hover:text-[#344182] text-sm">DT'S</p>
                 </button>
                 <a :href="route('movimientosExport', {categoria:categoria_actual})" class="flex flex-row items-center justify-between px-4 mx-1 border rounded-full shadow-lg text-[#2684D0] hover:text-[#344182] text-sm">
                   Descargar reporte
                 </a>
                 <div class="flex flex-row items-center mx-4">
                    <div>
                        <ButtonUploadBolo v-model="bolo"  />
                        <p class="text-sm text-danger">
                            {{ formNewThings.errors.bolo }}
                        </p>
                    </div>
                    <SpinProgress :inprogress="showSpinner" class="w-8 h-8 ml-2" v-if="showSpinner" />
                 </div>
                 <!--
                 <button class="flex flex-row items-center justify-between px-4 mx-1 border rounded-full shadow-lg">
                    <div class="text-[#2684D0] w-4 h-4 relative"></div>
                    <p class="text-[#2684D0] hover:text-[#344182] text-sm">Importar bolo</p>
                 </button>
                 -->
               </div>
            </div>
            <div class="grid w-full grid-cols-5 my-4"> <!--Modulos-->
                <!--Slider contenedor general-->
                <div class="col-start-1 col-end-4 px-8 "> <!--Modulo general tabla--> 
                   <div class="wrapper"> <!--Inicio de slide-->
                     <div class="container">
                        <input type="radio" name="slide" id="c1" style="display: none;" checked >
                        <label for="c1" class="card " @click="params.inventario_actual = 1">
                           <div v-if="params.inventario_actual == 2" class="h-full bg-[#177DE9] text-white font-semibold" style="border-radius: 1rem 0% 0% 1rem;" >
                                <div class="flex flex-row w-full rotate-90">
                                  <p class="ml-10 mr-2">
                                    Inventario 
                                  </p>
                                  <p >Diario</p>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="icon"></div>
                                <div class="description">
                                  <div class="flex flex-row justify-between mb-6">
                                    <div>
                                      <h1 class="text-xl font-semibold">Inventario Diario</h1>
                                       <div class="mt-2">
                                         <a :href="route('pdfCodes',categoriaDownload)" class="text-sm text-white bg-[#2684D0] px-2 py-1 rounded-full">
                                           Descargar codigos
                                         </a>
                                       </div>
                                    </div>
                                     <div>
                                          <button @click="cambiarCategoria(categoria)" :class="[ categoria_actual == categoria.id ? 'px-4 mx-2 border rounded-xl bg-[#344182]' : 'px-4 mx-2 border rounded-xl']" v-for="categoria in categorias" :key="categoria.id">
                                              <p :class="[categoria_actual == categoria.id ? 'text-white' : 'text-[#C3C4CE] hover:text-[#344182]']">{{categoria.nombre}}</p>
                                          </button>
                                          <button class="px-4 mx-2 border rounded-xl active:bg-[#344182]">
                                              <p class="text-[#C3C4CE] text-center">+</p>
                                          </button>
                                      </div>
                                  </div>
                                  <div class="h-16 " style="width:100%; height:35rem; overflow:auto;" >
                                    <table class="w-full">
                                     <thead class="border-b">
                                       <td class="flex flex-row py-4">
                                          <p class="mr-2 font-semibold">
                                              Productos
                                          </p>
                                          <button @click="openModalNewProduct()" class="bg-[#70BBF6] rounded-full flex flex-row justify-center items-center w-6">
                                              <p class="font-semibold text-white">+</p>
                                          </button> 
                                      </td>
                                       <td class="py-4 font-semibold">Inicio</td>
                                       <td class="py-4 font-semibold">Entrada</td>
                                       <td class="py-4 font-semibold">Salida</td>
                                       <td class="py-4 font-semibold">Actual</td>
                                       <td>
                                          
                                       </td>
                                     </thead> 
                                     <tbody>
                                       <tr v-for="producto in newProductos" :key="producto.id" >
                                          <td class="py-2 text-[#121A3C]">
                                            <div class="flex flex-row justify-between">
                                              <div class="flex flex-row items-center" :class="((producto.totalEntradas+producto.corte_diario.cantidad_inicial) - producto.totalSalidas) < producto.stock_minimo ? 'text-red-500' : 'bg-white'">
                                                <button class="bg-[#2684D0] mr-4 rounded-lg" @click="openModalGraph(producto)">
                                                  <svg  width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 19L12 11" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                                    <path d="M7 19L7 15" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                                    <path d="M17 19V6" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                                  </svg>
                                                </button>
                                                <p class="mr-4 uppercase"> {{ producto.producto_nombre }}</p>
                                                <img v-if="((producto.totalEntradas+producto.corte_diario.cantidad_inicial) - producto.totalSalidas) < producto.stock_minimo " class="w-4 h-4" src="../../assets/img/advertencia.svg" />
                                              </div>
                                               <button @click="openWatchInfoProd(producto)" class="bg-[#C3C4CE] rounded-full pb-2 px-1 "  >
                                                  <img class="w-6 h-6" src="../../assets/img/….svg" />
                                               </button>
                                            </div>
                                          </td>
                                          <td class="py-2 text-center">
                                            <p class="text-[#121A3C]" v-if="producto.corte_diario">
                                              {{ producto.corte_diario.cantidad_inicial }}
                                            </p>
                                            <p class="text-[#121A3C]" v-else>
                                              0
                                            </p>
                                          </td>
                                          <td class="py-2 text-center">
                                            {{ producto.totalEntradas}}
                                          </td>
                                          <td class="py-2 text-center">
                                            {{ producto.totalSalidas}}
                                          </td>
                                          <td class= "py-2 text-center">
                                            <p v-if="producto.corte_diario">
                                              {{ (producto.totalEntradas+producto.corte_diario.cantidad_inicial) - producto.totalSalidas}}
                                            </p>
                                            <p v-else>
                                              {{ (producto.totalEntradas) - producto.totalSalidas}}
                                            </p>
                                          </td>
                                          <td class="py-2 text-center">
                                             <button @click="openModalMovimientosByProducto(producto)"  class="bg-[#2684D0] px-2 py-1 rounded-2xl">
                                                  <p class="text-sm font-semibold text-white">Movimientos</p>
                                             </button>
                                          </td>
                                       </tr>
                                     </tbody>
                                    </table>
                                  </div>
                                </div>
                            </div>
                        </label>
                        <input type="radio" name="slide" id="c2" style="display: none;" >
                         <label for="c2" class="card" @click="params.inventario_actual = 2">
                              <div v-if="params.inventario_actual == 1" class="h-full bg-[#70BBF6] text-white font-semibold" style="border-radius: 0% 1rem 1rem 0%;">
                                <div class="flex flex-row w-full rotate-90">
                                  <p class="ml-10 mr-2">
                                    Inventario 
                                  </p>
                                  <p >Acumulado</p>
                                </div>  
                              </div>
                             <div class="row " >
                                 <div class="icon"></div>
                                 <div class="description">
                                    <div class="flex flex-row justify-between mb-6">
                                      <div>
                                        <h1 class="text-xl font-semibold">Inventario Acumulado</h1>
                                        <div class="mt-2">
                                         <a :href="route('pdfCodes' ,categoriaDownload)" class="text-sm text-white bg-[#2684D0] px-2 py-1 rounded-full">
                                           Descargar codigos
                                         </a>
                                       </div>
                                      </div>
                                      <div>
                                          <button @click="cambiarCategoria(categoria)" :class="[ categoria_actual == categoria.id ? 'px-4 mx-2 border rounded-xl bg-[#344182]' : 'px-4 mx-2 border rounded-xl']" v-for="categoria in categorias" :key="categoria.id">
                                              <p :class="[categoria_actual == categoria.id ? 'text-white' : 'text-[#C3C4CE] hover:text-[#344182]']">{{categoria.nombre}}</p>
                                          </button>
                                          <button  class="px-4 mx-2 border rounded-xl active:bg-[#344182]">
                                              <p class="text-[#C3C4CE] text-center">+</p>
                                          </button>
                                      </div>
                                    </div>
                                    <div style="width:100%; height:35rem; overflow:auto;">
                                        <table class="w-full"   >
                                     <thead class="border-b">
                                        <td class="flex flex-row py-4">
                                            <p class="mr-2 font-semibold">
                                                Productos
                                            </p>
                                            <button @click="openModalNewProduct(producto)" class="bg-[#70BBF6] rounded-full flex flex-row justify-center items-center w-6">
                                                <p class="font-semibold text-white">+</p>
                                            </button> 
                                        </td>
                                         <td class="py-4 font-semibold">Inicio</td>
                                         <td class="py-4 font-semibold">Entrada</td>
                                         <td class="py-4 font-semibold">Salida</td>
                                         <td class="py-4 font-semibold">Actual</td>
                                         <td></td>
                                       </thead>
                                     <tbody>
                                      <tr v-for="producto in newProductos" :key="producto.id">
                                        <td class="py-2 text-[#121A3C]">
                                            <div class="flex flex-row justify-between">
                                              <div class="flex flex-row items-center" :class="((producto.totalEntradas+producto.corte_diario.cantidad_inicial) - producto.totalSalidas) < producto.stock_minimo ? 'text-red-500' : 'bg-white'">
                                                <button class="bg-[#2684D0] mr-4 rounded-lg" @click="openModalGraph(producto)">
                                                  <svg  width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 19L12 11" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                                    <path d="M7 19L7 15" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                                    <path d="M17 19V6" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                                  </svg>
                                                </button>
                                                <p class="mr-4 uppercase"> {{ producto.producto_nombre }}</p>
                                                <img v-if="((producto.totalEntradas+producto.corte_diario.cantidad_inicial) - producto.totalSalidas) < producto.stock_minimo " class="w-4 h-4" src="../../assets/img/advertencia.svg" />
                                              </div>
                                               <button @click="openWatchInfoProd(producto)" class="bg-[#C3C4CE] rounded-full pb-2 px-1 ">
                                                  <img class="w-6 h-6" src="../../assets/img/….svg" />
                                               </button>
                                            </div>
                                          </td>
                                          <td class="py-2 text-center">
                                            <p class="text-[#121A3C]" v-if="producto.corte_diario">
                                              {{ producto.corte_diario.cantidad_inicial }}
                                            </p>
                                            <p class="text-[#121A3C]" v-else>
                                              0
                                            </p>
                                          </td>
                                          <td class="py-2 text-center">
                                            {{ producto.totalEntradas}}
                                          </td>
                                          <td class="py-2 text-center">
                                            {{ producto.totalSalidas}}
                                          </td>
                                          <td class= "py-2 text-center">
                                            <p v-if="producto.corte_diario">
                                              {{ (producto.totalEntradas+producto.corte_diario.cantidad_inicial) - producto.totalSalidas}}
                                            </p>
                                            <p v-else>
                                              {{ (producto.totalEntradas) - producto.totalSalidas}}
                                            </p>
                                          </td>
                                          <td class="py-2 text-center">
                                             <button @click="openModalMovimientosByProducto(producto)"  class="bg-[#2684D0] px-2 py-1 rounded-2xl">
                                                  <p class="text-sm font-semibold text-white">Movimientos</p>
                                             </button>
                                          </td>
                                       </tr>
                                     </tbody>
                                        </table>
                                    </div>

                                 </div>
                             </div>
                         </label>
                     </div>
                   </div><!--fin de slide-->
                </div>
                <!--Fin de Slider contenedor general-->
                <div class="flex flex-col col-start-4 col-end-6 gap-4">
                    <Entradas :categorias="categorias" />    
                    <Salidas :categorias="categorias"/> 
                </div>
            </div> <!--Fin Modulos-->
        </div> <!--Fin del apartado de modulos-->
        <!--Modales-->
        <ModalNewProduct :show="modalNewProduct" @close="closeModalNewProduct()" :categoria="categoria_actual" />
        <ModalStages :show="stageModal" :stages="stages" @close="closeModalStages()" />
        <ModalDts :show="modalDts" @close="closeModalDts()" :dts="dts" />
        <ModalMovimientosByProducto :show="modalMovimientosByProducto" :movimientosByProducto="movimientosByProducto" :productoForMovimientos="productoForMovimientos" :ultimo_corte="ultimo_corte" @close="closeModalMvimientosByProducto()" />
        <ModalWatchProducInfo :show="watchInfoProd" :producto="producto_select" @close="closeWatchInfoProd()" />
        <ModalGraph :show="modalGraph" @close="closeModalGraph()" :salidasForModal="salidasForModal"  />
        <!--Fin Modales-->
    </div> <!--Fin contenedor-->
</template>
<style>
.wrapper {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.container {
    height: 100%;
    display: flex;

}

.card {
    width: 70px;
    border-radius: .75rem;
    cursor: pointer;
    overflow: hidden;
    border-radius: 1%;
    margin: -4px;
    transition: .6s cubic-bezier(.28,-0.03,0,.99);
    background-color:#FFFFFF ;
    box-shadow: 0px 10px 20px -5px rgba(0,0,0,0.8);
}

.card > .row {
}

.card > .row > .icon 
{
  
  /*
  background: #223;
    color: white;
    border-radius: 50%;
    width: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 15px;
    */
}

.card > .row > .description 
{
  
}

.description p {
  
   
}

.description h4 {
    text-transform: uppercase;
}


input:checked + label 
{
    width: 100%;
    z-index: 1;
}

input:checked + label .description 
{
    opacity: 1 !important;
    transform: translateY(0) !important;
    display: block;
    padding: 1rem;
}


.description
{
 display: none;
}


</style>
