<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Lista de compras</title>
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
  </style>
</head>
<body>
  <table style="width: 100%">
    <thead>
      <tr>
        <th class="text-center">N° Factura</th>
        <th class="text-left" style="width: 200px">Proveedor</th>
        <th class="text-center">Tipo Comprobante</th>
        <th class="text-center">Soles/Dólares</th>
        <th class="text-center">M. Dólares</th>
        <th class="text-center">Tipo cambio</th>
        <th class="text-center">Sub Total</th>
        <th class="text-center">IGV</th>
        <th class="text-center">Monto total</th>
        <th class="text-center">Fecha emisión</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($shoppingList as $shopping)
        <tr>
          <td class="text-center"> <span>{{ $shopping->codigoFactura }}</span> </td>
          <td class="text-left text-capitalize"> {{ $shopping->proveedor->nroDocument }} - {{ $shopping->proveedor->fullName }} </td>
          <td class="text-center text-capitalize"> ({{ $shopping->tipoComprobante->codigo }}) {{ $shopping->tipoComprobante->nombre }} </td>
          <td class="text-center"> {{ $shopping->compraSolesDolares }} </td>
          <td class="text-center text-capitalize"> {{ $shopping->monto }} </td>
          <td class="text-center"> {{ $shopping->tipoDeCambio }} </td>
          <td class="text-center"> S/ {{ $shopping->montoVentaSoles }} </td>
          <td class="text-center"> S/ {{ $shopping->igv }} </td>
          <td class="text-center"> S/ {{ $shopping->montoTotal }} </td>
          <td class="text-center"> {{ \Carbon\Carbon::parse($shopping->fechaEmision)->format('d-m-Y') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

</body>
</html>
