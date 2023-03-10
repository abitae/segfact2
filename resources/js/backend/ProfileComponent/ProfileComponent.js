import LoaderComponent from '../LoaderComponent';
import ListErrorsComponent from '../ListErrorsComponent';

import Axios from 'axios';
import {
  normalizeLaravelErrorList,
  showReloadWindowAlert,
  showAlertProccessSuccess
} from '../Service';

export default {
  components: { LoaderComponent, ListErrorsComponent },
  data() {
    return {

      id: 0,
      nickName: '',
      name: '',
      email: '',
      currentPassword: '',
      newPassword: '',
      is_active: false,
      hasChangePassword: false,

      isLoader: true,
    }
  },
  mounted() {
    this.getProfileData();
  },
  methods: {
    async getProfileData() {
      try {
        const { data } = await Axios.get('profile-data');
        this.id = data.id;
        // this.name = data.name;
        this.nickName = data.nickName;
        this.email = data.email;
        this.is_active = data.is_active;
        this.isLoader = false;
      } catch (error) {
        this.isLoader = false;
        showReloadWindowAlert();
      }
    },
    async updateProfile(e) {
      const formData = new FormData(e.target);
      try {
        const { data } = await Axios.post('profile-update',formData);
        await showAlertProccessSuccess();
        window.location.reload();
      } catch (error) {
        const listErrors = error?.response?.data?.errors || [error?.response?.data?.message];
        const listNormalizeErrors = normalizeLaravelErrorList(listErrors)
        this.showErrors(listNormalizeErrors)
      }
    },
    showErrors(listErrors) {
      this.$toast.error({ component: ListErrorsComponent, props:  { listErrors } });
    }
  },
}
