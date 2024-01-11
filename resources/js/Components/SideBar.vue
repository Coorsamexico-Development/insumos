<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import NavLinkHorizontal from '@/Components/NavLinkHorizontal.vue';

const logout = () => {
    router.post(route('logout'));
};
</script>
<template>
    <div class="py-4 bg-[#344182] md:w-1/14 xl:13/14"> <!--menu-->
           <nav class="grid h-full grid-rows-6 py-4 pl-2">
              <div class="flex flex-row justify-center">
                 <Link :href="route('dashboard')">
                     <ApplicationMark class="block w-auto h-9" />
                 </Link>
             </div>
              <div class="block">
                <NavLinkHorizontal :href="route('dashboard')" :active="route().current('dashboard')">
                  <svg xmlns="http://www.w3.org/2000/svg" width="33.152" height="33.151" viewBox="0 0 33.152 33.151">
                    <path id="casa" d="M16.766,2.586a3.086,3.086,0,0,1,3.619,0L34.568,14.359a1.414,1.414,0,0,1-.724,2.558H31.836V33.493a1.658,1.658,0,0,1-1.658,1.658h-6.63a1.658,1.658,0,0,1-1.658-1.658v-6.63a1.658,1.658,0,0,0-1.658-1.658H16.918a1.658,1.658,0,0,0-1.658,1.658v6.63A1.658,1.658,0,0,1,13.6,35.151H6.973a1.658,1.658,0,0,1-1.658-1.658V16.918H3.308a1.414,1.414,0,0,1-.724-2.558Z" transform="translate(-2 -2)" fill="#fff" fill-rule="evenodd"/>
                  </svg>
                </NavLinkHorizontal>
              </div>
              <div class="flex justify-center row-start-5 ">
                <button class="text-white " @click="logout()">
                  <svg class="hover:fill-white fill-[#5562a3]" xmlns="http://www.w3.org/2000/svg" width="35.883" height="35.882" viewBox="0 0 35.883 35.882">
                    <g id="cerrar-sesion_1_" data-name="cerrar-sesion (1)" transform="translate(0)">
                      <path id="Trazado_4263" data-name="Trazado 4263" d="M22.426,19.44a1.5,1.5,0,0,0-1.5,1.495v5.98a1.5,1.5,0,0,1-1.5,1.495H14.951V5.984a3.013,3.013,0,0,0-2.036-2.842l-.443-.148h6.964a1.5,1.5,0,0,1,1.5,1.5V8.974a1.5,1.5,0,0,0,2.99,0V4.489A4.491,4.491,0,0,0,19.436,0H3.364A1.174,1.174,0,0,0,3.2.037C3.132.031,3.063,0,2.99,0A2.993,2.993,0,0,0,0,2.994V29.905a3.013,3.013,0,0,0,2.036,2.842l9,3a3.1,3.1,0,0,0,.927.139,2.993,2.993,0,0,0,2.99-2.99V31.4h4.485a4.491,4.491,0,0,0,4.485-4.485v-5.98A1.5,1.5,0,0,0,22.426,19.44Zm0,0" transform="translate(0 -0.004)" />
                      <path id="Trazado_4264" data-name="Trazado 4264" d="M293.34,113.084l-5.981-5.98a1.494,1.494,0,0,0-2.552,1.057v4.485h-5.98a1.495,1.495,0,1,0,0,2.99h5.98v4.485a1.495,1.495,0,0,0,2.552,1.057l5.981-5.98A1.493,1.493,0,0,0,293.34,113.084Zm0,0" transform="translate(-257.896 -99.19)" />
                    </g>
                  </svg>
                </button>
              </div>
           </nav>
        </div> <!--end menu-->
</template>