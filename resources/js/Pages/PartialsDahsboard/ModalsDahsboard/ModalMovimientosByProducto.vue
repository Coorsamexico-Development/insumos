<script setup>
 import { reactive, ref, watch, onUpdated,    } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import { useForm, router } from '@inertiajs/vue3'
 import { pickBy } from "lodash";

 const emit = defineEmits(["close",'reconsultarMovimiento'])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      movimientosByProducto:Object,
      productoForMovimientos:Object,
      ultimo_corte:Object
  });

  const close = () =>
  { 
     emit('close');
  };

  const movimientosTotalesModal = ref([]);

  onUpdated(() => 
  {
   movimientosTotalesModal.value = props.movimientosByProducto;
  }
  )

  const eliminarMovimiento = async (movimiento) => 
  {
   
    try 
    {
      await axios.post(route('eliminarMovimiento', {movimiento:movimiento}))
       .then(response => 
       {
          reconsultar();
       })
       .catch (err)
       {
         console.log(err)
       } 
    } 
    catch (error) 
    {
      
    }
    
  }

  const reconsultar = () => 
  {
    try 
    {
      axios.get(route('getEntradasByProducto',{producto:props.productoForMovimientos, tipo:'entradas'}))
       .then(response => 
       {
          //console.log(response.data)
          //movimientosByProducto.value = response.data;
         // ultimo_corte.value = response.data.ultimo_corte;
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
          
          movimientosTotalesModal.value = movimientosTotales.sort(function (a, b)
          {
             let fecha1 = new Date(a.fecha);
             let fecha2 = new Date(b.fecha);
             return fecha2 - fecha1;
          })
       })
       .catch(err => 
       {
         console.log(err)
       }) 
    } 
    catch (error) 
    {
      
    }
  }

</script>
<template>
  <DialogModal :maxWidth="'2xl'"  :show="show" @close="close()">
       <template #content>
          <div class="" style="font-family: 'Montserrat';">
              <button class="bg-[#C3C4CE] float-right rounded-full py-2 px-2" @click="close()">
                <svg id="Grupo_394" data-name="Grupo 394" xmlns="http://www.w3.org/2000/svg" width="11.344" height="11.344" viewBox="0 0 11.344 11.344">
                 <path id="Trazado_1910" data-name="Trazado 1910" d="M71.01,81.695a.584.584,0,0,1-.412-1L80.774,70.521a.584.584,0,0,1,.825.825L71.423,81.523A.585.585,0,0,1,71.01,81.695Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
                 <path id="Trazado_1911" data-name="Trazado 1911" d="M81.187,81.695a.579.579,0,0,1-.412-.171L70.6,71.347a.584.584,0,0,1,.825-.825L81.6,80.7a.584.584,0,0,1-.412,1Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
               </svg>
              </button>
          </div>
          <div style="font-family: 'Montserrat';">
             <h1 class="text-[#0A0F2C]  text-2xl">Movimientos</h1>
          </div>
          <div class="mt-4" style="font-family: 'Montserrat';">
             <table class="w-full">
                <tr class="border-b-2">
                    <td class="py-2 font-semibold text-center text-[#0A0F2C]">Producto</td>
                    <td class="py-2 font-semibold text-center text-[#0A0F2C]">Movimiento</td>
                    <td class="py-2 font-semibold text-center text-[#0A0F2C]">Fecha</td>
                    <td class="py-2 font-semibold text-center text-[#0A0F2C]">Hora</td>
                    <td class="py-2 font-semibold text-center text-[#0A0F2C]">Cantidad</td>
                    <td class="py-2 font-semibold text-center text-[#0A0F2C]">Dt</td>
                    <td class="py-2 font-semibold text-center text-[#0A0F2C]">Stage</td>
                </tr>
                <tr v-for="(movimiento, key) in movimientosByProducto" :key="key">
                   <td class="text-center text-[#0A0F2C]">
                     <div class="flex flex-row justify-between">
                         <div v-if="$page.props.auth.user.cans['can-delete-mov']">
                           <button @click="eliminarMovimiento(movimiento)" v-if="new Date((movimiento.fecha_string + ' ' + movimiento.hora)) > new Date(ultimo_corte.fecha)" class="px-1 py-1 bg-red-400 rounded-lg">
                            <img class="w-4" src="../../../../assets/img/eliminar.png" />
                           </button>
                         </div>
                         <div>
                           <p class="ml-2">
                             {{ productoForMovimientos.producto_nombre }}
                           </p>
                         </div>
                     </div>
                   </td>
                   <td class="py-2 text-center">
                    <div v-if="movimiento.tipo == 'salida'" class="grid items-center justify-center grid-cols-2 align-middle justify-items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15">
                        <g id="iniciar-sesion_1_" data-name="iniciar-sesion (1)" transform="translate(0)">
                          <path id="Trazado_4282" data-name="Trazado 4282" d="M16.639.009C16.623.008,16.61,0,16.594,0H9.875A1.877,1.877,0,0,0,8,1.875V2.5a.625.625,0,0,0,1.25,0V1.875a.626.626,0,0,1,.625-.625h2.912l-.191.064A1.256,1.256,0,0,0,11.75,2.5v9.375H9.875a.626.626,0,0,1-.625-.625V10A.625.625,0,0,0,8,10v1.25a1.877,1.877,0,0,0,1.875,1.875H11.75v.625A1.251,1.251,0,0,0,13,15a1.314,1.314,0,0,0,.4-.062l3.755-1.252A1.256,1.256,0,0,0,18,12.5V1.25A1.252,1.252,0,0,0,16.639.009Z" transform="translate(-3)" fill="#1fbaff"/>
                          <path id="Trazado_4283" data-name="Trazado 4283" d="M6.692,7.683l-2.5-2.5a.625.625,0,0,0-1.067.442V7.5H.625a.625.625,0,0,0,0,1.25h2.5v1.875a.625.625,0,0,0,1.067.442l2.5-2.5A.624.624,0,0,0,6.692,7.683Z" transform="translate(6.875 14.375) rotate(180)" fill="#1fbaff"/>
                        </g>
                      </svg>
                      <p  class="text-[#1FBAFF]">
                        Salida
                      </p>
                    </div>
                    <div v-else class="grid items-center justify-center grid-cols-2 align-middle justify-items-center">
                       <svg id="iniciar-sesion_1_" data-name="iniciar-sesion (1)" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15">
                          <path id="Trazado_4282" data-name="Trazado 4282" d="M16.639.009C16.623.008,16.61,0,16.594,0H9.875A1.877,1.877,0,0,0,8,1.875V2.5a.625.625,0,0,0,1.25,0V1.875a.626.626,0,0,1,.625-.625h2.912l-.191.064A1.256,1.256,0,0,0,11.75,2.5v9.375H9.875a.626.626,0,0,1-.625-.625V10A.625.625,0,0,0,8,10v1.25a1.877,1.877,0,0,0,1.875,1.875H11.75v.625A1.251,1.251,0,0,0,13,15a1.314,1.314,0,0,0,.4-.062l3.755-1.252A1.256,1.256,0,0,0,18,12.5V1.25A1.252,1.252,0,0,0,16.639.009Z" transform="translate(-3)" fill="#2684d0"/>
                          <path id="Trazado_4283" data-name="Trazado 4283" d="M6.692,7.683l-2.5-2.5a.625.625,0,0,0-1.067.442V7.5H.625a.625.625,0,0,0,0,1.25h2.5v1.875a.625.625,0,0,0,1.067.442l2.5-2.5A.624.624,0,0,0,6.692,7.683Z" transform="translate(0 -1.875)" fill="#2684d0"/>
                       </svg>
                       <a :href="route('downloadFactura',movimiento)" class="text-[#2684D0]"> 
                         Entrada
                       </a>
                    </div>
                   </td>
                   <td class="text-center text-[#0A0F2C]">
                     {{ movimiento.fecha_string }}
                   </td>
                   <td class="text-center text-[#0A0F2C]">
                      {{ movimiento.hora }}
                   </td>
                   <td class="text-center text-[#0A0F2C]">
                      {{ movimiento.cantidad }}
                   </td>
                   <td class="text-center text-[#0A0F2C]">
                      {{ movimiento.dt }}
                   </td>
                   <td class="text-center text-[#0A0F2C]">
                      {{ movimiento.stage }}
                   </td>
                </tr>
             </table>
          </div>
       </template>
  </DialogModal>
</template>