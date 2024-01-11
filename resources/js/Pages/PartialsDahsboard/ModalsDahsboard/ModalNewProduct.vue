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
      categoria:Number
  });

  const close = () =>
  { 
     emit('close');
  };

  //Formulario para nuevo proyecto
  const formNewProducto = useForm({
       nombre: '',
       categoria:-1,
       tiempo_resturtido: 0,
       stock: 0
    });

 const saveNewProduct = async () => 
 {
    formNewProducto.categoria = props.categoria;
    if(formNewProducto.nombre !== '' && formNewProducto.categoria !== -1, formNewProducto.tiempo_resturtido !== 0 && formNewProducto.stock !== null )
    {
        formNewProducto.post(route('saveNewProduct'),
       {
        onSuccess : () => 
            {
               formNewProducto.reset();
               close();
            },
        preserveScroll:true,
        preserveState:true
      });
    }
    else
    {
        alert('Todos los campos son necesarios')
    }

 }

</script>
<template>
  <DialogModal :maxWidth="'sm'"  :show="show" @close="close()">
       <template #content>
          <button class="bg-[#C3C4CE] float-right rounded-full py-2 px-2" @click="close()">
            <svg id="Grupo_394" data-name="Grupo 394" xmlns="http://www.w3.org/2000/svg" width="11.344" height="11.344" viewBox="0 0 11.344 11.344">
             <path id="Trazado_1910" data-name="Trazado 1910" d="M71.01,81.695a.584.584,0,0,1-.412-1L80.774,70.521a.584.584,0,0,1,.825.825L71.423,81.523A.585.585,0,0,1,71.01,81.695Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
             <path id="Trazado_1911" data-name="Trazado 1911" d="M81.187,81.695a.579.579,0,0,1-.412-.171L70.6,71.347a.584.584,0,0,1,.825-.825L81.6,80.7a.584.584,0,0,1-.412,1Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
           </svg>
          </button>
          <div class="">
            <h1 class="text-[#0A0F2C]  text-2xl">Nuevo Producto</h1>
            <form class="mt-4"> <!--formulario-->
                <div class="flex flex-col my-4">
                    <label htmlFor="nombre" class="text-lg">Nombre</label>
                    <input required v-model="formNewProducto.nombre" id="nombre" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
                </div>
                <div class="flex flex-col my-4">
                    <label htmlFor="resurtido" class="text-lg">Tiempo en resurtir</label>
                    <input required v-model="formNewProducto.tiempo_resturtido" id="resurtido" type="number" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
                </div>
                <div class="flex flex-col my-4">
                    <label htmlFor="stock" class="text-lg">Stock mínimo</label>
                    <input required v-model="formNewProducto.stock" id="stock" type="number" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
                </div>
                <div class="flex flex-row justify-center">
                  <button @click="saveNewProduct()" class="bg-[#1F97F2] px-10 py-1 rounded-full">
                      <p class="text-lg font-semibold text-white">Guardar</p>
                  </button>
                </div>
            </form>
          </div>
       </template>
  </DialogModal>
</template>