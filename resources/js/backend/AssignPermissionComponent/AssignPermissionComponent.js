import Axios from 'axios'
import moment from 'moment'
import {
  showReloadWindowAlert,
  listAll,
  showAlertProccessSuccess,
  can,
  setFocus
} from '../Service'

export default {
  name: 'AssignPermissionComponent',
  props: [
    'canView',
    'canUpdateRole',
  ],
  data() {
    return {
      nameSearch: '',
      emailSearch: '',

      idRoles: [],
      idUser: 0,
      prevRol: '',

      listRoles: [],
      listUsers: {},
      can
    }
  },
  filters: {
    parseDate(date) {
      return date ? moment(date).format('DD.MM.Y - hh:mm a') : '-';
    },
  },
  methods: {
    obtainDataSearch() {
      const searchParams = new URLSearchParams({
        name: this.nameSearch,
        email: this.emailSearch,
      })
      return searchParams.toString()
    },
    async getListUsers(page = 1) {
      try {
        let searchParams = this.obtainDataSearch()
        let { data } = await Axios.get(`list-users?${searchParams}&page=${page}`)
        this.listUsers = data
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    async getListRoles() {
      try {
        const { data } = await listAll('roles')
        this.listRoles = data
      } catch (error) {
        await showReloadWindowAlert()
        window.location.reload()
      }
    },
    async setNewRole() {
      const params = {
        userId: this.idUser,
        roleIds: this.idRoles,
        prevRol: this.prevRol
      }
      try {
        await Axios.post('assing-role-to-user', params)
        this.loadData()
        this.closeModalSetRole()
        showAlertProccessSuccess()
      } catch (error) {

      }
    },
    openModalNewRole(user) {
      $("#modal-set-new-role").modal('show')
      this.idUser = user.id
      this.prevRol = !user.roles.length ? '' : user.roles[0].name;
      this.idRoles = !user.roles.length ? [] : user.roles.map(({ id }) => id);
      setFocus('list-roles', 0.5);
    },
    closeModalSetRole() {
      $("#modal-set-new-role").modal('hide')
    },
    loadData() {
      this.getListRoles()
      this.getListUsers()
    },
  },
  mounted() {
    this.loadData()
  },
}
