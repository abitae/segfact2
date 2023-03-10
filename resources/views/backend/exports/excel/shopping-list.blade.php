<table>
  <thead>
    <tr>
      <th>N° Factura</th>
      <th>Proveedor</th>
      <th>Tipo Comprobante</th>
      <th>Soles/Dólares</th>
      <th>M. Dólares</th>
      <th>Tipo cambio</th>
      <th>Sub Total</th>
      <th>IGV</th>
      <th>Monto total</th>
      <th>Fecha emisión</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($shoppingList as $shopping)
      <tr>
        <td>
          <span>{{ $shopping->codigoFactura }}</span>
        </td>
        <td>
          {{ $shopping->proveedor->nroDocument }} - {{ $shopping->proveedor->fullName }}
        </td>
        <td> ({{ $shopping->tipoComprobante->codigo }}) {{ $shopping->tipoComprobante->nombre }} </td>
        <td> {{ $shopping->compraSolesDolares }} </td>
        <td> {{ $shopping->monto }} </td>
        <td> {{ $shopping->tipoDeCambio }} </td>
        <td> S/ {{ $shopping->montoVentaSoles }} </td>
        <td> S/ {{ $shopping->igv }} </td>
        <td> S/ {{ $shopping->montoTotal }} </td>
        <td> {{ \Carbon\Carbon::parse($shopping->fechaEmision)->format('d-m-Y') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
