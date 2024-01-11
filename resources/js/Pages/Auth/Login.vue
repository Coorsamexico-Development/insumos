<script setup>
import { Head, Link, useForm,  } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

import {ref} from 'vue'

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};


</script>

<template>
    <Head title="Inicio" />
    <div class="grid h-full grid-cols-12" style="font-family: 'Montserrat';">
       <div class="bg-[#DAF2FE] min-h-screen col-start-1 col-end-9">
           <div class="p-9">
               <h1 class="text-[#344182]  tracking-widest" style="font-size: 60px; font-weight:;">Plataforma</h1>
               <h2 class="text-[#177DE9]" style="font-size: 102px; margin-top: -2rem; font-weight: 600
               ;">Insumos</h2>
           </div>
           <div class="flex flex-row justify-center">
            <video autoplay muted loop style="width: 30rem; opacity:0.9;">
             <source
             size="1080"
             src="../../../assets/img/animacion_login.mp4"
             type="video/mp4"
             />
            </video>
           </div>
       </div>
       <div class="min-h-screen col-start-9 col-end-13">
        <div class="flex flex-col h-full mx-10 justify-evenly">
            <div class="flex flex-row justify-center p-9">
              <img class="h-16 w-18" src="../../../assets/img/logo coorsa.svg" />
           </div>
           <div class="flex flex-col justify-center px-9">
               <h1 class="text-3xl uppercase text-[#344182]  tracking-wider text-center mb-8">Bienvenido</h1>    
               <form class="" @submit.prevent="submit">
                   <div>
                       <TextInput
                           id="email"
                           v-model="form.email"
                           type="email"
                           class="block w-full mt-1 bg-[#F6F6F9] border-none"
                           required
                           autofocus
                           autocomplete="username"
                           placeholder="USUARIO"
                       />
                       <InputError class="mt-2" :message="form.errors.email" />
                   </div>

                   <div class="mt-8">
                       <TextInput
                           id="password"
                           v-model="form.password"
                           type="password"
                           class="block w-full mt-1 bg-[#F6F6F9] border-none"
                           required
                           autocomplete="current-password"
                           placeholder="CONTRASEÑA"
                       />
                       <InputError class="mt-2" :message="form.errors.password" />
                   </div>
                 <!--
                   <div class="block mt-4">
                       <label class="flex items-center">
                           <Checkbox v-model:checked="form.remember" name="remember" />
                           <span class="text-sm text-gray-600 ms-2">Remember me</span>
                       </label>
                   </div>
                 -->
                   <div class="flex items-center justify-center mt-16">
                    <!--
                       <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                           Forgot your password?
                       </Link>
                    -->
                       <button class=" bg-[#2684D0] text-white w-full rounded-lg py-3 font-semibold" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                           Iniciar sesión
                       </button>
                   </div>
               </form>
           </div>
        </div>
       </div>
    </div>
<!--
    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="block w-full mt-1"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="block w-full mt-1"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="text-sm text-gray-600 ms-2">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Forgot your password?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
    -->
</template>
<style>

</style>
