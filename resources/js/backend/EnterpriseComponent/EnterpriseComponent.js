import Axios from "axios"
import ListErrorsComponent from '../ListErrorsComponent.vue'
import LoaderComponent from '../LoaderComponent.vue'

import {
  normalizeLaravelErrorList,
  showErrorUpdateAlert,
  showReloadWindowAlert,
  can,
  consultDocumentSunat,
  generateClipboard
} from "../Service"

export default {
  name: 'EnterpriseComponent',
  props:[
    'canView',
    'canCreate',
    'canEdit',
    'canUpdateStatus'
  ],
  components: {
    ListErrorsComponent,
    LoaderComponent
  },
  data() {
    return {
      modalTitle: '',
      id: 0,

      typeDocument: '',
      nroDocument: '',
      name: '',
      lastName: '',
      fullName: '',
      address: '',
      email: '',
      nroPhone: '',
      representative_name: '',
      representative_dni: '',
      nro_cuenta_interbancaria: '',
      nro_cuenta_detraction: '',

      nroDocumentSearch: '',
      fullNameSearch: '',
      addressSearch: '',
      emailSearch: '',
      idEnterpriseMyCompany: '',

      listDataEnterprises: {},
      can,
      isLoading: true,

      isSearchDocumentSunat: false,
    }
  },
  created() {

  },
  mounted() {
    this.getListDataEnterprise()
  },
  methods: {
    copiedAtClipboard(nroDocument) {
      generateClipboard(nroDocument)
    },
    hideModal() {
      $("#modal-enterprise").modal('hide')
    },
    selectTypeDocumentHandleChange() {
      this.name = ''
      this.lastName = ''
      this.fullName = ''
      this.nroDocument = ''
    },
    showTextAccordingDocumentType(typeDocument) {
      return typeDocument == '06' ? 'RUC' : 'DNI'
    },
    async searchDocument() {
      this.isSearchDocumentSunat = true
      try {
        const paramsConsult = {
          type: this.typeDocument,
          nroDocument: this.nroDocument
        }
        const { data } = await consultDocumentSunat(paramsConsult)

        if(data.respuesta != 'ok') throw {error: data.mensaje}
        if('ruc' in data) {
          this.fullName = data.razon_social
          this.address = data.direccion
          this.name = ''
          this.lastName = ''
        } else {
          this.name = data.nombres
          this.lastName = `${data.ap_paterno} ${data.ap_materno}`
          this.fullName = ''
          this.address = ''
        }
        this.isSearchDocumentSunat = false
        this.audioSuccess.play()
      } catch ({error}) {
        this.audioError.play()
        this.Sweet.fire({
          icon: 'warning',
          title: '¡Alerta!',
          text: error,
          confirmButtonText: 'Correjir'
        })
        this.isSearchDocumentSunat = false
      }
    },
    openModalCreateEnterprise() {
      this.modalTitle = 'Nueva'
      $("#modal-enterprise").modal('show')
      this.claerData()
    },
    openModalEditUser(model)
    {
      this.modalTitle = 'Editar'
      $("#modal-enterprise").modal('show')
      this.id = model.id
      this.typeDocument = model.typeDocument
      this.nroDocument = model.nroDocument
      this.name = model.name
      this.lastName = model.lastName
      this.fullName = model.fullName
      this.address = model.address
      this.email = model.email
      this.nroPhone = model.nroPhone
      this.idEnterpriseMyCompany = model.idEnterpriseMyCompany
      this.representative_name = model.representative_name
      this.representative_dni = model.representative_dni
      this.nro_cuenta_interbancaria = model.nro_cuenta_interbancaria
      this.nro_cuenta_detraction = model.nro_cuenta_detraction
    },
    claerData() {
      this.id = 0
      this.typeDocument = ''
      this.nroDocument = ''
      this.name = ''
      this.lastName = ''
      this.fullName = ''
      this.address = ''
      this.email = ''
      this.nroPhone = ''
      this.idEnterpriseMyCompany = ''
      this.representative_name = ''
      this.representative_dni = ''
      this.nro_cuenta_interbancaria = ''
      this.nro_cuenta_detraction = ''
    },
    obtainDataSearch() {
      const searchParams = new URLSearchParams({
        nroDocumentSearch: this.nroDocumentSearch,
        fullNameSearch: this.fullNameSearch,
        addressSearch: this.addressSearch,
        emailSearch: this.emailSearch
      })
      return searchParams.toString()
    },
    obtainData() {
      const data = {
        id: this.id,
        typeDocument: this.typeDocument,
        nroDocument: this.nroDocument,
        name: this.name,
        lastName: this.lastName,
        fullName: this.fullName || `${this.name} ${this.lastName}`,
        address: this.address,
        email: this.email,
        nroPhone: this.nroPhone,
        idEnterpriseMyCompany: this.idEnterpriseMyCompany,

        representative_name: this.representative_name,
        representative_dni: this.representative_dni,
        nro_cuenta_interbancaria: this.nro_cuenta_interbancaria,
        nro_cuenta_detraction: this.nro_cuenta_detraction,
      }
      return data
    },
    async getListDataEnterprise(page = 1) {
      try {
        let searchParams = this.obtainDataSearch()
        let { data } = await Axios.get(`list-enterprises?${searchParams}&page=${page}`)
        this.listDataEnterprises = data
        this.isLoading = false
      } catch (error) {
        showReloadWindowAlert()
        this.isLoading = false
      }
    },
    async createOrUpdate() {
      const params = this.obtainData()
      if(!params) return false

      try {
        const url_redirect = params.id == 0 ? 'enterprise-store' : 'enterprise-update'
        const { data } = await Axios.post(url_redirect,params)
        const activity = params.id == 0 ? 'creado' : 'actualizado'
        this.hideModal()
        await this.Sweet.fire({
          icon: 'success',
          title: 'Proceso exitoso',
          text: `La empresa fue ${activity} correctamente.`,
          confirmButtonText: 'Aceptar'
        })
        window.location.reload()
      } catch (error) {
        const listErrors = normalizeLaravelErrorList(error.response.data.errors)
        this.showErrors(listErrors)
      }
    },
    async updataStatus(user) {
      try {
        const params = {
          table: 'enterprises',
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
    showErrors(listErrors) {
      this.$toast.error({ component: ListErrorsComponent, props:  { listErrors } })
    }
  },
}
