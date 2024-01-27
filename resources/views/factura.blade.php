<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
          Productos de plataforma de insumos
        </title>
      </head>
    <body >
        <div class="" style="width: 18rem;">
            <div class="bgSection">
                <img style="width: 237%;" src="https://storage.googleapis.com/coorsamexico_coordinacion_destino/img/banner_1%40100x.png" />
            </div>
            <div style="margin-top: -10rem; color:#3B4C90; position: absolute;" id="title">
              <h1 style="display: flex; flex-direction:row; font-size:1.5rem; font-weigth:100">
                {{  substr($entrada['fecha'], 8,2); }} de 
                @if ((substr($entrada['fecha'], 5,2)) == '01' )
                    Enero
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '02' )
                  Febrero
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '03' )
                  Marzo
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '04' )
                  Abril
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '05' )
                  Mayo
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '06' )
                  Junio
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '07' )
                  Julio
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '08' )
                  Agosto
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '09' )
                  Septiembre
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '10' )
                  Octubre
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '11' )
                  Noviembre
                @endif
                @if ((substr($entrada['fecha'], 5,2)) == '12' )
                  Diciembre
                @endif
                del {{substr($entrada['fecha'], 0,4)}}
              </h1>
              <h2>Factura:{{$entrada['factura']}}</h2>
           </div>
        </div>
        <div style="margin-top:3rem; width:100%;  margin: 2rem 6rem 6rem 0rem;font-family: 'Montserrat', sans-serif;"> <!--Datos-->
           <table style="width: 100%; font-family: 'Montserrat', sans-serif;">
             <thead style="border-bottom:solid 1px #c3c3c3; font-family: 'Montserrat', sans-serif;">
                <tr style=" text-align: center; font-family: 'Montserrat', sans-serif;  ">
                    <td style="font-weight: 100;padding-bottom:0.2rem; text-aling:center; font-family: 'Montserrat', sans-serif;" id="td">
                        <p style="font-family: 'Montserrat', sans-serif; font-size:1.5rem">
                            Producto
                        </p> 
                    </td>
                    <td style="font-weight: 100;padding-bottom:0.2rem; text-aling:center;font-size:1.5rem" id="td">Cantidad</td>
                </tr>
             </thead>
             <tbody>
                 <tr style=" text-align: center;">
                    <td id="td" style="font-size:1rem;padding-bottom:0.2rem;padding-top:0.2rem; text-aling:center; text-transform:uppercase">{{$entrada['producto']}}</td>
                    <td id="td" style="font-size:1rem;padding-bottom:0.2rem;padding-top:0.2rem;text-aling:center;">{{$entrada['cantidad']}}</td>
                 </tr>
             </tbody>
           </table>
           <table style="width: 100%; font-family: 'Montserrat', sans-serif; margin-top:15rem"><!--Firmas -->
            <tr>
                <td style="text-align: center;">
                    <span style="background-color:#77B8E6; width:13.5rem; height:1px; position: absolute; margin-left:5rem; margin-top:0.1rem"></span>
                </td>
                <td style="text-align: center;">
                    <span style="background-color:#77B8E6; width:13.5rem; height:1px; position: absolute; margin-left:3rem; margin-top:0.1rem"></span>
                </td>
            </tr>
            <tr>
                <td style=" text-align: center;">
                    <h3 style="color:#3B4C90; font-family: 'Montserrat', sans-serif;">Entregado</h3> 
                </td>
                <td style=" text-align: center;"">
                    <h3 style="color:#3B4C90; font-family: 'Montserrat', sans-serif;">Recibido</h3>
                </td>
            </tr>
           </table>
        </div>
    </body>
</html>
<style>
  .bgSection{
    margin-top: -3rem;
    margin-left: -3rem;
    margin-bottom: 3rem;
  },
  @font-face {
      font-family: 'Montserrat'
      font-style: normal;
      font-variant: normal;
      src: url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");
    }

   #title
   {
     font-family: 'Montserrat', sans-serif;
   }

   #table
   {
    font-family: 'Montserrat', sans-serif;
   }

   #td
   {
    font-family: 'Montserrat', sans-serif;
   }

</style>
