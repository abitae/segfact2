import Axios from "axios";
import ListErrorsComponent from '../ListErrorsComponent.vue';
import LoaderComponent from '../LoaderComponent.vue';

import {
  normalizeLaravelErrorList,
  showErrorUpdateAlert,
  showReloadWindowAlert,
  can,
  consultDocumentSunat,
  generateClipboard
} from "../Service";

export default {
  name: 'CustomerComponent',
  props: [
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

      nroDocumentSearch: '',
      fullNameSearch: '',
      addressSearch: '',
      emailSearch: '',

      listDataCustomers: {},
      can,
      isLoading: true,

      isSearchDocumentSunat: false,
    }
  },
  created() {

  },
  mounted() {
    this.getListDataCustomers()
  },
  methods: {
    copiedAtClipboard(nroDocument) {
      generateClipboard(nroDocument)
    },
    hideModal() {
      $("#modal-supplier").modal('hide')
    },
    selectTypeDocumentHandleChange() {
      this.name = '';
      this.lastName = '';
      this.fullName = '';
      this.nroDocument = '';
    },
    showTextAccordingDocumentType(typeDocument) {
      return typeDocument == '06' ? 'RUC' : 'DNI';
    },
    async searchDocument() {
      this.isSearchDocumentSunat = true;
      try {
        const paramsConsult = {
          type: this.typeDocument,
          nroDocument: this.nroDocument
        }
        const { data } = await consultDocumentSunat(paramsConsult);

        if(data.respuesta != 'ok') throw {error: data.mensaje};
        if('ruc' in data) {
          this.fullName = data.razon_social;
          this.address = data.direccion;
          this.name = '';
          this.lastName = '';
        } else {
          this.name = data.nombres;
          this.lastName = `${data.ap_paterno} ${data.ap_materno}`;
          this.fullName = '';
          this.address = '';
        }
        this.isSearchDocumentSunat = false;
        this.audioSuccess.play();
      } catch ({error}) {
        this.audioError.play();
        this.Sweet.fire({
          icon: 'warning',
          title: '¡Alerta!',
          text: error,
          confirmButtonText: 'Correjir'
        })
        this.isSearchDocumentSunat = false;
      }
    },
    openModalCreateCustomer() {
      this.modalTitle = 'Nuevo';
      $("#modal-supplier").modal('show')
      this.claerData();
    },
    openModalEditCustomer({id, email,typeDocument, nroDocument, name, lastName, fullName, address, nroPhone}) {
      this.modalTitle = 'Editar';
      $("#modal-supplier").modal('show')
      this.id = id;
      this.typeDocument = typeDocument;
      this.nroDocument = nroDocument;
      this.name = name;
      this.lastName = lastName;
      this.fullName = fullName;
      this.address = address;
      this.email = email;
      this.nroPhone = nroPhone;
    },
    claerData() {
      this.id = 0;
      this.typeDocument = '';
      this.nroDocument = '';
      this.name = '';
      this.lastName = '';
      this.fullName = '';
      this.address = '';
      this.email = '';
      this.nroPhone = '';
    },
    obtainDataSearch() {
      const searchParams = new URLSearchParams({
        nroDocumentSearch: this.nroDocumentSearch,
        fullNameSearch: this.fullNameSearch,
        addressSearch: this.addressSearch,
        emailSearch: this.emailSearch
      });
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
        nroPhone: this.nroPhone
      }
      return data;
    },
    async getListDataCustomers(page = 1) {
      try {
        let searchParams = this.obtainDataSearch();
        let { data } = await Axios.get(`list-customers?${searchParams}&page=${page}`);
        this.listDataCustomers = data;
        this.isLoading = false;
      } catch (error) {
        showReloadWindowAlert();
        this.isLoading = false;
      }
    },
    async createOrUpdate() {
      const params = this.obtainData()
      if(!params) return false;

      try {
        const url_redirect = params.id == 0 ? 'customer-store' : 'customer-update';
        const { data } = await Axios.post(url_redirect,params)
        const activity = params.id == 0 ? 'creado' : 'actualizado';
        $("#modal-supplier").modal('hide')
        await this.Sweet.fire({
          icon: 'success',
          title: 'Proceso exitoso',
          text: `El cliente fue ${activity} correctamente.`,
          confirmButtonText: 'Aceptar'
        })
        window.location.reload()
      } catch (error) {
        const listErrors = normalizeLaravelErrorList(error.response.data.errors);
        this.showErrors(listErrors)
      }
    },
    async updataStatus(user) {
      try {
        const params = {
          table: 'customers',
          id: user.id,
          is_active: !user.is_active
        }
        await Axios.post('update-status',params);
        user.is_active = !user.is_active;
        const status = user.is_active ? 'activado(a)' : 'desactivado(a)';
        this.$toast.success(`Fue ${status} correctamente.`);
      } catch (error) {
        showErrorUpdateAlert()
      }
    },
    showErrors(listErrors) {
      this.$toast.error({ component: ListErrorsComponent, props:  { listErrors } });
    }
  },
}
