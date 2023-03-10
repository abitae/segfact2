import Axios from "axios"
import {
  listAll,
  listAllActives,
  showReloadWindowAlert,
  normalizeLaravelErrorList,
  showAlertProccessSuccess,
  showErrorUpdateAlert,
  can,
  generateClipboard
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
    'isAdmin'
  ],
  components: {
    ListErrorsComponent,
    LoaderComponent
  },
  data() {
    return {
      modalTitle: '',

      id: 0,
      fechaEmision: '',
      codigoUnidadEjecutora: '',
      nroFacturaCompra: '',
      nroFacturaVenta: '',
      nroSiaf: '',
      description: '',
      fechaPago: '',

      currentSale: null,

      monto: '',
      detraccion: '',
      retencion: '',
      montoTotal: '',

      estadoDocumento: '',
      descripcionBienServicio: '',
      contactoEntidad: '',
      actionesObservaciones: '',
      idEnterprise: '',
      fechaEmision: '',

      nroDocumentCustomer: '', //20541528092
      customer: {},
      compraSolesDolares: '',
      listTypeDocuments: [],

      isSearchCustomer: false,
      isFormBeingSent: false,
      isSearchFacturaCompra: false,
      isSearchFacturaVenta: false,

      isRebilling: false,
      newNroVoucher: '',

      listDataShopping: {},
      isLoading: true,

      codigoFacturaSearch: '',
      nroDocumentCustomerSearch: '',
      fechaEmisionSearch: '',
      estadoDocumentoSearch: '',
      nameUserSearch: '',
      searchByDate: '',
      dateStartSearch: '',
      dateEndSearch: '',
      enterpriseSearch: '',
      bankIdSearch: '',

      pleaForAnnulment: '',

      idSale: '',
      idShopping: '',
      idBranchOfficeSearch: '',


      branchOffices: [],
      listDocumentStates: [],
      listSale: {},
      listEnterprise: [],
      indexedDocumentStates: {},
      renderListBranchOffice: [],
      showStatistics: false,
      statistics: {},

      users: [],
      banks: [],
      bank_id: '',
      nextState: {},
      can,

      paginate: 10,
      currentPage: 1,
      markDisable: true,


      sortDocumentStates: [],
    }
  },
  watch: {
    async enterpriseSearch(newValue, oldValue) {
      if(!newValue) {
        this.renderListBranchOffice = []
      } else {
        const currentEnterprise = this.listEnterprise.find(({id}) => id == newValue)
        await this.filterRenderListBranches(currentEnterprise.idEnterpriseMyCompany)
      }
    },
    async paginate() {
      await this.getListSale()
      this.currentPage = this.listSale.current_page
    },
    listSale(newValue){
      this.currentPage = newValue.current_page
    },
    nameUserSearch(newValue) {
      if(!newValue) return false
      this.showStatistics = !newValue
    },
    showStatistics(newValue) {
      if(newValue) {
        this.calculateStatistics()
      } else {
        this.statistics = {}
      }
    }
  },
  filters: {
    formatDate(date) {
      return !date ? '-' : moment(date).format('DD-MM-YYYY')
    },
    parseCentimos(money) {
      return Number(money).toFixed(2)
    },
  },
  methods: {
    async markAsArranged(sale_id, arranged) {
      try {
        let stateArranged = !arranged;
        this.isLoading = true
        const { data } = await Axios.post('tracking-mark-as-arranged',{sale_id, stateArranged})
        this.getListSale()
        this.isLoading = false
        this.$toast.success(`Actualizado correctamente.`)
      } catch (err) {

      }
    },
    async calculateStatistics() {
      const parseFixed = amount => Number(amount).toFixed(2)
      try {
        const params = new URLSearchParams({
          user_id: this.nameUserSearch
        }).toString();
        const { data: user } = await Axios.get('calculate-statistics?'+params);
        let { cuota } = user;
        let totalAmount = this.listSale.data.reduce((acc, { montoTotal }) => acc + Number(montoTotal), 0).toFixed(2);
        let figure = Number(cuota) > totalAmount ? 'uncompleted' : 'completed';
        let surplus = parseFixed(0);
        let commission = parseFixed(0);
        let percentageShare = Number(((totalAmount-cuota)/cuota) * 100).toFixed(1)
        if(totalAmount > Number(cuota)){
          surplus = parseFixed(totalAmount - cuota);
          commission = parseFixed(surplus * 0.004);
        }
        this.statistics = {
          cuota,
          totalAmount,
          figure,
          surplus,
          commission,
          percentageShare
        }
      } catch (error) {
        console.log("can't obtain statistics")
      }
    },
    async getUsers() {
      try {
        const { data } = await Axios.get('list-sellers')
        this.users = data
      } catch (error) {
        await showReloadWindowAlert()
        this.getUsers()
      }
    },
    async getListBanks() {
      try {
        const { data } = await listAllActives('banks')
        this.banks = data
      } catch (error) {
        await showReloadWindowAlert()
        this.getListBanks()
      }
    },
    async getListEnterprises() {
      try {
        const { data } = await listAll('enterprises')
        this.listEnterprise = data
        if(!data.length) return this.enterpriseSearch = ''
        const [enterprise] = data
        this.enterpriseSearch = enterprise.id
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
      await this.getListSale()
    },
    downloadAttachDocument(idSale, document) {
      window.location.href = `${this.END_POINT}download-document?idSale=${idSale}&path=${document}`
    },
    async refreshVariables() {
      await this.getListEnterprises()
      this.getUsers()
      this.getListBanks()
      this.getListTypeDocuments()
      const today = moment().format('YYYY-MM-DD')
      this.fechaEmision = today
      this.dateStartSearch = today
      this.dateEndSearch = today
      this.getListDocumentStates()
      this.getListSale()
    },
    calculateHandleClick() {
      $(`#modal-calculate-total-import`).modal('show')
      $(`#modal-sale`).modal('hide')
    },
    calculateTotalImport() {
      const totalImport = this.monto - this.detraccion - this.retencion
      this.montoTotal = Number(totalImport).toFixed(2)
    },
    finishCalculate() {
      $(`#modal-calculate-total-import`).modal('hide')
      $(`#modal-sale`).modal('show')
    },
    openModal(nameModal) {
      $(`#${nameModal}`).modal('show')
    },
    closeModal(nameModal) {
      $(`#${nameModal}`).modal('hide')
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
    },
    compraSolesDolaresHandleChange() {
      this.monto = ''
      this.tipoDeCambio = ''
      this.montoVentaSoles = ''
      this.igv = ''
      this.montoTotal = ''
    },
    async updateState() {
      this.isLoading = true
      let params = {
        id: this.currentSale.id,
        nroComprobante: this.currentSale.nroFacturaVenta,
        observation: this.description,
        state: this.nextState.id,
        fechaVencimiento: moment().add(30,'d').format('YYYY-MM-DD')
      }
      try {
        await Axios.post('tracking-receipts-update-status', params)
        $('#modal-sale').modal('hide')
        this.refreshVariables()
        this.isLoading = false
        await showAlertProccessSuccess()
      } catch (error) {
        this.isLoading = false
        this.showErrors(error)
      }
    },
    async createOrUpdate() {
      this.isFormBeingSent = true
      const formData = this.dataToBeSent()
      try {
        const urlRedirect = Boolean(this.id) ? 'tracking-receipts-update' : 'tracking-receipts-store'
        await Axios.post(urlRedirect,formData)
        $('#modal-sale').modal('hide')
        await showAlertProccessSuccess()
        this.isFormBeingSent = false

        const today = moment().format('YYYY-MM-DD')
        this.fechaEmision = today
        this.dateStartSearch = today
        this.dateEndSearch = today
        this.getListSale(this.currentPage)
        // this.refreshVariables()
        // window.location.reload()
      } catch (error) {
        this.isFormBeingSent = false
        this.showErrors(error)
      }
    },
    dataToBeSent() {
      let params = {
        id: this.id,
        nroSiaf: this.nroSiaf,
        monto: this.monto,
        detraccion: this.detraccion,
        retencion: this.retencion,
        montoTotal: this.montoTotal,
        codigoUnidadEjecutora: this.codigoUnidadEjecutora,
        bank_id: this.bank_id,
        fechaPago: this.fechaPago
      }
      return params
    },
    downloadExcel() {
      const searchParams = this.getFiltersSearch()
      window.location.href = `tracking-receipts-export-excel?${searchParams}`
    },
    downloadPdf() {
      const searchParams = this.getFiltersSearch()
      window.location.href = `tracking-receipts-export-pdf?${searchParams}`
    },
    hideModal() {
      $("#modal-sale").modal('hide')
    },
    async getListDocumentStates() {
      try {
        const { data } = await axios.get('document-states')
        this.listDocumentStates = data
        this.indexedDocumentStates = data.reduce((acc,item) => {
          return ({
            ...acc,
            [item.id]: item
          })
        }, {})
        this.sortDocumentStates = data.sort((a,b) => a.order - b.order)
      } catch (error) {
        await showReloadWindowAlert()
        this.getListDocumentStates()
      }
    },
    getFiltersSearch() {
      return new URLSearchParams({
        paginate: this.paginate,
        codigoFacturaSearch: this.codigoFacturaSearch,
        nroDocumentCustomerSearch: this.nroDocumentCustomerSearch,
        estadoDocumentoSearch: this.estadoDocumentoSearch,
        nameUserSearch: this.nameUserSearch,
        searchByDate: this.searchByDate,
        dateStartSearch: this.dateStartSearch,
        dateEndSearch: this.dateEndSearch,
        enterpriseSearch: this.enterpriseSearch,
        bankIdSearch: this.bankIdSearch
      }).toString()
    },
    async getListSale(page = 1) {
      const searchParams = this.getFiltersSearch()
      try {
        const { data } = await Axios.get(`list-tracking-receipts?page=${page}&${searchParams}`)
        this.listSale = data
        this.isLoading = false
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    openModalCreateShopping() {
      this.modalTitle = 'Nueva'
      this.id = 0
      $("#modal-sale").modal('show')
    },
    openModalEditSale(tracking) {
      this.currentSale = tracking
      this.id = tracking.id
      this.codigoUnidadEjecutora = tracking.codigoUnidadEjecutora
      this.nroSiaf = tracking.nroSiaf
      this.monto = tracking.monto
      this.retencion = tracking.retencion
      this.detraccion = tracking.detraccion
      this.montoTotal = tracking.montoTotal
      this.description = ''
      this.bank_id = tracking.bank_id || ''
      this.fechaPago = tracking.fechaPago || ''
      const state = tracking.estadoDocumento
      const { current, next } = this.getStates(state)

      // const currentState = state < 4 ? next.id : current.id
      // this.fechaEmision = currentState == 3 ? moment().format('YYYY-MM-DD') : null
      // this.nextState = this.indexedDocumentStates[currentState]

      // $("#modal-sale").modal('show')
      // if(tracking.estadoDocumento == 4) {
      //   setTimeout(() => document.querySelector('button#profile-tab').click(), 10)
      // } else {
      //   setTimeout(() => document.querySelector('button#home-tab').click(), 10)
      // }

      // const currentState = state < 4 ? next.id : current.id
      this.fechaEmision = current.id == 3 ? moment().format('YYYY-MM-DD') : null
      this.nextState = next

      $("#modal-sale").modal('show')

      if(tracking.estadoDocumento == 4) {
        setTimeout(() => document.querySelector('button#profile-tab').click(), 10)
      } else {
        setTimeout(() => document.querySelector('button#home-tab').click(), 10)
      }
    },
    getStates(currentState) {
      const nextState = this.sortDocumentStates.findIndex((item) => item.id == currentState)
      const previus = this.sortDocumentStates[nextState-1] ? {...this.sortDocumentStates[nextState-1]} : null;
      const current = {...this.sortDocumentStates[nextState]};
      const next = this.sortDocumentStates[nextState+1] ? {...this.sortDocumentStates[nextState+1]} : null;
      return {previus, current, next}
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
    removeCustomer() {
      this.customer = {}
      this.nroDocumentCustomer = ''
    },
    async searchNroFacturaCompra(nroFactura) {
      if(nroFactura.length == 0) return false
      this.isSearchFacturaCompra = true
      try {
        const { data } = await Axios.get(`shopping-data?nroFactura=${nroFactura}`)
        this.idShopping = data.id
        $("#nroFacturaCompra").addClass('border-success')
        this.isSearchFacturaCompra = false
        this.audioSuccess.play()
        this.Sweet.fire({
          icon: 'success',
          title: '¡Encontrado!',
          text: `N° Factura de compra encontrado.`,
          confirmButtonText: 'Aceptar'
        })
      } catch (error) {
        this.idShopping = ''
        $("#nroFacturaCompra").removeClass('border-success')
        this.isSearchFacturaCompra = false
        this.Sweet.fire({
          icon: 'warning',
          title: '¡Lo sentimos!',
          text: `${error.response.data.message} Registre y luego vuelve a intentarlo.`,
          confirmButtonText: 'Aceptar'
        })
      }
    },
    async searchNroFacturaVenta(nroFactura) {
      if(nroFactura.length == 0) return false
      this.isSearchFacturaVenta = true
      try {
        const { data } = await Axios.get(`sale-data?nroFactura=${nroFactura}`)
        this.idSale = data.id
        this.isSearchFacturaVenta = false
        $("#nroFacturaVenta").addClass('border-success')
        this.audioSuccess.play()
        this.Sweet.fire({
          icon: 'success',
          title: '¡Encontrado!',
          text: `N° Factura de venta encontrada.`,
          confirmButtonText: 'Aceptar'
        })
      } catch (error) {
        this.idSale = ''
        this.isSearchFacturaVenta = false
        $("#nroFacturaVenta").removeClass('border-success')
        this.Sweet.fire({
          icon: 'warning',
          title: '¡Lo sentimos!',
          text: `${error.response.data.message} Registre y luego vuelve a intentarlo.`,
          confirmButtonText: 'Aceptar'
        })
      }
    },
    async searchCustomer(nroDocument) {
      if(this.nroDocumentCustomer.length == 0) return false
      this.isSearchCustomer = true
      try {
        const { data } = await Axios.get(`customer-data?nroDocument=${nroDocument}`)
        this.customer = data
        this.isSearchCustomer = false
        this.audioSuccess.play()
      } catch (error) {
        this.isSearchCustomer = false
        this.customer = {}
        this.audioError.play()
        this.Sweet.fire({
          icon: 'warning',
          title: '¡Lo sentimos!',
          text: `${error.response.data.message} Registre y luego vuelve a intentarlo.`,
          confirmButtonText: 'Aceptar'
        })
      }
    },
    cancelVoucher(sale) {
      $('#modal-cancel-voucher').modal('show')
      this.currentSale = sale
      this.newNroVoucher = ''
      this.isRebilling = false
    },
    async annulmentVoucher() {
      try {
        const data = await Axios.post('annulment-voucher',{
          id: this.currentSale.id,
          nroComprobante: this.currentSale.nroFacturaVenta,
          newVoucher: this.newNroVoucher,
          isRebilling: this.isRebilling,
          observation: this.pleaForAnnulment,
          state: 5,
        })
        $('#modal-cancel-voucher').modal('hide')
        this.refreshVariables()
        await showAlertProccessSuccess()
        this.currentSale = null
        this.isRebilling = false
      } catch (error) {
        this.showErrors(error)
      }
    },
    setBackgroundColor(estadoDocumento, fechaVencimiento) {
      if(estadoDocumento == 4) return 'dateBg-success'
      if(estadoDocumento == 5) return 'dateBg-nullable'
      if(fechaVencimiento) {
        const diasPorVencer = moment().diff(fechaVencimiento,'days')
        return diasPorVencer > 0 ? 'dateBg-danger' : 'dateBg-warning'
      }
    },
    setTextDescription(fechaVencimiento, state) {
      if(state == 4) return 'Pagado'
      if(state == 5) return 'Anulado'
      if(!fechaVencimiento) return '-'
      const diffInDays = Math.abs(moment().diff(fechaVencimiento,'days'))

      if(!diffInDays) {
        return 'Expira hoy'
      } else if(moment().isBefore(fechaVencimiento)) {
        return `Expira en ${diffInDays} días`
      } else if(moment().isAfter(fechaVencimiento)) {
        return `Expiró hace ${diffInDays} días`
      }
    },
    showErrors(error) {
      const listErrors = normalizeLaravelErrorList(error.response.data.errors)
      this.$toast.error({ component: ListErrorsComponent, props:  { listErrors } })
    },
    async updataStatus(user) {
      try {
        const params = {
          table: 'seguimiento_comprobantes',
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
  mounted() {
    this.refreshVariables()
  },
}
