<script setup>
import { ref, watch, computed, reactive } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import Roles from './Partials/Roles.vue';
import Permisos from './Partials/Permisos.vue'
//Modales
import ModalNewUser from './Partials/ModalNewUser.vue'

var props = defineProps({
  usuarios:Object,
  roles: Object,
  permisos: Object
});

const modalNuevoUsuario = ref(false);
const openModalNuevoUsuario = () => 
{
    modalNuevoUsuario.value = true;
} 
const closeModalNuevoUsuario = () => 
{
    modalNuevoUsuario.value = false;
}

const rol = ref(null);

const selectRol = (id) => 
{ 
  //console.log(id)
  rol.value = id
}
 
</script>
<template>
    <Head title="Inventario" />
    <div class='min-h-screen md:flex bg-[#F5F6FA]' style="font-family: 'Montserrat';"> <!--Contenedor-->
        <SideBar />
        <div class="grid w-full grid-cols-6 grid-rows-6 gap-4 p-6"> <!--Inicio del apartado de modulos-->
            <h1 class="col-start-1 col-end-2 row-start-1 row-end-2 text-2xl">Usuarios</h1>
            <div class="col-start-1 col-end-5 row-start-1 row-end-7 p-6 bg-white rounded-lg shadow-lg" style="margin-top: 3rem;">
               <button @click="openModalNuevoUsuario()" class="bg-[#344182] px-4 py-1 rounded-full text-white">
                  Nuevo usuario
               </button>
               <table class="w-full">
                 <thead>
                    <tr class="border-b-2 border-[#344182]">
                        <td class="py-4 font-semibold text-center">Nombre</td>
                        <td class="py-4 font-semibold text-center">Ap. Paterno</td>
                        <td class="py-4 font-semibold text-center">Ap. Materno</td>
                        <td class="py-4 font-semibold text-center">Correo</td>
                        <td class="py-4 font-semibold text-center">Rol</td>
                    </tr>
                 </thead>
                 <tbody>
                    <tr v-for="usuario in usuarios.data" :key="usuario.id">
                        <td class="text-center">
                            {{ usuario.name }}
                        </td>
                        <td class="text-center">
                            {{ usuario.apellido_paterno }}
                        </td>
                        <td class="text-center">
                            {{ usuario.apellido_materno }}
                        </td>
                        <td class="text-center">
                            {{ usuario.email }}
                        </td>
                    </tr>
                 </tbody>
               </table>
            </div>
            <div class="col-start-5 col-end-7 row-start-1 row-end-7 p-6 bg-white rounded-lg shadow-lg" >
                <h1 class="text-xl font-semibold text-center">Roles y permisos</h1>
                <div class="py-4 my-2 rounded-lg shadow">
                    <div class="flex flex-row justify-center">
                        <h1 class="font-semibold text-center">Roles</h1>
                        <button class="bg-[#344182] text-white mx-4 px-2 rounded-full">
                            +
                        </button>
                    </div>
                    <Roles :roles="roles" @selectRol="selectRol" />
                </div>
                <Permisos  :permisos="permisos" :rol="rol" />
            </div>
        </div>
        <!--Modales-->
        <ModalNewUser :show="modalNuevoUsuario" @close="closeModalNuevoUsuario()" /> 
    </div>
</template>