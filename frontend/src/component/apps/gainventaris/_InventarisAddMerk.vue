<template>
  <b-modal
    id="md-inv-newloc"
    centered
    title="Tambah Merk"
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
        <label for="newmerk">Nama</label>
        <b-form-input
          id="newmerk"
          type="text"
          v-model="form.name"
        />
      <small><span class="text-danger">*</span>Input dibawah jika di list tidak ada.</small>
      </b-form-group>
    </b-form>

    <template #modal-footer>
      <b-button
        type="button"
        :disabled="processing"
        @click="closeAddMerk"
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
    openAddMerk(){
      this.show = true
    },
    closeAddMerk(){
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
      this.error = {
        code: '',
        message: '',
        show: false
      }
      this.processing = true
      http
      .post('inventaris/new-merk', this.form)
      .then((data) => {
        // console.log(data.data.message)
        called.$emit('inventarisformListMerk')
        this.processing = false
        this.closeAddMerk()
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
    called.$on('gainventarisOpenAddMerk', () => { this.openAddMerk() } )
  }
}
</script>

<style>

</style>