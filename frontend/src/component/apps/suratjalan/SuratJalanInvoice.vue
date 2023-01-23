<template>
  <b-modal
    id="md-sj-invoice"
    centered
    title="Mark Invoice"
    v-model="show"
    no-close-on-backdrop
    no-close-on-esc
    hide-header-close>
    
    <b-form>
      <b-form-group>
        <label for="deliveryno">Delivery No</label>
        <b-form-input
          id="deliveryno"
          type="text"
          v-model="form.deliveryno"
          disabled
        />
      </b-form-group>
      <b-form-group>
        <label for="invoiceno">Invoice No</label>
        <b-form-input
          type="text"
          placeholder="Invoice Number"
          v-model="form.invoiceno"
        />
      </b-form-group>
    </b-form>

    <b-alert 
      :show="showalert"
      variant="danger"
      class="mt-2">
      <div class="alert-body">
        <feather-icon
          class="mr-25"
          size="16"
          icon="XCircleIcon"
        />
        <span class="ml-25">{{ messagealert }}</span>
      </div>
    </b-alert>

    <template #modal-footer>
      <b-button
        type="button"
        :disabled="processing"
        variant="outline-secondary"
        @click="closeInvoice">
        Batal
      </b-button>
      <b-button
        type="button"
        :disabled="processing"
        variant="primary"
        @click="submitInvoice">
        Proses
      </b-button>
    </template>

  </b-modal>
</template>

<script>
import {
  BModal,
  BForm, BFormGroup, BFormInput,
  BButton,
  BAlert,
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  components: {
    BModal,
    BForm, BFormGroup, BFormInput,
    BButton,
    BAlert,
  },
  data(){
    return{
      show: false,
      form: {
        deliveryno: '',
        invoiceno: ''
      },
      processing: false,
      showalert: false,
      messagealert: '',
    }
  },
  methods: {
    openInvoice(val){
      this.show = true
      this.form.deliveryno = val
    },
    closeInvoice(){
      this.form.deliveryno = ''
      this.form.invoiceno = ''
      this.messagealert = ''
      this.processing = false
      this.showalert = false
      called.$emit('suratjalanlistFetchData')
      this.show = false
    },
    submitInvoice(){
      this.processing = true
      http
      .post('deliverynote/data/'+this.form.deliveryno, this.form, { params: {keyword: 'update-post-invoice'} })
      .then((data) => {
        // console.log(data)
        this.processing = false
        this.closeInvoice()
      })
      .catch((e) => {
        console.error(e)
        this.processing = false
        this.showalert = true
        this.messagealert = e.response.data.message
      })
    }
  },
  mounted(){
    called.$on('suratjalanInvoiceShow', (val) => { this.openInvoice(val) } )
  },
}
</script>

<style>

</style>