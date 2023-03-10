import Axios from 'axios'
import moment from 'moment'
import ListErrorsComponent from '../ListErrorsComponent.vue'
import {
  listAllActives,
  showReloadWindowAlert,
  normalizeLaravelErrorList,
  MY_COMPANY_END_POINT,
  MY_COMPANY_API_KEY,
  listAll,
  showAlertProccessSuccess,
  can,
  setFocus,
  consultDocumentSunat
} from '../Service'

export default {
  name: 'SynchronizationComponent',
  props: [
    'canCreateShopping',
    'canCreateSale'
  ],
  data() {
    return {
      codigoUnidadEjecutora: '',
      nroSiaf: '',

      listEnterprises: [],

      idEnterpriseMyCompanyShopping: '',
      synchronizeByShopping: null,
      serieComprobanteShopping: '',
      nroComprobanteShopping: '',
      dateStartShopping: '',
      dateEndShopping: '',

      idEnterpriseMyCompanySale: '',
      synchronizeBySale: 'document',
      serieComprobanteSale: '',
      nroComprobanteSale: '',
      dateStartSale: '',
      dateEndSale: '',

      listShoppingMyCompanyChecked: [],
      listShoppingMyCompany: [],

      sale: [],
      saleDetail: [],

      listDetail: [],

      currentIndexPucharse: null,
      currentIndexPurcharseDetail: null,

      listSuppliers: [],
      listUsers: [],

      idUser: null,
      isSentForm: false,

      serieSaleDetail: '',
      listCustomers: [],
      listTypeDocuments: [],
      listBranchOffices: {},

      attachPurchaseOrderDocument: null,
      attachRemissionGuideDocument: null,

      descripcionBienServicio: '',

      licenses: {
        description: '',
        quantityLicenses: 1,
        installationDate: moment().format('YYYY-MM-DD'),
      },
      resourceAddLicense: {},
      contact: {
        id: null,
        nroPhone: '',
        fullName: '',
        contactRegistered: false,
      },
      isSearchContact: false,

      can,
      isLoading: false,
      isSerieByAdding: false,
    }
  },
  mounted() {
    this.getListUsers()
    this.getListSuppliers()
    this.getListEnterprises()
    this.getListCustomers()
    this.getListTypeDocuments()
    this.getListBranchOffices()
  },
  filters: {
    formatDate(date) {
      return moment(date).format('DD-MM-YYYY')
    },
    parseCentimos(money) {
      return Number(money).toFixed(2)
    },
    parseDateHour(date) {
      return moment(date).format("LT")
    },
  },
  computed: {
    credentials_my_company() {
      return new URLSearchParams({
        key: MY_COMPANY_API_KEY
      }).toString()
    }
  },
  methods: {
    async searchContactByNroPhone(nroPhone) {
      this.isSearchContact = true
      try {
        const { data } = await Axios.get(`get-contact-by-nro-phone?nroPhone=${nroPhone}`)
        this.contact.contactRegistered = true
        this.contact.id = data.id
        this.contact.fullName = data.fullName
        this.isSearchContact = false
      } catch (error) {
        this.contact.id = null
        this.contact.fullName = ''
        this.contact.contactRegistered = false
        document.getElementById('contact.fullName').focus()
        this.isSearchContact = false
      }
    },
    changeFile(nameDocument) {
      const { files } = document.getElementById(nameDocument)
      if(files.length) {
        [this[nameDocument]] = files
      }
    },
    async getListBranchOffices() {
      try {
        const { data } = await listAll('branch_offices')
        if(!data.length) return
        const indexed = data.reduce((acc,item) => ({
          ...acc,
          [item.idMycompany]: item
        }), [])
        this.listBranchOffices = indexed
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    async getListTypeDocuments() {
      try {
        const { data } = await listAll('type_documents')
        const listTypeDocuments = data.reduce((acc,item) => ({
          ...acc,
          [item.codigo]: item
        }), [])
        this.listTypeDocuments = listTypeDocuments
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    async getListCustomers() {
      try {
        const { data } = await listAll('customers')
        const listCustomers = data.reduce((acc, customer) => ({
          ...acc,
          [customer.nroDocument]: customer
        }) ,[])
        this.listCustomers = listCustomers
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    closeModal(nameModal) {
      $(`#${nameModal}`).modal('hide')
    },
    openModal(nameModal) {
      $(`#${nameModal}`).modal('show')
    },
    async getListSuppliers() {
      try {
        const { data } = await listAll('suppliers')
        let indexed = data.reduce((acc, item) => ({
          ...acc,
          [item.idShoppingMyCompany]: item,
        }),{})
        this.listSuppliers = indexed
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
    removeSerie(serie, index, listSeries) {
      let newListSerie = [...listSeries]
      newListSerie.splice(index, 1)
      const indexDetail = serie.indexPucharse
      let detail = this.listDetail[serie.indexPucharse]
      detail.listSeries = newListSerie
      this.listDetail.splice(indexDetail, 1, detail)
    },
    addSerieToDetail($event, detail,  index) {
      const form = new FormData($event.target)
      this.currentIndexPurcharseDetail = index
      let listSeries = detail.listSeries ? [...detail.listSeries] : new Array()
      const newSerie = {
        name: form.get('serie').toUpperCase(),
        indexPucharse: index
      }
      listSeries.push(newSerie)
      detail.listSeries = listSeries
      this.listDetail.splice(index, detail)
      $event.target.reset()
    },
    closeModalMyCompanyList() {
      $("#my-company-list").modal('hide')
    },
    closeModalPucharseDetail() {
      $("#pucharse-detail").modal('hide')
      $("#my-company-list").modal('show')
    },
    handleChangeCheckbox(value) {
      const index = this.listShoppingMyCompanyChecked.indexOf(value)
      if( index == -1) {
        this.listShoppingMyCompanyChecked.push(value)
      } else {
        this.listShoppingMyCompanyChecked.splice(index,1)
      }
    },
    async getListEnterprises() {
      try {
        const { data } = await listAllActives('enterprises')
        this.listEnterprises = data
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    dataToBeSent(name) {
      const idEnterpriseMyCompany = this[`idEnterpriseMyCompany${name}`]
      const synchronizationMode = this[`synchronizeBy${name}`]
      if(!idEnterpriseMyCompany || !synchronizationMode) return

      let params = new Object
      params.synchronizeBy = synchronizationMode
      params.idEnterpriseMyCompany = idEnterpriseMyCompany

      if(synchronizationMode == 'document') {
        if(!this[`serieComprobante${name}`] || !this[`nroComprobante${name}`]) return
        params.serieComprobante = String(this[`serieComprobante${name}`]).trim()
        params.nroComprobante = String(this[`nroComprobante${name}`]).trim()
      }

      const dateStart = this[`dateStart${name}`]
      const dateEnd = this[`dateEnd${name}`]

      if(synchronizationMode == 'oneDay') {
        if(!dateStart) return
        params.dateStart = dateStart
      }

      if(synchronizationMode == 'oneToMoreDays') {
        if(!dateStart || !dateEnd) return
        params.dateStart = dateStart
        params.dateEnd = dateEnd
      }

      return new URLSearchParams(params).toString()
    },
    async handleSynchronizedShopping() {
      const params = this.dataToBeSent('Shopping')
      if(!params) return
      const { data } = await Axios.get('is-user-check')
      if(!data) window.location.reload()
      this.isLoading = true
      try {
        const { data: shoppingsCurrentSystem } = await Axios.get('shopping-search-synchronize',params)
        let list_nro_factura_system, list_nro_facturas_my_company

        if(shoppingsCurrentSystem.length) {
          list_nro_factura_system = shoppingsCurrentSystem.map(item => item.codigoFactura)
        }

        const { data: shoppingsOtherSystem } = await Axios.get(`${MY_COMPANY_END_POINT}list-compra?${params}&${this.credentials_my_company}`)
        if(!shoppingsOtherSystem.length) {
          this.isLoading = false
          this.Sweet.fire({
            icon: 'warning',
            title: 'Sin resultados',
            text: 'No hay nada que sincronizar',
            confirmButtonText: 'Aceptar'
          })
          return
        }

        list_nro_facturas_my_company = shoppingsOtherSystem.map(item => `${item.serie_comprobante}-${item.numero_comprobante}`)

        let listNewFacturas = []

        if(list_nro_factura_system) {
          listNewFacturas = list_nro_facturas_my_company.filter(item => !list_nro_factura_system.includes(item))
          const list = shoppingsOtherSystem.filter(item => listNewFacturas.includes(`${item.serie_comprobante}-${item.numero_comprobante}`))
          if(!list.length) {
            this.isLoading = false
            this.Sweet.fire({
              icon: 'warning',
              title: 'Sin registros',
              text: 'No hay nada que sincronizar',
              confirmButtonText: 'Aceptar'
            })
            return
          }
          this.listShoppingMyCompany = list
        } else {
          this.listShoppingMyCompany = shoppingsOtherSystem
        }
        this.idUser = Number(this.authenticated.id)
        this.isLoading = false
        $("#my-company-list").modal('show')
      } catch (error) {
        this.isLoading = false
        this.Sweet.fire({
          icon: 'warning',
          title: '¡Ups!',
          text: 'Algo sucedio mal, si persiste el error, comuníquese con el desarrollador',
          confirmButtonText: 'Aceptar'
        })
      }
    },
    async handleSynchronizedSale() {
      const params = this.dataToBeSent('Sale')
      if(!params) return
      const { data } = await Axios.get('is-user-check')
      if(!data) window.location.reload()
      this.isLoading = true
      try {
        const { data: saleInSegFact } = await Axios.get(`sale-search-synchronize?${params}`)
        if(saleInSegFact) {
          this.Sweet.fire({
            icon: 'warning',
            title: '¡Encontrado!',
            text: 'Comprobante ya se ha sincronizado',
            confirmButtonText: 'Aceptar'
          })
          this.isLoading = false
          return
        }
        const { data } = await Axios.get(`${MY_COMPANY_END_POINT}lista-venta?${params}&${this.credentials_my_company}`)
        if(!data.venta) {
          this.isLoading = false
          this.serieComprobanteSale = ''
          this.nroComprobanteSale = ''
          setFocus('serieComprobanteSale')
          this.Sweet.fire({
            icon: 'warning',
            title: 'Sin registros',
            text: 'No hay nada que sincronizar',
            confirmButtonText: 'Aceptar'
          })
          return
        }

        this.sale = data.venta
        const newDetails = data.detalle.map(item => ({...item, listSeries: []}) )
        this.saleDetail = newDetails
        this.isLoading = false
        $('#modal-sale-list').modal({
          show: true,
          keyboard: false,
          backdrop: 'static'
        })
        this.openModal('modal-sale-list')
      } catch (error) {
        await showReloadWindowAlert()
        this.isLoading = false
        window.location.reload()
      }
    },
    async addSerieToSaleDetail(event, detail, index) {
      let currentValue
      if(event.target.tagName == 'BUTTON') {
        currentValue = String(event.target.parentElement.parentElement.firstElementChild.value).trim()
      } else if(event.target.tagName == 'INPUT') {
        currentValue = String(event.target.value).trim()
      }
      if(!currentValue) return
      let listSeries = detail.listSeries
      const exists = listSeries.find(({ nroSerie }) => nroSerie === currentValue)
      if(exists) return
      this.isSerieByAdding = true
      try {
        const { data } = await Axios.get(`search-by-name-serie?nroSerie=${currentValue}`)
        listSeries.push(data)
        detail.listSeries = listSeries
        this.saleDetail.splice(index, 1, detail)
        this.isSerieByAdding = false
        document.getElementsByClassName('list-inputs-clear').forEach( input => {
          input.value = ''
        })
      } catch (error) {
        listSeries.push({
          id: 0,
          nroSerie: String(currentValue).toUpperCase(),
        })
        detail.listSeries = listSeries
        this.saleDetail.splice(index, 1, detail)
        this.isSerieByAdding = false
        document.getElementsByClassName('list-inputs-clear').forEach( input => {
          input.value = ''
        })
      }
    },
    removeSerieToSaleDetail(detail, indexSerie, indexDetail) {
      let listSeries = detail.listSeries
      listSeries.splice(indexSerie,1)
      detail.listSeries = listSeries
      this.saleDetail.splice(indexDetail,1,detail)
    },
    renderIconState(state) {
      const icon = {
        aceptado: 'doc_accepted.png',
        rechazado: 'doc_rejected.png',
        pendiente: 'doc_pending.png',
        ticket: 'doc_ticket.png',
        anulado: 'doc_reversed.png',
      }
      if(!icon) return
      return `${this.END_POINT}backend/assets/images/icons/${icon[state]}`
    },
    openModalPucharseDetails(detail, index) {
      this.listDetail = detail
      this.currentIndexPucharse = index
      $("#my-company-list").modal('hide')
      $("#pucharse-detail").modal('show')
    },
    async sendFormListShopping() {
      const onlyChecked = this.listShoppingMyCompany.filter( shopping => this.listShoppingMyCompanyChecked.includes(shopping.id_compra)).map( shopping => {
        if(this.listSuppliers[shopping.proveedor.id_proveedor]) {
          shopping.proveedor.id = this.listSuppliers[shopping.proveedor.id_proveedor].id
        }
        return shopping
      })

      if(!onlyChecked.length) window.location.reload()
      const currentEnterprise = this.listEnterprises.find( enterprise => enterprise.idEnterpriseMyCompany == this.idEnterpriseMyCompanyShopping)

      try {
        let data = {
          listPucharse: onlyChecked,
          idEnterprise: currentEnterprise.id,
        }
        if(this.idUser) {
          data.idUser = this.idUser
        }
        await Axios.post('synchronize-purchases',data)
        await showAlertProccessSuccess()
        window.location.href = '/shopping-management'
      } catch (error) {
        if(error.response.data.errors) {
          const listErrors = normalizeLaravelErrorList(error.response.data.errors)
          this.showErrors(listErrors)
        }
      }
    },
    async sendFormListSale() {
      this.isLoading = true
      const $form = document.getElementById('form-sale')
      const sale = this.sale

      const formData = new FormData($form)
      formData.append('idUsuario', this.authenticated.id)
      formData.append('saleDetail', JSON.stringify(this.saleDetail))
      formData.append('condicionPago', sale.condicion_pago.condicionpago)
      formData.append('fechaEmision', sale.fecha_registro)
      formData.append('tipoPago', sale.id_codigomoneda)
      formData.append('nroGuiaRemision', sale.nro_guia_remision || '')
      formData.append('nroPucharseOrder', sale.nro_otr_comprobante || '')
      formData.append('estadoEnvioSunat', sale.estado_envio_sunat)
      formData.append('codigoFactura', `${sale.serie_comprobante}-${sale.numero_comprobante}`)
      if(this.listTypeDocuments[sale.id_tipodoc_electronico]) {
        formData.append('idTipoComprobante', this.listTypeDocuments[sale.id_tipodoc_electronico].id)
      }
      if(this.descripcionBienServicio) {
        formData.append('descripcionBienServicio', this.descripcionBienServicio)
      }
      formData.append('compraSolesDolares', sale.id_codigomoneda)
      formData.append('monto', sale.sub_total)
      formData.append('igv', sale.total_igv)
      formData.append('montoTotal', sale.total)
      formData.append('saleCommission', Number(sale.total) * 0.01)
      formData.append('contactCommission', Number(sale.total) * 0.01)
      formData.append('fechaEmision', sale.fecha_registro)

      if(this.attachPurchaseOrderDocument) {
        formData.append('attachPurchaseOrderDocument', this.attachPurchaseOrderDocument)
      }
      if(this.attachRemissionGuideDocument) {
        formData.append('attachRemissionGuideDocument', this.attachRemissionGuideDocument)
      }

      if(this.listCustomers[sale.cliente.num_doc]) {
        formData.append('idCustomer', this.listCustomers[sale.cliente.num_doc].id)
      } else {
        const typeDocument = String(sale.cliente.num_doc).trim().length == 8 ? '01' : '06'
        const { data: customer} = await consultDocumentSunat({
          nroDocument: sale.cliente.num_doc,
          type: typeDocument
        })

        formData.append('customer', JSON.stringify({
          typeDocument: typeDocument,
          nroDocument: ('ruc' in customer) ? customer.ruc : customer.dni,
          name: ('ruc' in customer) ? '' : customer.nombres,
          lastName: ('ruc' in customer) ? '' : `${customer.ap_paterno} ${customer.ap_materno}`,
          fullName: ('ruc' in customer) ? customer.razon_social : customer.nombre,
          address: ('ruc' in customer) ? customer.direccion : '',
          email: '-',
          nroPhone: '-',
        }))
      }
      if(this.contact.id) formData.append('idContact', this.contact.id)

      formData.append('contactNroPhone', this.contact.nroPhone)
      formData.append('contactFullName', this.contact.fullName)
      formData.append('contactRegistered', this.contact.contactRegistered)
      formData.append('codigoUnidadEjecutora', this.codigoUnidadEjecutora)
      formData.append('nroSiaf', this.nroSiaf)

      if(this.listBranchOffices[sale.id_sucursal]) {
        formData.append('idBranchOffice', this.listBranchOffices[sale.id_sucursal].id)
      } else {
        const branchOffice = sale.sucursal
        formData.append('branchOffice', JSON.stringify({
          name: branchOffice.nombre,
          address: branchOffice.direccion,
          idUbigeo: branchOffice.id_ubigeo,
          nroPhone: branchOffice.telefono,
          email: branchOffice.email,
          idMycompany: branchOffice.idsucursal,
          idEnterprise: branchOffice.id_contribuyente
        }))
      }
      formData.append('idEnterprise', this.idEnterpriseMyCompanySale)
      try {
        const data = await Axios.post('synchronize-sale', formData)
        this.isLoading = false
        await showAlertProccessSuccess()
        window.location.href = this.END_POINT+'sale-management'
      } catch (error) {
        if(error.response.data.errors) {
          const listErrors = normalizeLaravelErrorList(error.response.data.errors)
          this.showErrors(listErrors)
        }
        this.isLoading = false
      }
    },
    showErrors(listErrors) {
      this.$toast.error({ component: ListErrorsComponent, props:  { listErrors } })
    },
    openModalLicense(detail, index) {
      this.resourceAddLicense = { index, detail }
      if(detail.licenses) {
        this.licenses = detail.licenses
      } else {
        this.licenses = {
          description: '',
          quantityLicenses: 1,
          installationDate: moment().format('YYYY-MM-DD'),
        }
      }
      $('#modal-sale-list').modal('hide')
      $('#list-details').modal({ show: true, keyboard: false, backdrop: 'static' })
      this.openModal('list-details')
    },
    addLicense() {
      let detail = this.resourceAddLicense.detail
      detail.licenses = this.licenses
      this.saleDetail.splice(this.resourceAddLicense.index, 1, detail)
      $('#list-details').modal('hide')
      $('#modal-sale-list').modal({
        show: true,
        keyboard: false
      })
      this.openModal('modal-sale-list')
      this.resourceAddLicense = {}
    },
    closeModalLicenses() {
      $('#list-details').modal('hide')
      $('#modal-sale-list').modal({
        show: true,
        keyboard: false
      })
      this.openModal('modal-sale-list')
      this.resourceAddLicense = {}
    },
    removeLicense() {
      let detail = this.resourceAddLicense.detail
      delete detail.licenses
      this.saleDetail.splice(this.resourceAddLicense.index, 1, detail)
      $('#list-details').modal('hide')
      $('#modal-sale-list').modal({
        show: true,
        keyboard: false
      })
      this.openModal('modal-sale-list')
      this.resourceAddLicense = {}
    },
  },
}
