import {
  listAllActives,
  showReloadWindowAlert
} from '../Service'
export default {
  name: 'QuotateCreateComponent',
  props: {
    state: Number
  },
  data() {
    return {
      id: 0,
      typeContact: 0,
      fileContact: null,
      idEnterprise: 0,

      enterprises: [],

      isLoading: true,
    }
  },
  async created() {
    await this.getListEnterprises()
    // await this.showOptionsSelectEnterprise()
  },
  methods: {
    async getListEnterprises() {
      try {
        const { data } = await listAllActives('enterprises')
        this.enterprises = data
        this.isLoading = false
      } catch (error) {
        await showReloadWindowAlert()
      }
    },
    fileUploadHandleChange() {
      const { files } = document.getElementById('fileContact')
      this.fileContact = files.length ? files[0] : null
    },
    async showOptionsSelectEnterprise() {
      this.isLoading = true
      const inputOptions = this.enterprises.reduce((acc,enterprise) => ({
        ...acc,
        [enterprise.id]: enterprise.fullName
      }),{})
      const { value: idEnterprise } = await this.Sweet.fire({
        icon: 'info',
        text: 'Seleccione una empresa',
        input: 'select',
        allowOutsideClick: false,
        allowEscapeKey: false,
        inputOptions,
        inputPlaceholder: 'Seleccione..',
        confirmButtonText: 'Continuar',
        confirmButtonColor: '#1b82ec',
        inputValidator: (value) => {
          return new Promise((resolve) => {
            if (value) {
              resolve()
            } else {
              resolve('Debe seleccionar una empresa')
            }
          })
        },
      })
      this.idEnterprise = idEnterprise
      this.isLoading = false
    },
  },
}
