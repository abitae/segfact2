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
import EmptyComponent from '../EmptyComponent.vue'
import moment from "moment"
moment.locale('es-US')

export default {
  props: [
    'canView',
    'canCreate',
    'canEdit',
    'canUpdateStatus',
    'canPrintReport',
    'isAdmin'
  ],
  components: {
    ListErrorsComponent,
    LoaderComponent,
    EmptyComponent
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
      idUser: '',
      igv: '',
      montoTotal: '',
      nroDocumentSupplier: '',
      supplier: {},
      compraSolesDolares: '',
      listTypeDocuments: [],
      saleCommission: '',
      contactCommission: '',

      isSearchSupplier: false,
      isFormBeingSent: false,

      listDataShopping: {},
      isLoading: true,

      searchByDate: 0,
      dateEmissionStart: '',
      dateEmissionEnd: '',

      codigoFacturaSearch: '',
      nroDocumentSupplierSearch: '',
      idTipoComprobanteSearch: '',
      idEnterpriseSearch: '',
      compraSolesDolaresSearch: '',
      idBranchOfficeSearch: '',

      listUsers: [],

      tipoCambioSunat: '',
      idMycompanyEnterprise: '',

      nroSerie: '',
      listSeries: [],
      listSeriesInProccess: [],
      listNewSeries: [],
      enterprises: [],
      branchOffices: [],

      listDataSeries: [],

      nameFile: null,
      attachDocument: null,

      sale: {},
      can,
      warrantyPeriod: '',
      warrantyPeriodQuantity: '',
      startDateWarranty: '',

      renderListBranchOffice: [],
    }
  },
  async mounted() {
    this.getExchangeRateSunat()
    await this.getListEnterprises()
    // await this.getListBranches()
    await this.getListTypeDocuments()
    await this.setDateEmision()
    await this.getListDataShopping()
    await this.getListUsers()
    await this.getListSeries()
  },
  watch: {
    searchByDate(newValue, oldValue) {
      this.setDateEmision()
      this.getListDataShopping()
    },
    async idEnterpriseSearch(newValue, oldValue) {
      if(!newValue) {
        this.renderListBranchOffice = []
      } else {
        const currentEnterprise = this.enterprises.find(({id}) => id == newValue)
        await this.filterRenderListBranches(currentEnterprise.idEnterpriseMyCompany)
      }
    },
  },
  filters: {
    formatDate(date) {
      return moment(date).format('DD-MM-YYYY')
    },
    parseCentimos(money) {
      return Number(money).toFixed(2)
    },
    parseDateHour(param) {
      const date = moment(param).format('DD-MM-YYYY')
      const hour = moment(param).format("LT")
      return `${date} / ${hour}`
    },
  },
  methods: {
    async getListEnterprises() {
      try {
        const { data } = await listAll('enterprises')
        this.enterprises = data
        if(!data.length) return this.idEnterpriseSearch = ''
        const [enterprise] = data
        this.idEnterpriseSearch = enterprise.id
        await this.getListBranches(enterprise.idEnterpriseMyCompany)
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    async getListBranches(idEnterpriseMyCompany) {
      try {
        const { data } = await listAll('branch_offices')
        this.branchOffices = data
        this.filterRenderListBranches(idEnterpriseMyCompany)
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    async filterRenderListBranches(idEnterpriseMyCompany = null) {
      const data = this.branchOffices
      if(!data.length) return this.idBranchOfficeSearch = ''
      if(!idEnterpriseMyCompany) {
        this.renderListBranchOffice = []
      } else {
        const listRender = data.filter( ({idEnterprise}) =>  idEnterprise == idEnterpriseMyCompany)
        this.renderListBranchOffice  = listRender
      }
      this.idBranchOfficeSearch = ''
      await this.getListDataShopping()
    },
    openModalGenerateDocuments(idSale, warrantyPeriod = 'months', warrantyPeriodQuantity = 1) {
      this.warrantyPeriod = warrantyPeriod
      this.warrantyPeriodQuantity = warrantyPeriodQuantity
      this.id = idSale
      this.startDateWarranty = moment().format('Y-MM-DD')
      $('#modal-generate-documents').modal('show')
    },
    async generateDocuments() {
      $('#modal-generate-documents').modal('hide')
      const { isConfirmed } = await this.Sweet.fire({
        icon: 'question',
        title: 'Confirme para continuar',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
      })
      if (!isConfirmed) return
      this.isLoading = true
      const params = {
        id: this.id,
        stateDelivery: 2,
        warrantyStartDate: this.startDateWarranty,
        warrantyPeriod: this.warrantyPeriod,
        warrantyPeriodQuantity: this.warrantyPeriodQuantity,
      }
      try {
        const { data } = await Axios.post('generate-documents', params)
        this.isLoading = false
        this.getListDataShopping()
        await showAlertProccessSuccess()
        const $linkCci = document.createElement('a')
        $linkCci.target = "_blank"
        $linkCci.href = `${this.END_POINT}generate-document-cci?idSale=${this.id}`
        $linkCci.click()

        const $linkLetterWarranty = document.createElement('a')
        $linkLetterWarranty.target = "_blank"
        $linkLetterWarranty.href = `${this.END_POINT}generate-document-letter-warranty?idSale=${this.id}`
        $linkLetterWarranty.click()
      } catch (error) {
        this.isLoading = false
        this.showErrors(error.response.data.errors)
        this.openModalGenerateDocuments(this.id, this.warrantyPeriod, this.warrantyPeriodQuantity)
      }
      // console.log(params)
    },
    closeModal(idNameModal) {
      $(`#${idNameModal}`).modal('hide')
    },
    changeFile(event) {
      const $file = event.target.files[0]
      this.nameFile = $file.name
      this.attachDocument = $file
    },
    renderTextSale(sicla) {
      const listText = {
        PEN: 'Soles',
        USD: 'Dólares'
      }
      return listText[sicla]
    },
    serieHandleSelected(serie) {
      if(!this.id) return

      if(!this.listSeries.includes(serie.id)) {
        this.listNewSeries.push(serie.id)
      }
    },
    async deselectedOption(serie){
      if(!this.id) return
      columna
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
        await Axios.post('sale-serie-delete',{
          idSerie: serie.id,
          id
        })
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
      if(!monto.length || !tipoDeCambio.length) return false
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
      const onePercent = this.montoTotal * 0.01
      this.saleCommission = Number(onePercent).toFixed(2)
      this.contactCommission = Number(onePercent).toFixed(2)
    },
    compraSolesDolaresHandleChange() {
      this.monto = ''
      if(this.compraSolesDolares == 'dolares') {
        this.tipoDeCambio = this.tipoCambioSunat.tipoCambioCompra
      } else {
        this.tipoDeCambio = ''
      }
      this.montoVentaSoles = ''
      this.igv = ''
      this.montoTotal = ''
    },
    clearForm() {
      this.id = 0
      this.codigoFactura = ''
      this.idCliente = ''
      this.descripcionBienServicio = ''
      this.fechaEmision = ''
      this.idTipoComprobante = 1
      this.monto = ''
      this.tipoDeCambio = ''
      this.montoVentaSoles = ''
      this.montoVentaDolares = ''
      this.idUser = ''
      this.igv = ''
      this.montoTotal = ''
      this.nroDocumentSupplier = ''
      this.supplier = {}
      this.compraSolesDolares = ''
      this.listSeriesInProccess = []
      this.listSeries = []
      this.listNewSeries = []
      this.saleCommission = ''
      this.contactCommission = ''
    },
    async createOrUpdate() {
      const formData = this.dataToBeSent()
      try {
        const urlRedirect = Boolean(this.id) ? 'sale-update' : 'sale-store'
        await Axios.post(urlRedirect, formData)
        await showAlertProccessSuccess()
        window.location.reload()
      } catch (error) {
        this.showErrors(error.response.data.errors)
        this.isFormBeingSent = false
      }
    },
    async downloadAttachDocument(nameFile, type) {
      const params = new URLSearchParams({ nameFile, type }).toString()
      window.location.href = `${this.END_POINT}sale-download-attach-document?${params}`
    },
    dataToBeSent() {
      let params = new FormData()
      const hasValue = value => value || ''
      if(hasValue(this.id)) {
        params.append('id',this.id)
      }
      if(hasValue(this.compraSolesDolares)) {
        params.append('compraSolesDolares',this.compraSolesDolares)
      }
      if(hasValue(this.codigoFactura)) {
        params.append('codigoFactura',this.codigoFactura)
      }
      if(hasValue(this.supplier)) {
        params.append('idCustomer',this.supplier.id)
      }
      if(hasValue(this.idUser)) {
        params.append('idUsuario',this.idUser)
      }
      if(hasValue(this.descripcionBienServicio)) {
        params.append('descripcionBienServicio',this.descripcionBienServicio)
      }
      if(hasValue(this.fechaEmision)) {
        params.append('fechaEmision',this.fechaEmision)
      }
      if(hasValue(this.idTipoComprobante)) {
        params.append('idTipoComprobante',this.idTipoComprobante)
      }
      if(hasValue(this.monto)) {
        params.append('monto',this.monto)
      }
      if(hasValue(this.tipoDeCambio)) {
        params.append('tipoDeCambio',this.tipoDeCambio)
      }
      if(hasValue(this.montoVentaSoles)) {
        params.append('montoVentaSoles',this.montoVentaSoles)
      }
      if(hasValue(this.montoVentaDolares)) {
        params.append('montoVentaDolares',this.montoVentaDolares)
      }
      if(hasValue(this.igv)) {
        params.append('igv',this.igv)
      }
      if(hasValue(this.montoTotal)) {
        params.append('montoTotal',this.montoTotal)
      }
      if(hasValue(this.listSeries)) {
        params.append('listSeries',JSON.stringify(this.listSeries))
      }
      if(hasValue(this.saleCommission)) {
        params.append('saleCommission',this.saleCommission)
      }
      if(hasValue(this.contactCommission)) {
        params.append('contactCommission',this.contactCommission)
      }
      if(hasValue(this.attachDocument)) {
        params.append('attachDocument',this.attachDocument)
      }
      if(this.id && this.listNewSeries.length) {
        params.append('listSeriesAdded',this.listNewSeries)
      }
      return params
    },
    async deleteNroSerieHandleClick(serie,index) {
      if('id' in serie) {
        const { isConfirmed } = await this.Sweet.fire({
          title: '¿Esta usted seguro?',
          text: "¡No podrás revertir esto!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: '¡Sí, eliminar!',
          cancelButtonText: 'Cancelar',
        })
        if(isConfirmed) {
          try {
            await Axios.post('sale-serie-delete',{id: serie.id})
            this.Sweet.fire('Eliminado!', 'Su registro ha sido eliminado.', 'success')
            this.listSeries.splice(index,1)
          } catch (error) {
            this.Sweet.fire('¡Oh no!', 'Error inesperado. '+error, 'warning')
          }
        }
      } else {
        this.listSeries.splice(index,1)
      }
    },
    urlSearchParams() {
      return new URLSearchParams({
        idEnterpriseSearch: this.idEnterpriseSearch,
        idBranchOfficeSearch: this.idBranchOfficeSearch,
        codigoFacturaSearch: this.codigoFacturaSearch,
        nroDocumentCustomerSearch: this.nroDocumentSupplierSearch,
        idTipoComprobanteSearch: this.idTipoComprobanteSearch,
        compraSolesDolaresSearch: this.compraSolesDolaresSearch,
        searchByDate: this.searchByDate,
        dateEmissionStart: this.dateEmissionStart,
        dateEmissionEnd: this.dateEmissionEnd,
      }).toString()
    },
    downloadExcel() {
      const params = this.urlSearchParams()
      window.location.href = `${this.END_POINT}sale-export-excel?${params}`
    },
    downloadPdf() {
      const params = this.urlSearchParams()
      window.location.href = `${this.END_POINT}sale-export-pdf?${params}`
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
        const { data } = await Axios.get(`list-sale?page=${page}&${params}`)
        this.listDataShopping = data
        this.isLoading = false
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
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
      this.clearForm()
      this.modalTitle = 'Nueva'
      this.id = 0
      $("#modal-shopping").modal('show')
      document.getElementById('home-tab').click()
    },
    openModalEditShopping(sale) {
      this.sale = sale
      this.nameFile = sale.attachDocument
      $("#modal-sale-list").modal('show')
    },
    renderIconState(state) {
      switch(state) {
        case "aceptado":
          return this.END_POINT + 'backend/assets/images/icons/doc_accepted.png'
        case "rechazado":
          return this.END_POINT + 'backend/assets/images/icons/doc_rejected.png'
        case "pendiente":
          return this.END_POINT + 'backend/assets/images/icons/doc_pending.png'
        case "ticket":
          return this.END_POINT + 'backend/assets/images/icons/doc_ticket.png'
        case "anulado":
          return this.END_POINT + 'backend/assets/images/icons/doc_reversed.png'
      }
    },
    async getListTypeDocuments() {
      try {
        const { data } = await listAllActives('type_documents')
        this.listTypeDocuments = await data
        this.idTipoComprobanteSearch = data.length ? data[0].id : ''
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    removeSupplier() {
      this.supplier = {}
      this.nroDocumentSupplier = ''
    },
    async searchCustomer(nroDocument) {
      if(nroDocument.length == 0) return false
      this.isSearchSupplier = true
      try {
        const { data } = await Axios.get(`customer-data?nroDocument=${nroDocument}`)
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
      this.dateEmissionStart = moment().startOf('month').format('YYYY-MM-DD')
      this.dateEmissionEnd = moment().endOf('month').format('YYYY-MM-DD')
    },
    showErrors(e) {
      const listErrors = normalizeLaravelErrorList(e)
      this.$toast.error({ component: ListErrorsComponent, props:  { listErrors } })
    },
    async updataStatus(user) {
      try {
        const params = {
          table: 'sales',
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
  },
}
