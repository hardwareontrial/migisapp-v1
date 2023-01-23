<template>
  <b-modal
    id="md-inv-newloc"
    centered
    title="Tambah Lokasi"
    v-model="show"
    no-close-on-backdrop
    no-close-on-esc
    hide-header-close>

    <b-alert
      variant="danger"
      :show="error.show">
      <div class="alert-body">
        <span><strong>{{ error.code }}</strong> {{ error.message }}</span>
      </div>
    </b-alert>

    <b-form @submit.prevent>
      <b-form-group class="mt-1">
        <label for="newlocation">Nama</label>
        <b-form-input
          id="newlocation"
          type="text"
          v-model="form.name"
        />
        <small><span class="text-danger">*</span>Input diatas jika di list tidak ada.</small>
      </b-form-group>
    </b-form>

    <template #modal-footer>
      <b-button
        type="button"
        :disabled="processing"
        @click="closeLocation"
        variant="outline-secondary">
        Batal
      </b-button>
      <b-button
        type="button"
        :disabled="processing"
        @click="submitAdd"
        variant="primary">
        {{ processing ? 'Proses' : 'Tambah' }}
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
        name: '',
      },
      processing: false,
      error: {
        code: '',
        message: '',
        show: false
      }
    }
  },
  methods: {
    openLocation(){
      this.show = true
    },
    closeLocation(){
      this.show = false
      this.form = {
        name: ''
      }
      this.error = {
        code: '',
        message: '',
        show: false
      }
    },
    submitAdd(){
      this.processing = true
      this.error = {
        code: '',
        message: '',
        show: false
      }
      http
      .post('inventaris/new-location', this.form)
      .then((data) => {
        // console.log(data.data.message)
        called.$emit('inventarisformListLokasi')
        this.processing = false
        this.closeLocation()
      })
      .catch((e) => { 
        console.error(e)
        this.error = {
          code: e.response.status,
          message: e.response.data.errors.name[0],
          show: true
        }
        this.processing = false

      })
    }
  },
  mounted(){
    called.$on('gainventarisOpenAddLocation', () => { this.openLocation() } )
  }
}
</script>

<style>

</style>