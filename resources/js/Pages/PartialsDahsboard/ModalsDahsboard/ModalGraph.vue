<script setup>
 import DialogModal from '@/Components/DialogModal.vue';
 import * as am4core from "@amcharts/amcharts4/core";
 import * as am4charts from "@amcharts/amcharts4/charts";
 //import am4themes_animated from "@amcharts/amcharts4/themes/animated";
 import { ref, onBeforeUpdate, onUpdated, watch } from 'vue';
 //Modales
 import ModalSalidasByDt from './ModalSalidasByDt.vue';

import ButtonCalendar from '@/Components/ButtonCalendar.vue'

 const emit = defineEmits(["close"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      salidasForModal:Object,
      clientes:Object,
      elementsCateProducto:Object
  });

  
  const close = () =>
  { 
     emit('close');
  };

  //Funcion para modales
  const modalSalidasByDT = ref(false);
  const newSalidas = ref([]);
  let  busqueda = ref('');

  const closeModalSalidasByDt = () => 
  {
    modalSalidasByDT.value = false;
  }

  const activarLegends = ref(false);

  //am4core.useTheme(am4themes_animated);
  let chart = null;
  let allseries = [];
  onUpdated(() => 
  {
    try 
    {
      // Themes begin
       //am4core.useTheme(am4themes_animated);
       // Themes end
       
       // Create chart instance
       chart = am4core.create("chartdiv", am4charts.XYChart);
       
       
       // Add data
       //console.log(props.salidasForModal)
       chart.data = props.salidasForModal
       //chart.cursor = new am4charts.XYCursor();
       //Exportaciones
       chart.exporting.menu = new am4core.ExportMenu();
       chart.exporting.menu.align = "left";
       chart.exporting.menu.verticalAlign = "top";
       /*
       [{
         "year": "2016",
         "europe": 2.5,
         "namerica": 2.5,
         "asia": 2.1,
         "lamerica": 0.3,
         "meast": 0.2,
         "africa": 0.1
       }, {
         "year": "2017",
         "europe": 2.6,
         "namerica": 2.7,
         "asia": 2.2,
         "lamerica": 0.3,
         "meast": 0.3,
         "africa": 0.1
       }, {
         "year": "2018",
         "europe": 2.8,
         "namerica": 2.9,
         "asia": 2.4,
         "lamerica": 0.3,
         "meast": 0.3,
         "africa": 0.1
       }];*/
       
       // Create axes
       var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
       categoryAxis.dataFields.category = "fecha";
       categoryAxis.renderer.grid.template.location = 0;
       
       
       var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
       valueAxis.renderer.inside = true;
       valueAxis.renderer.labels.template.disabled = true;
       valueAxis.min = 0;
       
       // Create series
       function createSeries(field, name) 
       {
         
         // Set up series
         var series = chart.series.push(new am4charts.ColumnSeries());
         series.name = name;
         series.dataFields.valueY = field;
         series.dataFields.categoryX = "fecha";
         series.sequencedInterpolation = true;
         
         // Make it stacked
         series.stacked = true;
         
         // Configure columns
         series.columns.template.width = am4core.percent(60);
         series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";

        //events
        series.columns.template.events.on("hit", function(ev) 
         {
            //console.log(ev.target.dataItem.component.name);
            //console.log(ev.target.dataItem.categories.categoryX)
            axios.get(route('salidasByClienteDate',
            {cliente:ev.target.dataItem.component.name, 
             fecha:ev.target.dataItem.categories.categoryX,
             producto_id:props.elementsCateProducto.producto_id,
             categoria_id:props.elementsCateProducto.categoria_id
            }))
            .then(response => 
            {
              //console.log(response.data)
              newSalidas.value = response.data;
              modalSalidasByDT.value = true;
            })
            .catch(err => 
            {
              console.log(err)
            })
         }, this);

         // Add label
         var labelBullet = series.bullets.push(new am4charts.LabelBullet());
         labelBullet.label.text = "{valueY}";
         labelBullet.locationY = 0.5;
         labelBullet.label.hideOversized = true;
         
         return series;
       }



       createSeries("DESACTIVAR TODOS", "DESACTIVAR TODOS");
       for (let index = 0; index < props.clientes.length; index++) 
       {
         let cliente = props.clientes[index];
         //console.log(cliente)
         if(cliente.nombre !== '¿')
         {
          allseries.push( createSeries(cliente.nombre, cliente.nombre));
         }
       }


     
       // Legend
       chart.legend = new am4charts.Legend();

       chart.legend.itemContainers.template.events.on("hit", function(ev) 
       {
         console.log(ev.target._dataItem.dataContext.dataFields.valueY) //tenemos el legend picado
         //hay qu apagar los legends
         if(ev.target._dataItem.dataContext.dataFields.valueY == 'DESACTIVAR TODOS')
         {
            activarLegends.value = !activarLegends.value
            for (let index = 0; index < allseries.length; index++) 
            {
              const element = allseries[index];
              if(!activarLegends.value)
              {
                element.appear();
              }
              else
              {
                element.hide();
              }
            }
         }
       });
       chart.legend.maxHeight = 500;
       chart.legend.scrollable = true;
       chart.legend.position= "left";

    } 
    catch (error) 
    {
      
    }
  })

  watch(busqueda, (newBusqueda) => 
  {
     //console.log(newBusqueda)
     //console.log(chart)
     //console.log(allseries)
     for (let index = 0; index < allseries.length; index++) 
     {
      const serie = allseries[index];
      if(serie.dataFields.valueY.match(newBusqueda))
      {
        serie.appear();
      }
      else
      {
        serie.hide();
      }
     }
  });

 //Fechas
let date = ref({
    month: new Date().getMonth(),
    year: new Date().getFullYear(),
});
</script>
<template>
      <DialogModal :maxWidth="'6xl'"  :show="show" @close="close()">
       <template #title> 
         Salidas
        <button class="bg-[#C3C4CE] float-right rounded-full py-2 px-2" @click="close()">
           <svg id="Grupo_394" data-name="Grupo 394" xmlns="http://www.w3.org/2000/svg" width="11.344" height="11.344" viewBox="0 0 11.344 11.344">
            <path id="Trazado_1910" data-name="Trazado 1910" d="M71.01,81.695a.584.584,0,0,1-.412-1L80.774,70.521a.584.584,0,0,1,.825.825L71.423,81.523A.585.585,0,0,1,71.01,81.695Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
            <path id="Trazado_1911" data-name="Trazado 1911" d="M81.187,81.695a.579.579,0,0,1-.412-.171L70.6,71.347a.584.584,0,0,1,.825-.825L81.6,80.7a.584.584,0,0,1-.412,1Z" transform="translate(-70.427 -70.351)" fill="#9b9ca0"/>
          </svg>
         </button>
       </template> 
       <template #content>
        <div class="flex flex-row">
          <input v-model="busqueda" placeholder="Buscar" class=" px-3 py-2 leading-tight text-gray-700 border border-none rounded shadow appearance-none focus:outline-none focus:shadow-outline bg-[#F6F6F9]"  />
          <ButtonCalendar :month="date.month" :year="date.year" />
        </div>
        <div id="chartdiv"></div>
        <ModalSalidasByDt :show="modalSalidasByDT" @close="closeModalSalidasByDt()" :salidas="newSalidas" /> 
       </template>
    </DialogModal>
</template>
<style>
#chartdiv {
  width: 100%;
  height: 800px;
}

.amcharts-amexport-item {
  border: 2px solid #777;
}

.amcharts-amexport-top .amcharts-amexport-item > .amcharts-amexport-menu {
  top: -3px!important;
  left: 2px
}
</style>