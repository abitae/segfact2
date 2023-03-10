import axios from "axios";
import moment from "moment";
import {
  showAlertProccessSuccess,
  showReloadWindowAlert,
  normalizeLaravelErrorList,
  showErrorUpdateAlert,
  can
} from "../Service";
import ListErrorsComponent from '../ListErrorsComponent.vue';


export default {
  props:[
    'canView',
    'canEdit',
    'canCreate',
    'canUpdateStatus',
  ],
  data() {
    return {
      id: 0,
      name: '',

      banks: [],
      isLoading: true,
      sedingForm: false,

      search: {
        name: ''
      },
      can
    }
  },
  computed: {},
  filters: {
    parseDate(date) {
      return moment(date).format('DD-MM-Y hh:mm a')
    },
  },
  methods: {
    openModalEdit({id, name}) {
      this.id = id
      this.name = name
      $('#modal-bank').modal('show')
    },
    openModal() {
      $('#modal-bank').modal('show')
      this.clearForm()
    },
    hideModal() {
      $('#modal-bank').modal('hide')
    },
    searchParams() {
      return new URLSearchParams(this.search).toString()
    },
    async getBanks() {
      try {
        const { data } = await axios.get(`list-banks?${this.searchParams()}`);
        this.banks = data;
        this.isLoading = false
      } catch (error) {
        await showReloadWindowAlert();
        this.getBanks();
      }
    },
    initializeData(dataLoad = false) {
      this.clearForm()
      // this.isLoading = false;
      if(!dataLoad) return false;
      this.getBanks()
    },
    async sendForm() {
      try {
        // this.sedingForm = false
        const formData = {
          id: this.id,
          name: this.name
        }
        const urlDynamic = !this.id ? 'bank-store' : 'bank-update';
        // const data = {
        //   urlDynamic: !this.id ? 'bank-store' : 'bank-update',
        //   messages: !this.id ? 'Actualizado' : 'Editado'
        // }
        await axios.post(urlDynamic, formData);
        this.initializeData(true)
        $('#modal-bank').modal('hide');
        showAlertProccessSuccess()
      } catch (error) {
        const listErrors = normalizeLaravelErrorList(error.response.data.errors);
        this.showErrors(listErrors)
        // $('#modal-bank').modal('show');
        // this.sedingForm = false;
      }
    },
    clearForm() {
      this.id = 0;
      this.name =  '';
    },
    showErrors(listErrors) {
      this.$toast.error({ component: ListErrorsComponent, props:  { listErrors } });
    },
    async updataStatus(model) {
      try {
        const params = {
          table: 'banks',
          id: model.id,
          is_active: !model.is_active
        }
        await axios.post('update-status',params);
        model.is_active = !model.is_active;
        const status = model.is_active ? 'activado(a)' : 'desactivado(a)';
        this.$toast.success(`Fue ${status} correctamente.`);
      } catch (error) {
        showErrorUpdateAlert()
      }
    },
  },
  mounted() {
    this.initializeData(true);
  },
}
