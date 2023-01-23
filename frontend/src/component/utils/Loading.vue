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
       <span class="ml-1">{{ message }}</span>
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
  components: {
    BButton,
    BModal,
    BSpinner,
  },
  data(){
    return{
      showmodal: false,
      message: '', 
    }
  },
  methods: {
    show(data){
      this.showmodal = data.show
      this.message = data.text
    },
    close(){
      this.showmodal = false
      this.message = ''
    }
  },
  created(){
    called.$on('showloading', (data) => this.show(data))
    called.$on('hideloading', () => this.close())
  },
  beforeDestroy(){
    called.$off('showloading', (data) => this.show(data))
    called.$off('hideloading', () => this.close())
  }
}
</script>

<style>

</style>