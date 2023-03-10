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

  function PeriodToString($time) {
    $array = [
      'years' => 'años',
      'months' => 'meses',
      'weeks' => 'semanas',
      'days' => 'días'
    ];
    return $array[$time];
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

    .content-series {
      line-height: 1;
    }

    .content-series span {
      display: block;
    }
    .border tr td {
      border: 1px solid rgb(194, 194, 194);
      padding: 2px;
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
      <tr> <td> <div style="height: 150px;"></div> </td> </tr>
      <tr>
        <td style="width: 50px">&nbsp;</td>
        <td>
          <h4 class="title">CARTA DE GARANTÍA COMERCIAL</h4>
        </td>
        <td style="width: 50px">&nbsp;</td>
      </tr>
      <tr>
        <td style="width: 50px"></td>
        <td style="height: 50px"><strong>Señor(es):</strong> <dd>{{ $sale->cliente->fullName }}</td>
        <td style="width: 50px"></td>
      </tr>
      <tr>
        <td style="width: 50px"></td>
        <td><strong>Asunto:</strong>
          <dd>CARTA DE GARANTÍA</dd>
        </td>
        <td style="width: 50px"></td>
      </tr>
      <tr>
        <td style="width: 50px"></td>
        <td>De nuestra consideración:</td>
        <td style="width: 50px"></td>
      </tr>
      <tr><td colspan="3"></td></tr>
      <tr>
        <td style="width: 50px"></td>
        <td style="text-align: justify;">
          El que suscribe, {{$sale->enterprise->representative_name}} representante legal de la empresa <strong>{{$sale->enterprise->fullName}}</strong>
          con DNI N° {{$sale->enterprise->representative_dni}} y RUC N° {{$sale->enterprise->nroDocument}} y domicilio legal en el {{$sale->enterprise->address}}.
          DECLARO BAJO JURAMENTO, que los bienes correspondientes a la orden de compra N° {{ $sale->nroPucharseOrder ?? '-' }} de la fecha
          {{ \Carbon\Carbon::parse($sale->warrantyStartDate)->isoFormat('dddd D MMMM YYYY') }}, cuenta con la garantía de {{ str_pad($sale->warrantyPeriodQuantity,2,'0',STR_PAD_LEFT) }}
          {{ PeriodToString($sale->warrantyPeriod) }} contados a partir de recibida la conformidad de la entrega de los bienes <br> Para lo cual describo los bienes incluidos en los documentos mencionados:
        </td>
        <td style="width: 50px"></td>
      </tr>
      <tr>
        <td style="width: 50px"></td>
        <td style="text-align: right; text-transform: capitalize;">Huancayo, {{ \Carbon\Carbon::parse($sale->warrantyStartDate)->isoFormat('dddd D MMMM YYYY') }}</td>
        {{-- <td style="text-align: right; text-transform: capitalize;">Huancayo, {{\Carbon\Carbon::parse($sale->warrantyStartDate)->format('d/m/Y')}}</td> --}}
        <td style="width: 50px"></td>
      </tr>
      <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td style="width: 50px"></td>
        <td>
          <table class="border" style="border: 1px solid gray; font-size: 11px; border-collapse: collapse; width: 100%">
            <tbody>
              <tr>
                <td style="text-align: center;">
                  <strong>CANT.</strong>
                </td>
                <td style="text-align: center;">
                  <strong>Descripción</strong>
                </td>
                <td style="text-align: center;">
                  <strong>SERIE(S)</strong>
                </td>
              </tr>
              @foreach ($sale->ventaDetalle as $detalle)
                <tr>
                  <td style="width: 30px; text-align: center;"> {{ str_pad($detalle->cantidad,2,'0',STR_PAD_LEFT) }} </td>
                  <td>{{$detalle->descripcion}}</td>
                  <td style="width: 150px; text-align: center;" class="content-series">
                    @forelse ($detalle->listSeries as $list)
                      <span>{{$list->serie->nroSerie}}</span>
                    @empty
                      <span> - </span>
                    @endforelse
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </td>
        <td style="width: 50px"></td>
      </tr>
    </tbody>
  </table>

  <footer>
    <img src="{{ convertImageBase64($sale->enterprise->template,'footer') }}" style="width: 100%" alt="Image Footer">
  </footer>

</body>
</html>
