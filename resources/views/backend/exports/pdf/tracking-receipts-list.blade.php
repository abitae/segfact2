@php
  use \Carbon\Carbon;
  $today = Carbon::now();

  function setTextDescription($fechaVencimiento, $today, $state) {
    if($state == 4) return 'Pagado';
    if($state == 5) return 'Anulado';
    if(!$fechaVencimiento) return '-';
    $expirationDate = Carbon::parse($fechaVencimiento);
    $diffInDays = Carbon::now()->diffInDays($expirationDate);

    if(!$diffInDays) {
      return 'Expira hoy';
    } else if($today->isBefore($expirationDate)) {
      return "Expira en $diffInDays días";
    } else if($today->isAfter($expirationDate)) {
      return "Expiró hace $diffInDays días";
    }
  }

  function setDiferenceInDays($dateExpired) {
    $diffInDays = Carbon::now()->diffInDays($dateExpired);
    $isBefore = $today->isBefore($dateExpired);
    $isBefore = $today->isAfter($dateExpired);
    return $diffInDays;
  }

  function setBackgroundColor($estadoDocumento, $fechaVencimiento, $today) {
    if($estadoDocumento == 'Girado') return "#adffad";
    if($estadoDocumento == 'Anulado') return "#d6d6d6";
    $expirationDate = Carbon::parse($fechaVencimiento);
    $diasPorVencer = Carbon::now()->diffInDays($expirationDate);
    $expirationCurrentDate = $today->isBefore($expirationDate);
    if($diasPorVencer == 0) return "#ffffad";
    if($expirationCurrentDate) return "#ffffad";
    return '#ffadad';
  }
  function setCentimos($money) {
    return "S/ ".number_format($money,2);
  }

  $sumTotal = collect($listTrackingReceipts)->reduce(function($acc, $item) {
    return $acc + $item->monto;
  },0);

  $sumDetraccion = collect($listTrackingReceipts)->reduce(function($acc, $item) {
    return $acc + $item->detraccion;
  },0);

  $sumRetencion = collect($listTrackingReceipts)->reduce(function($acc, $item) {
    return $acc + $item->retencion;
  },0);

  $sumMontoTotal = collect($listTrackingReceipts)->reduce(function($acc, $item) {
    return $acc + $item->montoTotal;
  },0);

@endphp
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Seguimiento comprobante</title>
  <style>
    * {
      font-size: 10px;
    }
    table {
      border-collapse: collapse;
      border: 1px solid #cfcfcf;
      width: 100%;
    }
    table tr th,
    table tr td {
      border-bottom: 1px solid #cfcfcf;
      padding-left: 5px;
    }
    .border-right {
      border-right: 1px solid #cfcfcf;
    }
    .text-center {
      text-align: center;
    }
    .text-right {
      text-align: right;
    }
  </style>
</head>
<body>
  <table>
    <thead>
      <tr>
        {{-- <th rowspan="2" class="border-right">N° F. Compra</th> --}}
        <th rowspan="2" class="border-right">Comprobante</th>
        <th rowspan="2" class="border-right">C.U. Ejec.</th>
        <th rowspan="2" class="border-right">N° Siaf</th>
        <th rowspan="2" class="border-right">Cliente</th>
        <th rowspan="2" class="border-right">Monto</th>
        <th rowspan="2" class="border-right">Detracción</th>
        <th rowspan="2" class="border-right">Retención</th>
        <th rowspan="2" class="border-right">Monto total</th>
        <th rowspan="2" class="border-right">Vendedor</th>
        <th rowspan="2" class="border-right">Estado</th>
        <th rowspan="2" class="border-right">Dif. en días</th>
        <th colspan="2" class="border-right">Fecha</th>
        <th rowspan="2" class="border-right">Observaciones</th>
      </tr>
      <tr>
        <th class="border-right">Emisión</th>
        <th class="border-right">Vencimiento</th>
      </tr>
    </thead>
    <tbody class="vertical-align-middle">
      @foreach ($listTrackingReceipts as $trackingReceipt)
        <tr>
          {{-- <td class="border-right text-center"> {{ $trackingReceipt->facturaCompra->codigoFactura }} </td> --}}
          <td class="border-right text-center"> {{ $trackingReceipt->facturaVenta->codigoFactura }} </td>
          <td class="border-right text-center"> {{ $trackingReceipt->codigoUnidadEjecutora }} </td>
          <td class="border-right text-center"> {{ $trackingReceipt->nroSiaf }} </td>
          <td class="border-right"> {{ $trackingReceipt->cliente->nroDocument }} - {{ strToLower($trackingReceipt->cliente->fullName) }} </td>
          {{-- <td class="border-right text-center"> S/ {{ $trackingReceipt->monto }} </td> --}}
          <td class="border-right text-right"> {{ $trackingReceipt->monto ? setCentimos($trackingReceipt->monto) : '-' }} </td>
          <td class="border-right text-right"> {{ $trackingReceipt->detraccion ? setCentimos($trackingReceipt->detraccion) : '-' }} </td>
          <td class="border-right text-right"> {{ $trackingReceipt->retencion ? setCentimos($trackingReceipt->retencion) : '-' }} </td>
          <td class="border-right text-right"> {{ $trackingReceipt->montoTotal ? setCentimos($trackingReceipt->montoTotal) : '-' }} </td>
          <td class="border-right"> {{ $trackingReceipt->usuario->name }} </td>
          <td class="border-right"> {{ $trackingReceipt->estadoDeDocumento->nombre }} </td>
          <td class="border-right"> {{ setTextDescription($trackingReceipt->fechaVencimiento, $today, $trackingReceipt->estadoDocumento) }} </td>
          <td class="border-right text-center"> {{ Carbon::parse($trackingReceipt->fechaEmision)->format('d-m-Y') }} </td>
          <td class="border-right text-center" style="background-color: {{setBackgroundColor($trackingReceipt->estadoDeDocumento->nombre, $trackingReceipt->fechaVencimiento, $today)}}"> {{ !$trackingReceipt->fechaVencimiento ? '-' : Carbon::parse($trackingReceipt->fechaVencimiento)->format('d-m-Y') }} </td>
          <td> {{ $trackingReceipt->actionesObservaciones }} </td>
        </tr>
      @endforeach
      <tr>
        <td colspan="4">&nbsp;</td>
        <td class="text-right">{{ setCentimos($sumTotal) }}</td>
        <td class="text-right">{{ setCentimos($sumDetraccion) }}</td>
        <td class="text-right">{{ setCentimos($sumRetencion) }}</td>
        <td class="text-right">{{ setCentimos($sumMontoTotal) }}</td>
        <td colspan="6">&nbsp;</td>
      </tr>
    </tbody>
  </table>
</body>
</html>
