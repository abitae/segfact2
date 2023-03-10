import Axios from "axios";
import moment from "moment";
import {
  showReloadWindowAlert,
  showAlertProccessSuccess,
  showErrorUpdateAlert,
  can
} from '../Service';

export default {
  props:[
    'canView',
    'canEdit',
  ],
  data() {
    return {
      check_all: false,

      idRole: 0,
      currentRol: {},
      name: '',

      selectedPermits: [],

      listRoles: [],
      listPermissions: [],

      can,
    }
  },
  filters: {
    parseDate(date) {
      return moment(date).format('DD.MM.Y - hh:mm a')
    },
  },
  watch: {
    check_all(newValue) {
      if(newValue){
        this.selectedPermits = this.listPermissions.map( ({id}) => id)
      } else {
        this.selectedPermits = []
      }
    },
  },
  methods: {
    async sendForm() {
      try {
        await axios.post('role-store',{ name: this.name })
        showAlertProccessSuccess()
        this.loadData()
      } catch (error) {

      }
    },
    openModalRole () {
      $('#modal-role').modal('show')
    },
    closeModal() {
      $('#modal-role').modal('hide')
    },
    asignPermission(role) {
      this.resetAsignPermissions()
      this.currentRol = role
      this.idRole = role.id
      if(!role.permissions_count) return
      this.selectedPermits = role.permissions.map( ({id}) => id)
      this.check_all = this.listPermissions.length == role.permissions.length
    },
    resetAsignPermissions() {
      this.idRole = 0
      this.check_all = false
      this.selectedPermits = []
      this.currentRol = {}
    },
    async getListRoles() {
      try {
        const { data } = await Axios.get('list-roles')
        this.listRoles = data
      } catch (error) {
        await showReloadWindowAlert()
        this.getListRoles()
      }
    },
    async getListPermissions() {
      try {
        const { data } = await Axios.get('list-permissions')
        this.listPermissions = data
      } catch (error) {
        await showReloadWindowAlert()
        this.getListPermissions()
      }
    },
    async saveRolWithPermissions() {
      try {
        await axios.post('asign-permission-to-role', {
          permissions: this.selectedPermits,
          role_id: this.idRole
        })
        this.loadData()
        this.resetAsignPermissions()
        showAlertProccessSuccess()
      } catch (err) {
        showErrorUpdateAlert()
      }
    },
    loadData() {
      this.getListRoles()
      this.getListPermissions()
    },
  },
  mounted() {
    this.loadData()
  },
}
