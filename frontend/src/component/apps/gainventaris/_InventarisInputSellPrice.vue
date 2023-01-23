<template>
  <b-modal
    id="md-inv-newloc"
    centered
    title="Masukkan Harga"
    v-model="show"
    no-close-on-backdrop
    no-close-on-esc
    hide-header-close>

    <small><span class="text-danger">*</span>Opsional</small>

    <b-form @submit.prevent>
      <b-form-group class="mt-1">
        <label for="newlocation">Harga Jual</label>
        <b-form-input
          id="newlocation"
          type="text"
          @keypress="isNumber($event)"
          v-model="hargajual"
        />
      </b-form-group>
    </b-form>

    <template #modal-footer>
      <b-button
        type="button"
        :disabled="processing"
        @click="show = false, hargajual = null"
        variant="outline-secondary">
        Batal
      </b-button>
      <b-button
        type="button"
        :disabled="processing"
        @click="submitSell"
        variant="primary">
        {{ processing ? 'Proses' : 'Lanjutkan' }}
      </b-button>
    </template>

  </b-modal>
</template>

<script>
import {
  BModal,
  BForm, BFormGroup, BFormInput,
  BButton,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    BModal,
    BForm, BFormGroup, BFormInput,
    BButton,
  },
  data(){
    return{
      show: false,
      hargajual: null,
      id: '',
      processing: false,
    }
  },
  methods: {
    openSell(val){
      this.show = true
      this.id = val
    },
    isNumber(e){
      e = (e) ? e : window.event;
      let cCode = (e.which) ? e.which : e.kCode;
      if ((cCode > 31 && (cCode < 48 || cCode > 57)) && (cCode < 40 || cCode > 41)) {
        e.preventDefault();
      } else {
        return true;
      }
    },
    submitSell(){
      this.processing = true
      http
      .delete('inventaris/sell/'+this.id, { hargajual: this.hargajual })
      .then((data) => {
        // console.log(data)
        this.$toast({
          component: ToastificationContent,
          props: {
            // title: data.status.toString(),
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: data.data.message
          },
        },
        { position: 'top-center' })
        this.processing = false
        this.hargajual = null
        this.show = false
        called.$emit('inventarislistFetchData')
      })
      .catch((e) => { 
        console.error(e) 
        this.processing = false
        this.hargajual = null
        this.show = false
        called.$emit('inventarislistFetchData')
      })
    }
  },
}
</script>

<style>

</style>