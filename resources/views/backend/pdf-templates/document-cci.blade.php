@php
  function convertImageBase64($image, $type) {
  $slash = config('app.env') == 'local' ? '\\' : '/';
  $pathImage = public_path() .''.$slash.'backend/assets/template-images/'.$image.'_'."$type.png";
  $arrContextOptions = [
    'ssl' => [
      'verify_peer' => false,
      'verify_peer_name' => false,
    ],
  ];
  $path = $pathImage;
  $type = pathinfo($path, PATHINFO_EXTENSION);
  $data = file_get_contents($path, false, stream_context_create($arrContextOptions));
  $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
  return $base64;
  }
@endphp
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/logo-sm-dark.png') }}">
    <title>{{ $nameFile }}</title>

    <style>
      @font-face Garamond {
        font-family: 'Garamond';
        src: "{{ asset('fonts/garamond/MotionPicture_PersonalUseOnly.ttf') }}";
      }

      body {
        font-family: 'Garamond', Arial, Helvetica, sans-serif;
        font-size: 15px;
      }

      html {
        margin: 0;
      }

      .title {
        font-weight: bold;
        text-decoration: underline;
        text-align: center;
      }
      header {
        position: fixed;
        top: 0;
      }
      footer {
        position: fixed;
        bottom: 0px;
        height: 80px;
      }
      .page-break {
        page-break-after: always;
      }
    </style>
  </head>

  <body>
    <header>
      <img src="{{ convertImageBase64($sale->enterprise->template, 'header') }}" style="width: 100%;" alt="Image Header">
      <div>&nbsp;</div>
    </header>
    <table>
      <tbody>
        <tr>
          <td>
            <div style="height: 150px;"></div>
          </td>
        </tr>
        <tr>
          <td style="width: 50px">&nbsp;</td>
          <td>
            <h4 class="title">CARTA DE AUTORIZACIÓN ABONO EN CCI</h4>
          </td>
          <td style="width: 50px">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td style="width: 50px"></td>
          <td style="height: 50px"><strong>Señor(es):</strong>
            <dd>{{ $sale->cliente->fullName }}</dd>
          </td>
          <td style="width: 50px"></td>
        </tr>
        <tr>
          <td style="width: 50px"></td>
          <td><strong>Asunto:</strong>
            <dd>ABONO EN CUENTA CCI</dd>
          </td>
          <td style="width: 50px"></td>
        </tr>
        <tr> <td colspan="3"> &nbsp; </td> </tr>
        @if ($sale->nroPucharseOrder)
        <tr>
          <td style="width: 50px"></td>
          <td>REF. ORDEN DE COMPRA NRO {{$sale->nroPucharseOrder}}</td>
          <td style="width: 50px"></td>
        </tr>
        <tr> <td colspan="3"> &nbsp; </td> </tr>
        @endif
        <tr>
          <td style="width: 50px"></td>
          <td style="text-align: justify">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Yo, <strong>{{$sale->enterprise->representative_name}}</strong> representante legal de la empresa <strong>{{$sale->enterprise->fullName}}</strong> con RUC N° {{$sale->enterprise->nroDocument}} y domicilio legal en el {{$sale->enterprise->address}} me dirijo a Uds., para manifestar mi número de Cuenta Interbancario (CCI) de Empresa <strong>{{$sale->enterprise->fullName}}</strong> que represento es el N° <strong>{{$sale->enterprise->nro_cuenta_interbancaria}}</strong>
            @if ($sale->enterprise->nro_cuenta_detraction)
            , por otro lado la cuenta de detracciones es N° {{$sale->enterprise->nro_cuenta_detraction}}
            @endif
            ; agradeciéndole se sirva disponer lo conveniente, de manera que los pagos a nombre de mi presentada sean abonados en la cuenta que corresponde a la indicada CCI en el Banco de la Nación.
          </td>
          <td style="width: 50px"></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
          <td style="width: 50px"></td>
          <td style="text-align: justify"> Asimismo, dejo constancia que la factura emitida por mi representada, quedará cancelada para todos sus afectos, mediante la sola acreditación del importe de la referida factura a favor de la cuenta de la entidad bancaria a que se refiere el primer párrafo de la presente.
          </td>
          <td style="width: 50px"></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr><tr><td colspan="3">&nbsp;</td></tr>
        <tr>
          <td style="width: 50px"></td>
          {{-- ->formatLocalized('%A %d %B %Y') --}}
          <td style="text-align: right; text-transform: capitalize;">Huancayo, {{ \Carbon\Carbon::parse($sale->warrantyStartDate)->isoFormat('dddd D MMMM YYYY') }}</td>
          {{-- <td style="text-align: right; text-transform: capitalize;">Huancayo, {{\Carbon\Carbon::parse($sale->warrantyStartDate)->format('d/m/Y')}}</td> --}}
          <td style="width: 50px"></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr><tr><td colspan="3">&nbsp;</td></tr>
        <tr>
          <td style="width: 50px"></td>
          <td style="text-align: center">Atentamente,</td>
          <td style="width: 50px"></td>
        </tr>
      </tbody>
    </table>
    <footer>
      <img src="{{ convertImageBase64($sale->enterprise->template,'footer') }}" style="width: 100%" alt="Image Footer">
    </footer>
  </body>

</html>
