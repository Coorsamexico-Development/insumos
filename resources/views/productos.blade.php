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
            <div style="margin-top: -10rem; color:#3B4C90;">
              <h1 style="display: flex; flex-direction:row; font-size:1.7rem">Productos / {{$categoria['nombre']}} </h1>
              <span style="background-color:#77B8E6; width:11.7rem; height:4px; position: absolute; margin-left:-3rem; margin-top:-1rem"></span>
            </div>
        </div>
        <div style="margin-top:3rem; width:100%;  margin: 2rem 6rem 6rem 0rem;"> <!--Productos-->
          <table style="width: 100%">
            <thead style="border-bottom: 1px solid;">
              <tr >
                <td style="font-weight: 100;padding-bottom:1rem;">Productos</td>
                <td style="font-weight: 100;padding-bottom:1rem;">Código</td>
              </tr>
            </thead>
            <tbody>
              <tr @foreach ($productos as $producto ) >
                  <td><p style="font-size:1rem;padding-bottom:1rem;">{{$producto['nombre']}}</p></td>
                  <td style="padding-bottom:1rem;">
                    <div style="width:6rem">
                      <?php 
                        echo $generator->getBarcode($producto['codigo'], $generator::TYPE_CODE_39);
                         '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($producto['codigo'], $generator::TYPE_CODE_39)) . '">';
                      ?> 
                    </div>
                  </td>
              </tr @endforeach>>
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
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: normal;
  src: url(https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,700;0,800;0,900;1,700;1,800&display=swap) format('truetype');
}
body 
{
  font-family: 'Montserrat';
}

</style>
