<template>
  <b-modal
    :title="code"
    size="lg"
    v-model="show"
    no-close-on-backdrop
    no-close-on-esc
    hide-header-close>
    <b-alert
      :variant="alert.variant"
      :show="alert.show">
      <div class="alert-body">
        <span>Data tidak ditemukan !</span>
      </div>
    </b-alert>
    <b-overlay :show="detail === null" rounded="sm" :opacity="0.90">
      <b-tabs>
        <b-tab active>
          <template #title>
            <span>Info</span>
          </template>
          <detail-data 
            :detail-data="detail"
            />
        </b-tab>
        <b-tab>
          <template #title>
            <span>Riwayat</span>
          </template>
          <log 
            :detail-log="logs"
            :user-logged-id="user_logged_id"/>
        </b-tab>
      </b-tabs>
    </b-overlay>
    <template #modal-footer>
      <b-button-group>
        <b-button
          v-if="detail && logs.length > 0"
          @click="editdata"
          type="button"
          variant="flat-primary"
          class="btn-icon">
          <feather-icon icon="Edit2Icon" size="16"/>
        </b-button>
        <b-button
          v-if="isloggedin"
          type="button"
          variant="flat-danger"
          class="btn-icon"
          @click="closeModal()">
          <feather-icon icon="XIcon" size="16"/>
        </b-button>
      </b-button-group>
    </template>
  </b-modal>
</template>

<script>
import {
  BModal,
  BButton, BButtonGroup,
  BTabs, BTab,
  BAlert,
  BOverlay,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import Log from '@/component/apps/gainventaris/_InventarisDetailLog.vue'
import DetailData from '@/component/apps/gainventaris/_InventarisDetailData.vue'

export default {
  components: {
    BModal,
    BButton, BButtonGroup,
    BTabs, BTab,
    Log, DetailData,
    BAlert,
    BOverlay,
  },
  data(){
    return{
      show: false,
      detail: null,
      logs: [],
      alert: {
        show: false,
        variant: 'primary'
      },
      code: '',
      user_logged_id: 0,
    }
  },
  methods: {
    getdetail(val){
      http.get('inventaris/data/'+val)
      .then((res) => { 
        this.detail = res.data
        this.logs = res.data.logs
        this.code = res.data.kd_brg
      })
      .catch((e) => { 
        console.error(e)
        if(e.response.status === 500 || e.response.status === 404){
          this.alert = {
            show: true,
            variant: 'danger'
          }
        }
      })
    },
    closeModal(){
      this.show = false
      this.detail = null
    },
    showDetail(id){
      this.show = true
      this.id = id
      this.getdetail(id)
    },
    editdata(){
      if(this.isloggedin){
        this.$router.push({ name: 'apps-inventaris-edit', params: { id: this.code.split(' ').join('-') }, query: { q: this.id } })
        this.show = false
      }else{
        this.$router.push({ name: 'login', query: { redirect: {name: 'apps-inventaris-edit', params: { id: this.code.split(' ').join('-') }, query: { q: this.id }} } })
        this.show = false
      }
    },
  },
  created(){
    if(this.$route.name === 'apps-inventaris-showfromqr'){
      const id = this.$route.params.id
      this.showDetail(id)
    }else{
      called.$on('inventarisdetailshow', (id) => { this.showDetail(id) })
    }
  },
  computed: {
    isloggedin(){
      let statustoken = localStorage.getItem('token')
      let statususerdata = JSON.parse(localStorage.getItem('userdata'))
      if(statustoken && statususerdata){
        this.user_logged_id = statususerdata.detailuser.id
        return true
      }
      return false
    }
  }
}
</script>

<style>

</style>