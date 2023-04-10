<template>
  <div>
    <div>
      <div class="d-flex mt-0">
        <feather-icon
          icon="UnlockIcon"
          size="19"/>
        <h4 class="mb-1 ml-50">
          Roles
        </h4>
      </div>
      <div class="d-flex mt-2">
        <feather-icon
          icon="UnlockIcon"
          size="19"/>
        <h4 class="mb-0 ml-50">
          Permissions
        </h4>
      </div>
      <b-row v-for="(permissions, i) in listPermissions" :key="i" style="margin-top: 10px;">
        <b-col cols="12">
          <strong><u> {{ i }} </u></strong>
        </b-col>
        <b-col
          style="margin-top: 5px;"
          cols="12"
          sm="4"
          lg="3"
          v-for="(permission, j) in permissions" :key="j">
          <!-- :disabled="userAdmin === 1 ? true : (['manage.all', 'read.Auth', 'read.ACL']).includes(permission.name)" -->
          <b-form-checkbox
            :disabled="userAdmin === 1 ? true : (['manage.all', 'read.Auth', 'read.ACL']).includes(permission.name)"
            :value="permission.name"
            v-model="formpermission.selectedPermission">
            {{ permission.text }} 
          </b-form-checkbox>
        </b-col>
      </b-row>
    </div>
    <b-row class="mt-1">
      <b-col>
        <b-button
          type="button"
          :disabled="processing"
          @click="submitUpdatePermission()"
          variant="outline-primary"
          class="mb-1 mb-sm-0 mr-0 mr-sm-1"
          :block="$store.getters['app/currentBreakPoint'] === 'xs'">
          Update
        </b-button>
        <b-button
          type="button"
          :disabled="processing"
          @click="closeForm"
          variant="danger"
          :block="$store.getters['app/currentBreakPoint'] === 'xs'">
          Tutup
        </b-button>
      </b-col>
    </b-row>

  </div>
</template>

<script>
import http from '@/customs/axios'
import {
  BFormGroup, BFormCheckboxGroup, BFormCheckbox,
  BCol, BRow,
  BButton,
} from 'bootstrap-vue'

export default {
  props: {
    userPermissions: {
      type: Array
    },
    userNik: {
      type: Number
    },
    userAdmin: {
      type: Number, String
    }
  },
  components: {
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormGroup,
    BCol, BRow,
    BButton,
  },
  data(){
    return{
      listPermissions: [],
      listRoles: [],
      processing: false,
      formpermission: {
        nik: '',
        selectedPermission: []
      },
      formrole: {
        nik: '',
        selectedRoles: []
      }
    }
  },
  methods: {
    closeForm(){
      this.listPermissions = [],
      this.listRoles = [],
      this.formpermission = {
        nik: this.userNik,
        selectedPermission: []
      }
      this.processing = false
      this.$router.replace({ name: 'apps-usermgt-list' })
    },
    getPermissionsList(){
      http
      .get('user/permission/data', { params: { f: 'form-list-permission' } })
      .then((res) => {
        // console.log(res.data)
        this.listPermissions = res.data.message
      })
      .catch((e) => { console.error(e) })
    },
    submitUpdatePermission(){
      // this.processing = true
      http
      .put('user/permission/update/'+this.userNik, this.formpermission, { params: { keyword: 'update-user-permission'} })
      .then((res) => {
        // console.log(res.data)
        this.closeForm()
        this.processing = false
      })
      .catch((e) => {
        console.error(e)
      })
    },
  },
  watch: {
    'userPermissions': {
      immediate: true,
      deep: true,
      handler(n){
        if(n.length > 0) {
          const result = n.map(({name})=> name)
          this.formpermission = {
            nik: this.userNik,
            selectedPermission: result
          }
        }
        else{
          this.formpermission = {
            nik: this.userNik,
            selectedPermission: []
          }
        }
      }
    }
  },
  computed: {
    userisadmin(){
      let userdata = JSON.parse(localStorage.getItem('userdata'))
      let isadmin = userdata.admin
      return isadmin
    },
  },
  mounted(){
    this.getPermissionsList()
  }
}
</script>

<style>

</style>