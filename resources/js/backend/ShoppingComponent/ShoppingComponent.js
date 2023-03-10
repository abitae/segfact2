import Axios from "axios"
import {
  listAll,
  listAllActives,
  showReloadWindowAlert,
  normalizeLaravelErrorList,
  showAlertProccessSuccess,
  showErrorUpdateAlert,
  generateClipboard,
  can
} from "../Service"
import ListErrorsComponent from '../ListErrorsComponent.vue'
import LoaderComponent from '../LoaderComponent.vue'
import moment from "moment"
moment.locale('es-US')
export default {
  props: [
    'canView',
    'canCreate',
    'canEdit',
    'canUpdateStatus',
    'canPrintReport',
  ],
  components: {
    ListErrorsComponent,
    LoaderComponent
  },
  data() {
    return {
      modalTitle: '',

      id: 0,
      codigoFactura: '',
      idCliente: '',
      descripcionBienServicio: '',
      fechaEmision: '',
      idTipoComprobante: 1,
      monto: '',
      tipoDeCambio: '',
      montoVentaSoles: '',
      montoVentaDolares: '',
      igv: '',
      montoTotal: '',

      nroDocumentSupplier: '', //20541528092
      supplier: {},
      compraSolesDolares: '',
      listTypeDocuments: [],

      isSearchSupplier: false,
      isFormBeingSent: false,

      listDataShopping: {},
      isLoading: true,


      codigoFacturaSearch: '',
      nroDocumentSupplierSearch: '',
      idTipoComprobanteSearch: '',
      compraSolesDolaresSearch: '',
      fechaEmisionSearch: '',

      listUsers: [],
      idUser: '',

      listSeries: [],
      nroSerie: '',
      listSeriesInProccess: [],
      listNewSeries: [],

      shoppingExportExcel: 'shopping-export-excel',
      shoppingExportPdf: 'shopping-export-pdf',

      tipoCambioSunat: {},
      listDataSeries: [],

      detail: [],

      can,

      shippingInSolesDolares: '',
    }
  },
  mounted() {
    this.getExchangeRateSunat()
    this.getListTypeDocuments()
    this.setDateEmision()
    this.getListDataShopping()
    this.getListUsers()
    this.getListSeries()
  },
  filters: {
    formatDate(date) {
      return moment(date).format('DD-MM-YYYY')
    }
  },
  methods: {
    hideModalDetail() {
      $("#modal-detail").modal('hide')
    },
    showDetails(detail, solesDolares) {
      $("#modal-detail").modal('show')
      this.detail = detail
      this.shippingInSolesDolares = solesDolares
    },
    serieHandleSelected(serie) {
      if(!this.id) return

      if(!this.listSeries.includes(serie.id)) {
        this.listNewSeries.push(serie.id)
      }
      console.log(this.listNewSeries)
    },
    async deselectedOption(serie){
      if(!this.id) return

      const indexNewSerie = this.listNewSeries.findIndex(item => item == serie.id)
      if(!this.listSeriesInProccess.includes(serie) && indexNewSerie != -1) {
        this.listNewSeries.splice(indexNewSerie,1);
        return
      }

      const { isConfirmed } = await this.Sweet.fire({
        icon: 'question',
        title: '¿Desea eliminar la serie?',
        showCancelButton: true,
        confirmButtonText: '¡Si. eliminar!',
        cancelButtonText: 'Cancelar'
      })

      if (!isConfirmed) {
        this.listSeries.push(serie.id)
        return
      }

      const { id } = this.listSeriesInProccess.find( item => item.idSerie == serie.id)
      try {
        await Axios.post('shopping-serie-delete', { id })
        showAlertProccessSuccess()
      } catch (error) {
        showErrorUpdateAlert()
      }
    },
    addSerieToList() {
      const nroSerie = this.nroSerie
      this.listSeries.push({nroSerie})
      this.nroSerie = ''
      $('#nroSerie').focus()
    },
    copiedAtClipboard(nroDocument) {
      generateClipboard(nroDocument)
    },
    calculateOperationInDolares() {
      const monto = this.monto
      const tipoDeCambio = this.tipoDeCambio
      if(!monto.length || isNaN(tipoDeCambio)) return false
      const montoTolal = Number(monto * tipoDeCambio).toFixed(2)
      this.montoVentaSoles = Number(montoTolal / 1.18).toFixed(2)
      this.igv = Number(this.montoVentaSoles * 0.18).toFixed(2)
      this.montoTotal = montoTolal
    },
    calculateOperationInSoles() {
      const montoVentaSoles = this.montoVentaSoles
      if(!montoVentaSoles.length) return false
      this.igv = Number(montoVentaSoles * 0.18).toFixed(2)
      this.montoTotal = Number(montoVentaSoles * 1.18).toFixed(2)
    },
    cleanForm() {
      this.id = 0
      this.codigoFactura = ''
      this.idCliente = ''
      this.descripcionBienServicio = ''
      this.fechaEmision = ''
      this.idTipoComprobante = 1
      this.monto = ''
      this.montoVentaSoles = ''
      this.montoVentaDolares = ''
      this.igv = ''
      this.montoTotal = ''
      this.nroDocumentSupplier = ''
      this.supplier = {}
      this.compraSolesDolares = ''
      this.idUser = ''
      this.listSeriesInProccess = []
      this.listSeries = []
      this.listNewSeries = []
      this.setDateEmision()
    },
    compraSolesDolaresHandleChange() {
      this.monto = ''
      this.montoVentaSoles = ''
      if(this.compraSolesDolares == 'dolares') {
        this.tipoDeCambio = this.tipoCambioSunat.tipoCambioVenta
      } else {
        this.tipoDeCambio = ''
      }
      this.igv = ''
      this.montoTotal = ''
    },
    async createOrUpdate() {
      this.isFormBeingSent = true
      const formData = this.dataToBeSent()
      try {
        const urlRedirect = Boolean(this.id) ? 'shopping-update' : 'shopping-store'
        await Axios.post(urlRedirect,formData)
        await showAlertProccessSuccess()
        window.location.reload()
      } catch (error) {
        this.isFormBeingSent = false
        const listErrors = normalizeLaravelErrorList(error.response.data.errors)
        this.showErrors(listErrors)
      }
    },
    dataToBeSent() {
      const params = {
        id: this.id,
        compraSolesDolares: this.compraSolesDolares,
        codigoFactura: this.codigoFactura,
        idSupplier: this.supplier.id,
        idUsuario: this.idUser,
        descripcionBienServicio: this.descripcionBienServicio,
        fechaEmision: this.fechaEmision,
        idTipoComprobante: this.idTipoComprobante,
        monto: this.monto,
        tipoDeCambio: this.tipoDeCambio,
        montoVentaSoles: this.montoVentaSoles,
        montoVentaDolares: this.montoVentaDolares,
        igv: this.igv,
        montoTotal: this.montoTotal,
        listSeries: this.listSeries
      }

      if(this.id && this.listNewSeries.length) {
        Object.assign(params, {listSeriesAdded: this.listNewSeries})
      }
      return params
    },
    downloadExcel() {
      const params = this.urlSearchParams()
      window.location.href = `${this.shoppingExportExcel}?${params}`
    },
    downloadPdf() {
      const params = this.urlSearchParams()
      window.location.href = `${this.shoppingExportPdf}?${params}`
    },
    async getExchangeRateSunat() {
      try {
        const { data } = await Axios.get('tipo-cambio')
        this.tipoCambioSunat = data
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    async getListDataShopping(page = 1) {
      const params = this.urlSearchParams()
      try {
        const { data } = await Axios.get(`list-shopping?page=${page}&${params}`)
        this.listDataShopping = data
        this.isLoading = false
      } catch (error) {

      }
    },
    async getListSeries() {
      try {
        const { data } = await listAll('series')
        this.listDataSeries = data
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    async getListUsers() {
      try {
        const { data } = await listAllActives('users')
        this.listUsers = data
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    hideModal() {
      $("#modal-shopping").modal('hide')
    },
    openModalCreateShopping() {
      this.cleanForm()
      this.modalTitle = 'Nueva'
      this.tipoDeCambio = this.tipoCambioSunat.tipoCambioVenta
      $("#modal-shopping").modal('show')
      document.getElementById('home-tab').click()
    },
    openModalEditShopping(shopping) {
      this.modalTitle = 'Editar'
      this.listNewSeries = []
      this.id = shopping.id
      this.idUser = shopping.idUsuario
      this.codigoFactura = shopping.codigoFactura
      this.supplier = shopping.proveedor
      this.nroDocumentSupplier = shopping.proveedor.nroDocument
      this.descripcionBienServicio = shopping.descripcionBienServicio
      this.fechaEmision = shopping.fechaEmision
      this.idTipoComprobante = shopping.tipo_comprobante.id
      this.compraSolesDolares = shopping.compraSolesDolares
      this.monto = shopping.monto
      this.tipoDeCambio = shopping.tipoDeCambio
      this.montoVentaSoles = shopping.montoVentaSoles
      this.montoVentaDolares = shopping.montoVentaDolares
      this.igv = shopping.igv
      this.montoTotal = shopping.montoTotal
      this.listSeries = shopping.series.map(serie => serie.idSerie)
      this.listSeriesInProccess = shopping.series
      $("#modal-shopping").modal('show')
      document.getElementById('home-tab').click()

    },
    async getListTypeDocuments() {
      try {
        const { data } = await listAllActives('type_documents')
        this.listTypeDocuments = data
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    removeSupplier() {
      this.supplier = {}
      this.nroDocumentSupplier = ''
    },
    async searchSupplier(nroDocument) {
      if(this.nroDocumentSupplier.length == 0) return false
      this.isSearchSupplier = true
      try {
        const { data } = await Axios.get(`supplier-data?nroDocument=${nroDocument}`)
        this.supplier = data
        this.isSearchSupplier = false
        this.audioSuccess.play()
      } catch (error) {
        this.isSearchSupplier = false
        this.supplier = {}
        this.audioError.play()
        this.Sweet.fire({
          icon: 'warning',
          title: '¡Lo sentimos!',
          text: `${error.response.data.message} Registre y luego vuelve a intentarlo.`,
          confirmButtonText: 'Aceptar'
        })
      }
    },
    setDateEmision() {
      this.fechaEmision = moment().format('YYYY-MM-DD')
    },
    showErrors(listErrors) {
      this.$toast.error({ component: ListErrorsComponent, props:  { listErrors } })
    },
    async updataStatus(user) {
      try {
        const params = {
          table: 'shoppings',
          id: user.id,
          is_active: !user.is_active
        }
        await Axios.post('update-status',params)
        user.is_active = !user.is_active
        const status = user.is_active ? 'activado(a)' : 'desactivado(a)'
        this.$toast.success(`Fue ${status} correctamente.`)
      } catch (error) {
        showErrorUpdateAlert()
      }
    },
    urlSearchParams() {
      return new URLSearchParams({
        codigoFacturaSearch: this.codigoFacturaSearch,
        nroDocumentSupplierSearch: this.nroDocumentSupplierSearch,
        idTipoComprobanteSearch: this.idTipoComprobanteSearch,
        compraSolesDolaresSearch: this.compraSolesDolaresSearch,
        fechaEmisionSearch: this.fechaEmisionSearch
      }).toString()
    },
  },
}
