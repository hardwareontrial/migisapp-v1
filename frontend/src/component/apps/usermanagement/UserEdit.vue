<template>
  
  <component :is="(edit && datauser === undefined || datauser === null) ? 'div' : 'b-card'">
  <b-overlay :show="overlay" rounded="sm" :opacity="0.90">

    <b-alert
      variant="danger"
      :show="edit && datauser === undefined || datauser === null">
      <h4 class="alert-heading">
        Error
      </h4>
      <div class="alert-body">
        Data tidak ditemukan !
      </div>
    </b-alert>

    <!-- {{ datauser_info }} -->
    <b-tabs
      pills
      v-if="datauser">

      <b-tab 
        active>
        <template #title>
          <feather-icon
            icon="InfoIcon"
            size="16"
            class="mr-0 mr-sm-50"/>
          <span class="d-none d-sm-inline">Informasi</span>
        </template>
        <user-edit-tab-info
          :user-info="datauser_info"
          @del_ext="deleteExtensionNo"
          class="mt-2 pt-75"/>
      </b-tab>

      <b-tab>
        <template #title>
          <feather-icon
            icon="UserIcon"
            size="16"
            class="mr-0 mr-sm-50"/>
          <span class="d-none d-sm-inline">Akun</span>
        </template>
        <user-edit-tab-account
          :user-nik="idnik"
          :user-account="datauser_akun"
          class="mt-2 pt-75"/>
      </b-tab>

      <b-tab
        v-if="datauser_akun">
        <template #title>
          <feather-icon
            icon="LockIcon"
            size="16"
            class="mr-0 mr-sm-50"/>
          <span class="d-none d-sm-inline">Izin Akses</span>
        </template>
        <user-edit-tab-permission
          :user-nik="idnik"
          :user-permissions="datauser_akun.permissions"
          :user-admin="datauser_akun.admin"
          class="mt-2 pt-75"/>
      </b-tab>

    </b-tabs>
  
  </b-overlay>
  
  </component>

</template>

<script>
import http from '@/customs/axios'
import {
  BTab, BTabs, BCard, BAlert, BLink, BRow, BCol, BButton, BOverlay,
} from 'bootstrap-vue'
import UserEditTabAccount from '@/component/apps/usermanagement/user-form/UserEditTabAccount.vue'
import UserEditTabInfo from '@/component/apps/usermanagement/user-form/UserEditTabInfo.vue'
import UserEditTabPermission from '@/component/apps/usermanagement/user-form/UserEditTabPermission.vue'

export default {
  components: {
    BTab, BTabs,
    BCard, 
    BAlert, 
    BLink,
    BRow, BCol,
    BButton,
    BOverlay,
    UserEditTabAccount,
    UserEditTabInfo,
    UserEditTabPermission,
  },
  data(){
    return{
      idnik: '',
      datauser: null,
      datauser_info: null,
      datauser_akun: null,
      edit: false,
      overlay: false,
    }
  },
  methods: {
    getdata(val){
      this.overlay = true
      http
      .get('user/detail', { 
        params: { nik: val }
      })
      .then((res) => { 
        // console.log(res)
        if(res.data.message === null || res.data.message === '') {
          this.datauser = undefined
          this.datauser_info = undefined
          this.datauser_akun = undefined
        }else{
          this.datauser = res.data.message
          this.datauser_info = this.datauser
          this.datauser_akun = this.datauser.datalogin
        }
        this.overlay = false
      })
      .catch((e) => { console.error(e) })
    },
    deleteExtensionNo(val){
      http
      .delete('phonebook/extension/data/'+val)
      .then((res) => { 
        // console.log(res.data)
        this.getdata(this.idnik)
      })
      .catch((e) => { console.error(e) })
    },
  },
  mounted() {
    called.$on('usereditGetData', (val) => { this.getdata(val) })
  },
  created() {
    if('id' in this.$route.params){
      this.edit = true
      this.idnik = parseInt(this.$route.params.id)
      this.getdata(this.idnik)
    }else{
      this.edit = false
    }
  }
}
</script>

<style>

</style>