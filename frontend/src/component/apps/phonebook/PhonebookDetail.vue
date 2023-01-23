<template>
  <b-modal
    :title="`Data Kontak ${title}`"
    size="lg"
    centered
    lazy
    v-model="show"
    no-close-on-backdrop
    no-close-on-esc
    hide-header-close>
    <table class="table">
      <tbody>
        <tr>
          <td colspan="3" class="text-center"><strong>INFO</strong></td>
        </tr>
        <tr>
          <td style="width: 20%;">Nama</td>
          <td style="width: 1%;"> :</td>
          <td>
            <span v-if="data.type === 1">{{ data.name }}</span>
            <span v-else>{{ data.company }}</span>
          </td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td> :</td>
          <td>
            <span v-if="data.type === 1">{{ data.address }}</span>
            <span v-else>{{ data.company_address }}</span>
          </td>
        </tr>
          <tr>
          <td>Kota</td>
          <td> :</td>
          <td>{{ data.city }}</td>
        </tr>
        <tr>
          <td>Email</td>
          <td> :</td>
          <td>{{ data.email }}</td>
        </tr>
        <tr>
          <td>Note</td>
          <td> :</td>
          <td>{{ data.keterangan }}</td>
        </tr>
        <tr>
          <td colspan="3" class="text-center"><strong>DETAIL</strong></td>
        </tr>
        <tr v-for="(d, i) in data.inputs" :key="d.id">
          <td>Nomor ({{ i +1 }})</td>
          <td> :</td>
          <td>{{ d.number }} <span v-if="d.pic">( {{ d.pic }} )</span></td>
        </tr>
      </tbody>
    </table>
    <template #modal-footer>
      <b-button
        type="button"
        variant="primary"
        @click="closeModal()"
        class="float-right"> Tutup
      </b-button>
    </template>
  </b-modal>
</template>

<script>
import {
  BModal,
  BButton,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import ripple from 'vue-ripple-directive'

export default {
  components: {
    BModal,
    BButton,
  },
  directives: { ripple },
  data(){
    return{
      show: false,
      title: '',
      data: {
        name: '',
        address: '',
        company: '',
        company_address: '',
        email: '',
        city: '',
        keterangan: '',
        inputs: [],
      },
    }
  },
  methods: {
    openModal(val){
      this.show = true
      this.fetchData(val)
    },
    closeModal(){
      this.show = false
      this.data = {
        type: '',
        name: '',
        address: '',
        company: '',
        company_address: '',
        email: '',
        city: '',
        keterangan: '',
        inputs: [],
      }
    },
    fetchData(val){
      this.title = val.name ? val.name : val.company_name
      this.data = {
        type: val.type,
        name: val.name,
        address: val.address,
        company: val.company_name,
        company_address: val.company_address,
        email: val.email,
        city: val.city,
        keterangan: val.note,
        inputs: val.detail,
      }
    },
  },
  mounted(){
    called.$on('phonebookdetailOpenModal', (value) => { this.openModal(value) })
  }
}
</script>

<style>
  
</style>