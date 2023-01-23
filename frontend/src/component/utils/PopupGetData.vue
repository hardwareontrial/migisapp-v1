<template>
  <div>
    <b-modal
      v-model="showmodal"
      hide-header
      hide-header-close
      no-close-on-backdrop
      no-close-on-esc
      hide-footer>
       <b-spinner label="Spinning" small></b-spinner>
       <span class="ml-1">Sedang Memuat...</span>
    </b-modal>
  </div>
</template>

<script>
import { 
  BButton, 
  BModal, 
  BSpinner,
} from 'bootstrap-vue'

export default {
  props: {
    showmodal: {
      default: true,
      type: Boolean,
    }
  },
  components: {
    BButton,
    BModal,
    BSpinner,
  },
  data(){
    return{
      // showmodal: false,
    }
  }
}
</script>

<style>

</style>