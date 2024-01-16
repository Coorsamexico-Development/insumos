<script setup>
 import { reactive, ref, watch, onUpdated  } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import { useForm, router } from '@inertiajs/vue3'

 const emit = defineEmits(["close"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
  });

  const close = () =>
  { 
     emit('close');
  };

 //Formulario para nuevo usuario
 const formNewUser= useForm({
    nombre: '',
    ap_paterno:'',
    ap_materno:'',
    email:'',
    contraseña:''
 });

 const saveUser = async () => 
 {
    formNewUser.post(route('newUser'),
       {
        onSuccess : () => 
            {
                formNewUser.reset();
                close();
            },
        preserveScroll:true,
        preserveState:true
      });
 }
</script>
<template>
  <DialogModal :maxWidth="'xl'"  :show="show" @close="close()">
       <template #content>
          <button class="bg-[#C3C4CE] float-right rounded-full py-2 px-2" @click="close()">
            <svg id="Grupo_394" data-name="Grupo 394" xmlns="http://www.w3.org/2000/svg" width="11.344" height="11.344" viewBox="0 0 11.344 11.344">
             <path id="Trazado_1910" data-name="Trazado 1910" d="M71.01,81.695a.584.584,0,0,1-.412-1L80.774,70.521a.584.584,0,0,1,.825.825L71.423,81.523A.585.585,0,0,1,71.01,81.695Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
             <path id="Trazado_1911" data-name="Trazado 1911" d="M81.187,81.695a.579.579,0,0,1-.412-.171L70.6,71.347a.584.584,0,0,1,.825-.825L81.6,80.7a.584.584,0,0,1-.412,1Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
           </svg>
          </button>
          <div class="" style="font-family: 'Montserrat';">
            <div class="flex flex-row items-center pb-2 align-middle">
                <h1 class="text-[#0A0F2C]  text-2xl mr-2 font-semibold">Nuevo usuario</h1>
            </div>
            <form>
                <div class="flex flex-col my-4">
                    <label htmlFor="nombre" class="text-lg">Nombre</label>
                    <input v-model="formNewUser.nombre" required  id="nombre" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
                </div>
                <div class="flex flex-col my-4">
                    <label htmlFor="ap_paterno" class="text-lg">Apellido paterno</label>
                    <input v-model="formNewUser.ap_paterno"  required  id="ap_paterno" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
                </div>
                <div class="flex flex-col my-4">
                    <label htmlFor="ap_materno" class="text-lg">Apellido materno</label>
                    <input v-model="formNewUser.ap_materno"  required  id="ap_materno" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
                </div>
                <div class="flex flex-col my-4">
                    <label htmlFor="correo" class="text-lg">Correo</label>
                    <input v-model="formNewUser.email"  required type="email"  id="correo" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
                </div>
                <div class="flex flex-col my-4">
                    <label htmlFor="contraseña" class="text-lg">Contraseña</label>
                    <input v-model="formNewUser.contraseña"  required  type="password" id="contraseña" class="w-full px-3 py-2 leading-tight text-gray-700 rounded shadow appearance-none border-none bg-[#F6F6F9]" />
                </div>
                <div class="flex flex-row justify-center">
                    <button @click="saveUser()" class="bg-[#344182] px-4 py-1 rounded-full text-white text-xl">
                      Guardar
                    </button>
                </div>
            </form>
          </div>
       </template>
  </DialogModal>
</template>