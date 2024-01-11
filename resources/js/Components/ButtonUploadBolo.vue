<template>
    <div class="flex flex-row items-center w-48 px-3 py-2 border rounded-full shadow-lg"
     @click="selectFile()" @drop="drop" @dragover.prevent="checkDrop">
     <svg class="mr-6" xmlns="http://www.w3.org/2000/svg" width="15.207" height="16.021" viewBox="0 0 12.207 13.021">
        <g id="subir_1_" data-name="subir (1)" transform="translate(-16)">
          <g id="Grupo_4436" data-name="Grupo 4436" transform="translate(18.848)">
            <g id="Grupo_4435" data-name="Grupo 4435" transform="translate(0)">
              <path id="Trazado_4265" data-name="Trazado 4265" d="M134.408,3.394,131.559.139a.406.406,0,0,0-.612,0L128.1,3.394a.407.407,0,0,0,.306.675h1.628v5.29a.407.407,0,0,0,.407.407h1.628a.407.407,0,0,0,.407-.407V4.069H134.1a.407.407,0,0,0,.306-.675Z" transform="translate(-127.998)" fill="#2684d0"/>
            </g>
          </g>
          <g id="Grupo_4438" data-name="Grupo 4438" transform="translate(16 8.952)">
            <g id="Grupo_4437" data-name="Grupo 4437" transform="translate(0)">
              <path id="Trazado_4266" data-name="Trazado 4266" d="M26.58,352v2.441H17.628V352H16v3.255a.814.814,0,0,0,.814.814h10.58a.813.813,0,0,0,.814-.814V352Z" transform="translate(-16 -352)" fill="#2684d0"/>
            </g>
          </g>
        </g>
      </svg>
     <input :ref="'filedropzone'" type="file"
                @input="$emit('update:modelValue', $event.target.files[0])"
                class="hidden" :accept="accept"
                @change="setFile"/>
     <span class="text-[#2684D0] hover:text-[#344182] text-sm" :class="{'hidden':withFile}">
         Importar bolo
     </span>
      <div class="text-[#2684D0] hover:text-[#344182] text-sm" v-if="withFile">
            {{fileName}}
      </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue';
    import { valImageFile } from '../utils/index';

    export default defineComponent({
        props: {
            modelValue: {
                require:false
            },
            accept: {
                default:'.xlsx',
                require:false
            }
        },

        emits: ['update:modelValue', 'loadFile'],
        data(){
            return {
                withFile: false,
                urlImage:'/images/fileIcon.png',
                fileName:''
            }
        },
        methods: {
            focus() {
                this.$refs.filedropzone.focus()
            },
            selectFile(){
                this.$refs.filedropzone.click();
            },
            drop(event){
                this.error = false
                event.preventDefault();
                const file = event.dataTransfer.files[0];
                this.$emit('update:modelValue',file);
                setTimeout(()=> {
                    this.setFile()
                },200);
            },
            setFile: function(){
                if(this.modelValue){
                    this.withFile = true;
                    this.fileName = this.modelValue.name;
                    if(valImageFile(this.fileName)){
                        this.urlImage = URL.createObjectURL(this.modelValue);
                    }
                    this.$emit('loadFile', this.modelValue);
                }else{
                    this.withFile = false;
                }
            },

        }
    })
</script>
