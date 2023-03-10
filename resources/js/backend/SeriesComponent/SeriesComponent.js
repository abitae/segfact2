import Axios from 'axios'
import ListErrorsComponent from '../ListErrorsComponent.vue'
import LoaderComponent from '../LoaderComponent.vue'
import EmptyComponent from '../EmptyComponent.vue'
import moment from 'moment'
import {
  normalizeLaravelErrorList,
  showAlertProccessSuccess,
  showReloadWindowAlert,
  can
} from '../Service'

export default {
  props: [
    'canView',
    'canCreate',
    'canEdit',
    'canUpdateStatus',
  ],
  component: { LoaderComponent, EmptyComponent },
  data() {
    return {
      modalTitle: '',

      id: 0,
      nroSerie: '',

      listSeries: {},

      nroSerieSearch: '',

      nroSerieSearchAdvanced: '',

      isFormSent: false,
      isLoading: true,

      seguimientoSerie: {},
      can,
    }
  },
  filters: {
    formatDate(date) {
      return moment(date).format('DD-MM-Y')
    }
  },
  mounted() {
    this.getListSeries()
  },
  methods: {
    closeModal() {
      $('#modal-series').modal('hide')
    },
    async createOrUpdate() {
      const formData = this.dataToBeSent()
      const urlRedirect = formData.id ? 'series-update' : 'series-store'
      this.isFormSent = true
      try {
        await Axios.post(urlRedirect, formData)
        await showAlertProccessSuccess()
        window.location.reload()
      } catch (error) {
        this.isFormSent = false
        const listErrors = normalizeLaravelErrorList(error.response.data.errors)
        this.showErrors(listErrors)
      }
    },
    dataToBeSent() {
      return {
        id: this.id,
        nroSerie: this.nroSerie.toUpperCase()
      }
    },
    async getAdvancedSearch() {
      if(!this.nroSerieSearchAdvanced) return
      const nroSerie = String(this.nroSerieSearchAdvanced).toUpperCase()
      try {
        const { data } = await Axios.get(`serie-search-advanced?nroSerie=${nroSerie}`)
        this.seguimientoSerie = data
      } catch (error) {
        this.seguimientoSerie = {}
        if(error.response.data.message) {
          this.showErrors([error.response.data.message])
          return
        }
      }
    },
    async getListSeries(page = 1) {
      try {
        const urlSearchParams = this.urlSearchParams()
        const { data } = await Axios.get(`list-series?${urlSearchParams}&page=${page}`)
        this.listSeries = data
        this.isLoading = false
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    openModalCreate() {
      this.modalTitle = 'Nueva'
      this.id = 0
      $('#modal-series').modal('show')
    },
    showErrors(listErrors) {
      this.$toast.error({ component: ListErrorsComponent, props:  { listErrors } })
    },
    async updataStatus(data) {
      try {
        const params = {
          table: 'series',
          id: data.id,
          is_active: !data.is_active
        }
        await Axios.post('update-status',params)
        data.is_active = !data.is_active
        const status = data.is_active ? 'activado(a)' : 'desactivado(a)'
        this.$toast.success(`Fue ${status} correctamente.`)
      } catch (error) {
        showErrorUpdateAlert()
      }
    },
    urlSearchParams() {
      return new URLSearchParams({
        nroSerie: this.nroSerieSearch
      }).toString()
    },
  },
}
