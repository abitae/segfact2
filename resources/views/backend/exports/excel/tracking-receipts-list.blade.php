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

  function sumTotal($collection) {
    return collect($collection)->reduce(function($acc, $item) {
      if($item->estadoDocumento == 5) return $acc;
      return $acc + $item->monto;
    },0);
  }

  function sumDetraccion($collection) {
    return collect($collection)->reduce(function($acc, $item) {
      if($item->estadoDocumento == 5) return $acc;
      return $acc + $item->detraccion;
    },0);
  }

  function sumRetencion($collection) {
    return collect($collection)->reduce(function($acc, $item) {
      if($item->estadoDocumento == 5) return $acc;
      return $acc + $item->retencion;
    },0);
  }

  function sumMontoTotal($collection) {
    return collect($collection)->reduce(function($acc, $item) {
      if($item->estadoDocumento == 5) return $acc;
      return $acc + $item->montoTotal;
    },0);
  }
@endphp

@foreach ($listTrackingReceipts as $key => $trackingReceipt)
  <table>
    {{-- <thead>
      <tr>
        <th>Empresa</th>
        <th>Comprobante</th>
        <th>Código Unidad Ejecutora</th>
        <th>N° Siaf</th>
        <th>Cliente</th>
        <th>Monto</th>
        <th>Detracción</th>
        <th>Retención</th>
        <th>Monto total</th>
        <th>Vendedor</th>
        <th>Estado</th>
        <th>Dif. en días</th>
        <th>Fecha emisión</th>
        <th>Fecha vencimiento</th>
        <th>Observaciones</th>
      </tr>
    </thead> --}}
    <tbody class="vertical-align-middle">
      <tr>
        <th colspan="15" class="text-right">
          {{ $key }}
        </th>
      </tr>
      <tr>
        {{-- <th>Empresa</th> --}}
        <th>Comprobante</th>
        <th>Código Unidad Ejecutora</th>
        <th>N° Siaf</th>
        <th>Cliente</th>
        <th>Monto</th>
        <th>Detracción</th>
        <th>Retención</th>
        <th>Monto total</th>
        <th>Vendedor</th>
        <th>Estado</th>
        <th>Dif. en días</th>
        <th>Fecha emisión</th>
        <th>Fecha vencimiento</th>
        <th>Observaciones</th>
      </tr>
      @foreach ($trackingReceipt as $value)
      <tr>
        {{-- <td> {{ $value->empresa->fullName }} </td> --}}
        <td> {{ $value->facturaVenta->codigoFactura }} </td>
        <td> {{ $value->codigoUnidadEjecutora }} </td>
        <td> {{ $value->nroSiaf }} </td>
        <td> {{ $value->cliente->nroDocument }} - {{ $value->cliente->fullName }} </td>
        <td> {{ $value->monto ? setCentimos($value->monto) : '-' }} </td>
        <td> {{ $value->detraccion ? setCentimos($value->detraccion) : '-' }} </td>
        <td> {{ $value->retencion ? setCentimos($value->retencion) : '-' }} </td>
        <td> {{ $value->montoTotal ? setCentimos($value->montoTotal) : '-' }} </td>
        <td> {{ $value->usuario->name }} </td>
        <td> {{ $value->estadoDeDocumento->nombre }} </td>
        <td> {{ setTextDescription($value->fechaVencimiento, $today, $value->estadoDocumento) }} </td>
        <td> {{ \Carbon\Carbon::parse($value->fechaEmision)->format('d-m-Y') }} </td>
        <td style="background-color: {{setBackgroundColor($value->estadoDeDocumento->nombre, $value->fechaVencimiento, $today)}}">
          {{ !$value->fechaVencimiento ? '-' : Carbon::parse($value->fechaVencimiento)->format('d-m-Y') }}
        </td>
        <td> {{ $value->actionesObservaciones }} </td>
      </tr>
      @endforeach
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="text-right">{{ setCentimos( sumTotal($trackingReceipt) ) }}</td>
        <td class="text-right">{{ setCentimos( sumDetraccion($trackingReceipt) ) }} </td>
        <td class="text-right">{{ setCentimos( sumRetencion($trackingReceipt) ) }} </td>
        <td class="text-right">{{ setCentimos( sumMontoTotal($trackingReceipt) ) }} </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>

    </tbody>
  </table>
@endforeach
