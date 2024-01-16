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
                  <svg :class="(route().current('dashboard')) ? 'fill-white' : 'fill-[#5562a3]'"  xmlns="http://www.w3.org/2000/svg" width="33.152" height="33.151" viewBox="0 0 33.152 33.151">
                    <path id="casa" d="M16.766,2.586a3.086,3.086,0,0,1,3.619,0L34.568,14.359a1.414,1.414,0,0,1-.724,2.558H31.836V33.493a1.658,1.658,0,0,1-1.658,1.658h-6.63a1.658,1.658,0,0,1-1.658-1.658v-6.63a1.658,1.658,0,0,0-1.658-1.658H16.918a1.658,1.658,0,0,0-1.658,1.658v6.63A1.658,1.658,0,0,1,13.6,35.151H6.973a1.658,1.658,0,0,1-1.658-1.658V16.918H3.308a1.414,1.414,0,0,1-.724-2.558Z" transform="translate(-2 -2)"  fill-rule="evenodd"/>
                  </svg>
                </NavLinkHorizontal>
              </div>
              <div class="block">
                <NavLinkHorizontal :href="route('usuarios')" :active="route().current('usuarios')">
                  <svg :class="(route().current('usuarios')) ? 'fill-white' : 'fill-[#5562a3]'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="41.086" height="41.085" viewBox="0 0 41.086 41.085">
                     <defs>
                       <clipPath id="clip-path">
                         <path id="Trazado_4262" data-name="Trazado 4262" d="M.6.5H41.688V41.585H.6Z" transform="translate(-0.602 -0.5)" fill="none"/>
                       </clipPath>
                     </defs>
                     <g id="usuario" transform="translate(0)" clip-path="url(#clip-path)">
                       <path id="Trazado_4256" data-name="Trazado 4256" d="M14.594,17.483A5.992,5.992,0,1,0,8.6,11.492,5.992,5.992,0,0,0,14.594,17.483Z" transform="translate(5.094 3.059)" />
                       <path id="Trazado_4257" data-name="Trazado 4257" d="M20.882,18.059a4.28,4.28,0,1,0-4.28-4.28A4.28,4.28,0,0,0,20.882,18.059Z" transform="translate(10.789 5.907)" />
                       <path id="Trazado_4258" data-name="Trazado 4258" d="M6.952,18.059a4.28,4.28,0,1,0-4.28-4.28A4.28,4.28,0,0,0,6.952,18.059Z" transform="translate(0.872 5.907)"/>
                       <path id="Trazado_4259" data-name="Trazado 4259" d="M16.73,13.5A11.14,11.14,0,0,0,5.6,24.627a.856.856,0,0,0,.856.856H27a.856.856,0,0,0,.856-.856A11.14,11.14,0,0,0,16.73,13.5Z" transform="translate(2.958 8.754)" />
                       <path id="Trazado_4260" data-name="Trazado 4260" d="M9.9,15.685a7.681,7.681,0,0,0-1.6-.185A7.713,7.713,0,0,0,.6,23.2a.856.856,0,0,0,.856.856H7.607A2.539,2.539,0,0,1,7.45,23.2,12.758,12.758,0,0,1,9.9,15.685Z" transform="translate(-0.601 10.178)" />
                       <path id="Trazado_4261" data-name="Trazado 4261" d="M19.77,15.5a7.681,7.681,0,0,0-1.6.185A12.758,12.758,0,0,1,20.626,23.2a2.539,2.539,0,0,1-.157.856h6.149a.856.856,0,0,0,.856-.856A7.713,7.713,0,0,0,19.77,15.5Z" transform="translate(11.907 10.178)" />
                     </g>
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