<script setup>
import { ref, watch, computed, reactive } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import Roles from './Partials/Roles.vue';
import Permisos from './Partials/Permisos.vue'
//Modales
import ModalNewUser from './Partials/ModalNewUser.vue'
import ModalNewRol from './Partials/ModalNewRol.vue'
import ModalNewPermission from  './Partials/ModalNewPermission.vue'
import ModalEditUser from './Partials/ModalEditUser.vue'

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
 
const newRolModal = ref(false);
const openNewRolModal = () => 
{
  newRolModal.value = true;
}
const closeNewRolModal = () => 
{
    newRolModal.value = false;
}

const newPermissionModal = ref(false);
const openNewPermissionModal = () => 
{
  newPermissionModal.value = true;
}
const closeNewPermissionModal = () => 
{
    newPermissionModal.value = false
}

const editUser = ref(false);
const userActive = ref({});

const openEditUser = (user) => 
{
  editUser.value = true;
  userActive.value = user;
}

const closeEditUser = () => 
{
  editUser.value = false;

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
                        <td class="py-2 text-center">
                            <div class="flex flex-row justify-between">
                                <button @click="openEditUser(usuario)" class="bg-[#44BFFC] rounded-xl px-4 py-2">
                                   <svg xmlns="http://www.w3.org/2000/svg" fill="white" class="w-4 h-4" viewBox="0 0 16 16">
                                      <path
                                          d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                      <path fill-rule="evenodd"
                                          d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                   </svg>
                               </button>  
                                {{ usuario.name }}
                            </div>
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
                        <td class="text-center">
                            {{ usuario.role_name }}
                        </td>
                    </tr>
                 </tbody>
               </table>
            </div>
            <h1 class="text-xl font-semibold text-center">Roles y permisos</h1>
            <div class="grid col-start-5 col-end-7 grid-rows-2 row-start-1 row-end-7 p-6 bg-white rounded-lg shadow-xl" >
                <div class="py-4 my-2 rounded-lg shadow">
                    <div class="flex flex-row justify-center">
                        <h1 class="font-semibold text-center">Roles</h1>
                        <button @click="openNewRolModal()" class="bg-[#344182] text-white mx-4 px-2 rounded-full">
                            +
                        </button>
                    </div>
                    <Roles :roles="roles" @selectRol="selectRol" />
                </div>
                <div class="py-4 my-2 rounded-lg shadow-xl">
                    <div class="flex flex-row justify-center">
                        <h1 class="font-semibold text-center">Permisos</h1>
                        <button @click="openNewPermissionModal()" class="bg-[#344182] text-white mx-4 px-2 rounded-full">
                            +
                        </button>
                    </div>
                    <Permisos  :permisos="permisos" :rol="rol" />
                </div>
            </div>
        </div>
        <!--Modales-->
        <ModalNewUser :show="modalNuevoUsuario" @close="closeModalNuevoUsuario()" /> 
        <ModalNewRol :show="newRolModal" @close="closeNewRolModal()" />
        <ModalNewPermission :show="newPermissionModal" @close="closeNewPermissionModal()" />
        <div v-if="userActive !== {}">
          <ModalEditUser @close="closeEditUser()" :show="editUser" :user="userActive" :roles="roles" />
        </div>
    </div>
</template>