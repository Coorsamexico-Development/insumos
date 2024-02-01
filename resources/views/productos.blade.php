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
        <?php 
         $generator = new Picqer\Barcode\BarcodeGeneratorHTML();   
         ?>
        <div class="" style="width: 18rem;">
            <div class="bgSection">
                <img style="width: 237%;" src="https://storage.googleapis.com/coorsamexico_coordinacion_destino/img/banner_1%40100x.png" />
            </div>
            <div style="margin-top: -10rem; color:#3B4C90; position: absolute;" id="title">
              <h1 style="display: flex; flex-direction:row; font-size:2rem;">Productos / {{$categoria['nombre']}} </h1>
              <span style="background-color:#77B8E6; width:13.5rem; height:4px; position: absolute; margin-left:-3rem; margin-top:0.1rem"></span>
            </div>
        </div>
        <div style="margin-top:3rem; width:100%;  margin: 2rem 6rem 6rem 0rem;"> <!--Productos-->
          <table style="width: 100%" id="table">
            <thead style="border-bottom: 1px solid;">
              <tr >
                <td style="font-weight: 100;padding-bottom:1rem;" id="td">Productos</td>
                <td style="font-weight: 100;padding-bottom:1rem;" id="td">Código</td>
              </tr>
            </thead>
            <tbody>
              @foreach ($productos as $producto ) 
              <tr >
                  <td>
                    <p style="font-size:1rem;padding-bottom:1rem;text-transform:uppercase;">{{$producto['nombre']}}</p>
                    <p style="font-size:1rem;padding-bottom:1rem;text-transform:uppercase;">{{$producto['codigo']}}</p>
                  </td>
                  <td style="padding-bottom:1rem;">
                    <div style="width:6rem">
                      <?php 
                        echo $generator->getBarcode($producto['codigo'], $generator::TYPE_CODE_39);
                         '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($producto['codigo'], $generator::TYPE_CODE_39)) . '">';
                      ?> 
                    </div>
                  </td>
              </tr>
              @endforeach
            </tbody>
          </table>
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
