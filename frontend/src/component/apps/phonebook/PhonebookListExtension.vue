<template>
  <div>
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
          v-if="$can('create', 'AppPhoneExt')"
          style="margin-right: 10px;"
          variant="primary"
          @click="openform()">
          <span class="text-nowrap">Tambah</span>
        </b-button>
        <b-dropdown
          v-if="false"
          id="pbListExtensionExport"
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          text="Export"
          variant="primary">
          <b-dropdown-item>Excel</b-dropdown-item>
          <b-dropdown-item>Print</b-dropdown-item>
        </b-dropdown>
      </template>

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>

      <template #cell(extension)="data">
        {{ data.item.ext }}
      </template>

      <template #cell(name)="data">
        {{ data.item.user.name }}
      </template>

      <template #cell(position)="data">
        {{ data.item.user.position ? data.item.user.position.name : '' }}
      </template>

      <template #cell(lokasi)="data">
        {{ resloc[1][data.item.lokasi_id] }}
      </template>

      <template #cell(opsi)="data">
        <div class="text-nowrap">
          <b-button
            type="button"
            :disabled="!$can('edit', 'AppPhoneExt')"
            @click="handleEdit(data.item.id)"
            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
            :variant="$can('edit', 'AppPhoneExt') ? 'flat-primary' : 'flat-secondary'"
            class="btn-icon">
            <feather-icon icon="EditIcon" size="16"/>
          </b-button>
          <b-button
            type="button"
            :disabled="!$can('delete', 'AppPhoneExt')"
            @click="handleDelete(data.item.id)"
            v-ripple.400="'rgba(40, 199, 111, 0.15)'"
            :variant="$can('delete', 'AppPhoneExt') ? 'flat-danger' : 'flat-secondary'"
            class="btn-icon">
            <feather-icon icon="TrashIcon" size="16"/>
          </b-button>
        </div>
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
  BDropdown, BDropdownItem, 
} from 'bootstrap-vue'
import http from '@/customs/axios'
import ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    BButton,
    BDropdown, BDropdownItem, 
    Table: () => import('@/component/utils/Datatable.vue'),
  },
  directives: { ripple },
  data(){
    return{
      fields: [
        { key: 'extension', label: 'Ext', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'name', label: 'Nama', thStyle: { width: "25%" } },
        { key: 'position', label: 'Posisi', thStyle: { width: "15%" } },
        { key: 'lokasi', label: 'Lokasi', thStyle: { width: "15%" } },
        { key: 'opsi', label: 'Opsi', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center', visible: false },
      ],
      items: [],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'created_at',
      sortByDesc: false,
      busy: false,
      lokasiList: [
        { value: 1, text: 'Atas' },
        { value: 2, text: 'Bawah' },
      ],
      resloc: [
        { 1: '1', 2: '2' },
        { 1: 'Atas', 2: 'Bawah' }
      ]
    }
  },
  methods: {
    handlePerPage(val){
      this.per_page = val
    },
    handlePagination(val){
      this.current_page = val
    },
    handleSearch(val){
      this.search = val
    },
    handleSort(val){
      this.sortBy = val.sortBy
      this.sortByDesc = val.sortByDesc
    },
    openform(){
      called.$emit('phonebookaddOpenFormInt')
      called.$emit('phonebookaddExtResolveLocation', this.lokasiList)
    },
    handleFetchData(){
      this.busy = true
      // let current_page = this.search == '' ? this.current_page : 1
      http
      .get('phonebook/extension/data', {
        params: {
          // page: current_page,
          page: this.current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      })
      .then((res) => {
        let receivedData = res.data.message
        // console.log(receivedData)
        this.items = receivedData.data
        this.meta = {
          total: receivedData.total,
          current_page: receivedData.current_page,
          per_page: receivedData.per_page,
          from: receivedData.from,
          to: receivedData.to
        }
      })
      .catch((e) => { 
        console.error(e)
      })
      .finally(() => { this.busy = false })
    },
    handleEdit(val){
      called.$emit('phonebookaddExtResolveLocation', this.lokasiList)
      called.$emit('phonebookaddOpenEditFormInt', val)
    },
    handleDelete(val){
      this.busy = true
      http
      .delete('phonebook/extension/data/'+val)
      .then((res) => {
        this.busy = false
        this.handleFetchData()
        this.$toast({
          component: ToastificationContent,
          props: {
            // title: data.status.toString(),
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: res.data.message
          },
        },
        { position: 'top-right' })
      })
      .catch((e) => {
        this.busy = false
        this.handleFetchData()
        this.$toast({
          component: ToastificationContent,
          props: {
            // title: data.status.toString(),
            icon: 'XCircleIcon',
            variant: 'danger',
            title: e.response.data.message
          },
        },
        { position: 'top-right' })
      })
    },
  },
  mounted(){
    this.handleFetchData()
    called.$on('phonebooklistExtensionHandleFetchData', () => { this.handleFetchData() })
  },
  created(){
    //
  },
  computed: {
    //
  }
}
</script>

<style>

</style>