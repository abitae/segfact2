@php
  function parseCentimos($cash) {
    return number_format($cash,2);
  }
@endphp
<table>
  <thead>
    <tr>
      <th>Comprobante</th>
      <th>Sucursal</th>
      <th>Cliente</th>
      <th>Vendedor</th>
      <th>Tipo Comprobante</th>
      <th>Compra</th>
      <th>Sub total</th>
      <th>IGV</th>
      <th>Importe</th>
      <th>Fecha emisión</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($saleList as $sale)
      <tr>
        <td> {{ $sale->codigoFactura }} </td>
        <td> {{ $sale->branchOffice->name }} </td>
        <td> {{ $sale->cliente->nroDocument }} - {{ $sale->cliente->fullName }} </td>
        <td> {{ $sale->vendedor->name }} </td>
        <td> {{ $sale->tipoComprobante->nombre }} </td>
        <td> {{ $sale->compraSolesDolares == 'PEN' ? 'Soles' : 'Dólares' }} </td>
        <td> S/ {{ parseCentimos($sale->monto) }} </td>
        <td> S/ {{ parseCentimos($sale->igv) }} </td>
        <td> S/ {{ parseCentimos($sale->montoTotal) }} </td>
        <td> {{ \Carbon\Carbon::parse($sale->fechaEmision)->format('d-m-Y') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
