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

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>

      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>
      
      <template #cell(name)="data">
        {{ data.item.text }}
      </template>

      <!-- <template #cell(description)="data">
        {{ data.item.description }}
      </template> -->

      <template #cell(option)>
        <b-dropdown
          variant="link"
          no-caret>
          <template #button-content>
            <feather-icon
              icon="MoreVerticalIcon"
              size="16"
              class="align-middle text-body"/>
          </template>
          <b-dropdown-item>
            <feather-icon icon="FileTextIcon" />
            <span class="align-middle ml-50">Details</span>
          </b-dropdown-item>
        </b-dropdown>
      </template>

    </Table>
  <!-- {{ items }} -->
  </div>
</template>

<script>
import { 
  BButton, 
  BDropdown, BDropdownItem,
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  components: {
    Table: () => import('@/component/utils/Datatable.vue'),
    BButton,
    BDropdown, BDropdownItem,
  },
  data(){
    return{
      items: [],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'created_at',
      sortByDesc: false,
      busy: false,
      fields: [
        { key: 'name', label: 'Nama Izin', thStyle: { width: "35%" } },
        { key: 'description', label: 'Deskripsi' },
        // { key: 'option', label: 'Opsi', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
      ],
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
      let current_page = this.search == '' ? this.current_page : 1
      http
      .get('user/permission/data', {
        params: {
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC',
          f: 'table-list-permission'
        }
      })
      .then((res) => {
        // console.log(res.data.message)
        let receivedData = res.data.message
        this.items = receivedData.data
        this.meta = {
          total: receivedData.total,
          current_page: receivedData.current_page,
          per_page: receivedData.per_page,
          from: receivedData.from,
          to: receivedData.to
        }
      })
      .catch((err) => {
        console.error(error.response)
      })
      .finally(() => { this.busy = false })
    },
  },
  mounted(){
    this.fetchData()
  },
}
</script>

<style>

</style>