import ListErrorsComponent from '../ListErrorsComponent.vue'
import EmptyComponent from '../EmptyComponent.vue'
import Axios from 'axios'
import moment from 'moment'
import {
  listAllActives,
  showAlertProccessSuccess,
  showReloadWindowAlert,
  normalizeLaravelErrorList,
  showErrorUpdateAlert,
  can
} from '../Service'
export default {
  component: {EmptyComponent},
  props: [
    'canView',
    'canCreate',
    'canEdit',
    'canUpdateStatus',
  ],
  data() {
    return {
      id: 0,
      product: '',
      description: '',
      quantity: '',
      installationDate: '',
      expirationDate: '',
      idClient: null,
      idContact: null,

      modalTitle: '',

      isFormBeingSent: false,

      listLicenses: {},
      listClients: [],
      listContacts: [],

      nameProductSearch: '',
      nameClientSearch: '',
      nameContactSearch: '',
      searchByDate: '',
      dateStartSearch: '',
      dateEndSearch: '',

      can,
    }
  },
  mounted() {
    this.getListLicenses()
    this.getListClients()
    this.getListContacts()
  },
  filters: {
    formatDate(date) {
      return !date ? '-' : moment(date).format('DD-MM-YYYY')
    }
  },
  methods: {
    setBackgoundColor(dateExpired, dateInstalled) {
      const installed = moment()
      const expiredInDays = installed.diff(dateExpired, 'days')
      if(!dateExpired) return 'bg-toinstall'
      if(expiredInDays <= -30 ) {
        return 'bg-installed'
      } else if(expiredInDays >= -30 && expiredInDays <=0) {
        return 'bg-byExpire'
      } else if(expiredInDays >= 1) {
        console.log(expiredInDays)
        return 'bg-expired'
      }
    },
    clearForm() {
      this.id = 0
      this.installationDate = moment().format('YYYY-MM-DD')
      this.expirationDate = moment().add(1,'month').format('YYYY-MM-DD')
      this.product = ''
      this.description = ''
      this.quantity = ''
      this.idClient = null
      this.idContact = null
      this.isFormBeingSent = false
    },
    closeModal() {
      $('#modal-license').modal('hide')
    },
    async createOrUpdate() {
      const formData = this.dataToBeBeingSent()
      this.isFormBeingSent = true
      try {
        const urlRedirect = !formData.id ? 'license-store' : 'license-update'
        await Axios.post(urlRedirect, formData)
        this.getListLicenses()
        this.closeModal()
        showAlertProccessSuccess()
      } catch (error) {
        if(error.response.data.errors) {
          const listErrors = normalizeLaravelErrorList(error.response.data.errors)
          this.showErrors(listErrors)
        }
        this.isFormBeingSent = false
      }
    },
    dataToBeBeingSent() {
      const params = {
        id: this.id,
        product: this.product,
        description: this.description,
        quantity: this.quantity,
        installationDate: this.installationDate,
        expirationDate: this.expirationDate,
        idClient: this.idClient || '',
        idContact: this.idContact || '',
      }
      return params
    },
    async getListClients() {
      try {
        const { data } = await listAllActives('customers')
        this.listClients = data
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    async getListContacts() {
      try {
        const { data } = await listAllActives('contacts')
        this.listContacts = data
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    async getListLicenses(page = 1) {
      try {
        const urlSearchParams = this.urlSearchParams()
        const { data } = await Axios.get(`list-licenses?${urlSearchParams}&page=${page}`)
        this.listLicenses = data
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    openModalCreate() {
      this.clearForm()
      $('#modal-license').modal('show')
    },
    openModalEdit({ id, product, description, quantity, installationDate, expirationDate, idClient, idContact}) {
      this.clearForm()
      this.modalTitle = 'Editar'
      this.id = id
      this.product = product
      this.description = description
      this.quantity = quantity
      this.installationDate = installationDate
      this.expirationDate = expirationDate
      this.idClient = idClient
      this.idContact = idContact
      $('#modal-license').modal('show')
    },
    showErrors(listErrors) {
      this.$toast.error({ component: ListErrorsComponent, props:  { listErrors } })
    },
    async updataStatus(data) {
      try {
        const params = {
          table: 'licenses',
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
      let params = new URLSearchParams({
        nameProductSearch: this.nameProductSearch,
        nameClientSearch: this.nameClientSearch,
        nameContactSearch: this.nameContactSearch,
        dateStartSearch: this.dateStartSearch,
        dateEndSearch: this.dateEndSearch
      })
      if(this.searchByDate) {
        params.append('searchByDate',this.searchByDate)
      }
      return params.toString()
    },
  },
}
