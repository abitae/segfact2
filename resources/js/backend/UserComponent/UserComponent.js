import Axios from "axios"
import moment from "moment"
import ListErrorsComponent from '../ListErrorsComponent.vue'
import LoaderComponent from '../LoaderComponent.vue'

import {
  normalizeLaravelErrorList,
  showErrorUpdateAlert,
  showReloadWindowAlert,
  can,
  showAlertProccessSuccess,
  generateClipboard
} from "../Service"

export default {
  name: 'UserComponent',
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
      name: '',
      nickName: '',
      email: '',
      cuota: '',
      isSeller: true,

      password: '',
      repeatPassword: '',
      nameSearch: '',
      emailSearch: '',
      listDataUsers: {},
      can,
      isLoading: true,
      isUpdatePassword: false,
    }
  },
  computed:{
    generatePassword() {
      return "fulltecnologia"+moment().format('Y')
    }
  },
  filters:{
    parseDate(date) {
      if(!date) return '-'
      return moment(date).format('DD-MM-Y hh:mm a')
    },
  },
  methods: {
    closeModal() {
      $('#modal-user').modal('hide')
    },
    generateClipboard(defaultPassword) {
      generateClipboard(defaultPassword)
    },
    openModalCreateUser() {
      this.modalTitle = 'Nuevo'
      $('#modal-user').modal('show')
      this.claerData()
    },
    openModalEditUser({id, nickName, name, email, cuota, is_seller}) {
      $('#modal-user').modal('show')
      this.modalTitle = 'Editar'
      this.id = id
      this.name = name
      this.nickName = nickName
      this.email = email
      this.cuota = cuota
      this.isSeller = is_seller
    },
    claerData() {
      this.id = 0
      this.name = ''
      this.nickName = ''
      this.email = ''
      this.cuota = ''
      this.isSeller = true
      this.password = ''
      this.repeatPassword = ''
    },
    obtainDataSearch() {
      return new URLSearchParams({
        name: this.nameSearch,
        email: this.emailSearch,
      }).toString()
    },
    obtainData() {
      const data = {
        id: this.id,
        name: this.name,
        nickName: this.nickName,
        email: this.email,
        cuota: this.cuota,
        is_seller: this.isSeller,
        password: this.password,
        repeatPassword: this.repeatPassword,
        isUpdatePassword: this.isUpdatePassword
      }
      if(this.id) {
        if(this.isUpdatePassword) {
          if(this.validateData(data)) {
            this.showErrors(this.validateData(data))
            return false
          }
        }
      }
      return data
    },
    validateData({password, repeatPassword}) {
      let listErrors = new Array()
      if(repeatPassword != password) {
        listErrors.push("Las contraseñas no coinciden")
      }
      if(listErrors.length > 0) return listErrors
    },
    async getListDataUsers(page = 1) {
      try {
        let searchParams = this.obtainDataSearch()
        let { data } = await Axios.get(`list-users?${searchParams}&page=${page}`)
        this.listDataUsers = data
        this.isLoading = false
      } catch (error) {
        showReloadWindowAlert()
        this.isLoading = false
      }
    },
    async createOrUpdate() {
      const params = this.obtainData()
      if(!params) return
      this.isLoading = true
      try {
        const urlDynamic = !params.id ? 'user-store' : 'user-update'
        await Axios.post(urlDynamic,params)
        $('#modal-user').modal('hide')
        this.isLoading = false
        this.getListDataUsers()
        showAlertProccessSuccess()
      } catch (error) {
        const listErrors = normalizeLaravelErrorList(error.response.data.errors)
        this.showErrors(listErrors)
      }
    },
    async updataStatus(user) {
      try {
        const params = {
          table: 'users',
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
  mounted() {
    this.getListDataUsers()
  },
}
