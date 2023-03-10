@php
  function parseCentimos($cash) {
    return number_format($cash,2);
  }
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Lista de ventas</title>
  <style>
    * {
      font-size: 11px;
    }
    table {
      border: 1px solid #e6e6e6;
      border-collapse: collapse;
    }
    tr th,
    tr td {
      line-height: 1;
      border-bottom: 1px solid #e6e6e6;
      border-right: 1px solid #e6e6e6;
    }
    .text-center {
      text-align: center;
    }
    .text-capitalize {
      text-transform: capitalize;
    }
    .text-left {
      text-align: left;
    }
    .text-right {
      text-align: right;
    }
  </style>
</head>
<body>
  <table style="width: 100%">
    <thead>
      <tr>
        <th class="text-center">Comprobante</th>
        <th class="text-left">Sucursal</th>
        <th class="text-left" style="width: 200px">Cliente</th>
        <th class="text-center">Vendedor</th>
        <th class="text-center">Tipo Comprobante</th>
        <th class="text-center">Compra</th>
        <th class="text-center">Sub total</th>
        <th class="text-center">Igv</th>
        <th class="text-center">Importe</th>
        <th class="text-center">Fecha emisión</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($saleList as $sale)
        <tr>
          <td class="text-center"> {{ $sale->codigoFactura }} </td>
          <td class="text-left"> {{ $sale->branchOffice->name }} </td>
          <td class="text-left text-capitalize" style="font-size: 9px"> {{ $sale->cliente->nroDocument }} - {{ $sale->cliente->fullName }} </td>
          <td class="text-center text-capitalize"> {{ $sale->vendedor->name }} </td>
          <td class="text-center">{{ $sale->tipoComprobante->nombre }} </td>
          <td class="text-center text-capitalize"> {{ $sale->compraSolesDolares == 'PEN' ? 'Soles' : 'Dólares' }} </td>
          <td class="text-right"> S/ {{ parseCentimos($sale->monto) }} </td>
          <td class="text-right"> S/ {{ parseCentimos($sale->igv) }} </td>
          <td class="text-right"> S/ {{ parseCentimos($sale->montoTotal) }} </td>
          <td class="text-center"> {{ \Carbon\Carbon::parse($sale->fechaEmision)->format('d-m-Y') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

</body>
</html>
