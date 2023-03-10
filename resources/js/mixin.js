window.Vue = require('vue');
import moment from 'moment';
import Swal from 'sweetalert2';

Vue.mixin({
  computed: {
    END_POINT() { return document.head.querySelector("meta[name='end-point']").content },
    dateInitWorking() {  return '2021-11-01' },
    currentDay() {  return moment().format('YYYY-MM-DD') },
    authenticated() { return JSON.parse(document.head.querySelector("meta[name='authenticated-user']").content) },
    audioSuccess() {
      const audio = new Audio(`${this.END_POINT}/backend/assets/audio/audio_success.mp3`);
      audio.volume = 0.2;
      return audio;
    },
    audioError() {
      const audio = new Audio(`${this.END_POINT}/backend/assets/audio/audio_error.mp3`);
      audio.volume = 0.2;
      return audio;
    },
    Sweet() {
      return Swal
    }
  }
})
