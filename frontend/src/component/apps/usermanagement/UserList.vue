<template>
  <div>
    
    <b-alert
      :variant="alertresetpass.variant"
      :show="alertresetpass.show">
      <div class="alert-body">
        <span>{{ alertresetpass.message }}</span>
      </div>
    </b-alert>

    
    
    <Table
      :items="items"
      :fields="fields"
      :meta="meta"
      :isbusy="busy"
      @per_page="handlePerPage"
      @pagination="handlePagination"
      @search="handleSearch"
      @sort="handleSort">

      <template v-slot:addbutton>
        <b-button
          type="button"
          v-if="$can('create', 'AppUserMgt')"
          variant="primary"
          @click="openAddForm">
          <span class="text-nowrap"> Tambah </span>
        </b-button>
      </template>

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>

      <template #cell(user)="data">
        <b-media>
          <template #aside>
            <b-avatar
              variant="primary"
              :src="data.item.avatar ? data.item.avatarlink : ''"
              :text="avatarText(data.item.name)"
              size="36"/>
          </template>
          <b-link class="font-weight-bold d-block text-nowrap">
            {{ data.item.name }}
          </b-link>
          <small>{{ data.item.nik }}</small>
        </b-media>
      </template>

      <template #cell(email)="data">
        {{ data.item.datalogin ? data.item.datalogin.email : 'n/a' }}
      </template>

      <template #cell(position)="data">
        {{ data.item.position ? data.item.position.name : '' }}
      </template>

      <template #cell(dept)="data">
        {{ data.item.position ? data.item.position.deptname.name : '' }}
      </template>

      <template #cell(stuser)="data">
        <b-form-checkbox
          class="custom-control-primary ml-1"
          @change="toggleSwitchUser(data.item.active, data.item.datalogin, data.item)"
          :checked="!!+data.item.active"
          switch>
        </b-form-checkbox>
      </template>

      <template #cell(accesslogin)="data">
        <b-form-checkbox
          :disabled="data.item.datalogin === null || data.item.active === 0"
          class="custom-control-primary ml-1"
          @change="toggleSwitchLogin(data.item.datalogin, data.item.datalogin.active)"
          :checked="data.item.datalogin !== null ? !!+data.item.datalogin.active : false"
          switch>
        </b-form-checkbox>
      </template>

      <template #cell(opsi)="data">
        <b-dropdown
          variant="link"
          no-caret>
          <template #button-content>
            <feather-icon
              icon="MoreVerticalIcon"
              size="16"
              class="align-middle text-body"/>
          </template>
          <b-dropdown-item
            disabled>
            <feather-icon icon="FileTextIcon" />
            <span class="align-middle ml-50">Details</span>
          </b-dropdown-item>
          <b-dropdown-item
            :disabled="!$can('edit', 'AppUserMgt')"
            :to="{ name: 'apps-usermgt-useredit', params: { id: data.item.nik } }">
            <feather-icon icon="EditIcon" />
            <span class="align-middle ml-50">Edit</span>
          </b-dropdown-item>
          <b-dropdown-item
            @click="handleResetPassword(data.item.nik)"
            :disabled="data.item.datalogin === null || !$can('resetpassuser', 'AppUserMgt')">
            <feather-icon icon="KeyIcon" />
            <span class="align-middle ml-50">Reset Password</span>
          </b-dropdown-item>
          <b-dropdown-item
            @click="handleDeleteUser()"
            v-if="userdata.admin === 1 && data.item.nik !== '9000901'" >
            <feather-icon icon="Trash2Icon" />
            <span class="align-middle ml-50">Hapus</span>
          </b-dropdown-item>
        </b-dropdown>
      </template>

      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>

    </Table>

  </div>
</template>

<script>
import { 
  BButton, 
  BMedia, BAvatar,
  BLink,
  BDropdown, BDropdownItem,
  BFormCheckbox,
  BAlert,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import { avatarText } from '@core/utils/filter'

export default {
  props: {
    userdata: {
      type: Object,
    }
  },
  components: {
    BButton, 
    BMedia, BAvatar,
    BLink,
    BDropdown, BDropdownItem,
    BFormCheckbox,
    BAlert,
    Table: () => import('@/component/utils/Datatable.vue'),
  },
  data() {
    return{
      // Table
      fields: [
        { key: 'user', label: 'User', sortable: false, thStyle: { width: "18%" }, thClass: 'text-left' },
        { key: 'email', label: 'Email', sortable: false, thStyle: { width: "18%" }, thClass: 'text-left' },
        { key: 'position', label: 'Jabatan', thStyle: { width: "17%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'dept', label: 'Departemen', thStyle: { width: "17%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'stuser', label: 'Status User', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'accesslogin', label: 'Login Akses', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'opsi', label: 'Opsi', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
      ],
      items: [],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'created_at',
      sortByDesc: false,
      busy: false,
      avatarText,
      alertresetpass: {
        variant: '',
        show: 'false',
        message: ''
      },
    }
  },
  methods: {
    handlePerPage(val){
      this.per_page = val
      this.fetchData()
    },
    handlePagination(val){
      this.current_page = val
      this.fetchData()
    },
    handleSearch(val){
      this.search = val
      this.fetchData() 
    },
    handleSort(val){
      this.sortBy = val.sortBy
      this.sortByDesc = val.sortByDesc
      this.fetchData()
    },
    fetchData(){
      this.busy = true
      http.get('user/all', { params: {page: this.current_page, per_page: this.per_page, q: this.search, sortby: this.sortBy, sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'} })
      .then((res) => {
        let receivedData = res.data.message
        this.items = receivedData.data
        this.meta = {
          total: receivedData.total,
          current_page: receivedData.current_page,
          per_page: receivedData.per_page,
          from: receivedData.from,
          to: receivedData.to
        }
        this.busy = false
      })
      .catch((e) => { console.error(e) })
    },
    openAddForm(){
      called.$emit('usermanagementUserAdd')
    },
    toggleSwitchUser(val, val2, val3){
      this.alertresetpass = {
        variant: '',
        show: false,
        message: ''
      }
      this.busy = true
      const oldStatusActive = !!+val
      const newStatusActive = +(!oldStatusActive)
      const datalogin = val2
      const data = val3
      http
      .patch('user/setactive/'+val3.id, { 
        newvalue: newStatusActive.toString(),
        nik: datalogin ? data.nik : null
      })
      .then((res) => {
        // console.log(res.data)
        this.fetchData()
        this.busy = false
      })
      .catch((e) => { 
        console.error(e)
        this.busy = false
        this.fetchData()
      })
    },
    toggleSwitchLogin(val, val2){
      this.alertresetpass = {
        variant: '',
        show: false,
        message: ''
      }
      this.busy = true
      // console.log(val)
      // console.log(val2)
      const datalogin = val
      const oldStatusLogin = !!+val2
      const newStatusLogin = +(!oldStatusLogin)
      http
      .patch('user/auth/update/active/'+datalogin.nik, { newvalue: newStatusLogin.toString() })
      .then((res) => {
        this.busy = false
        this.fetchData()
        // console.log(res.data)
      })
      .catch((e) => {
        this.busy = false
        this.fetchData()
        console.error(e)
      })
    },
    handleResetPassword(nik){
      this.alertresetpass = {
        variant: '',
        show: false,
        message: ''
      }
      this.busy = true
      http
      .post('user/auth/update/password/'+nik, { resetpassword: 'mig123!' })
      .then((res) => {
        // console.log(res)
        this.alertresetpass = {
          variant: 'success',
          show: true,
          message: res.data.message
        }
        this.busy = false
        this.fetchData()
      })
      .catch((e) => {
        console.error(e)
        this.alertresetpass = {
          variant: 'danger',
          show: true,
          message: e.response.data.errors.message
        }
      })
    },
    handleDeleteUser(){
      alert('Available soon.')
    }
  },
  mounted(){
    this.fetchData()
  },
  created(){
    // console.clear()
    called.$on('userlistfetchData', () => { this.fetchData() })
  }
}
</script>

<style>

</style>