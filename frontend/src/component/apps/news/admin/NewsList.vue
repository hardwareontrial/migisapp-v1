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
          variant="primary"
          :to="{ name: 'apps-news-create' }">
          <span class="text-nowrap">Tambah</span>
        </b-button>
      </template>

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>

      <template #cell(title)="data">
        {{ data.item.title }}
      </template>

      <template #cell(category)="data">
        <b-badge
          variant="primary"
          style="margin-right: 5px;"
          v-for="(cat, i) in data.item.category" :key="i">
          {{ cat }}
        </b-badge>
      </template>

      <template #cell(author)="data">
        {{ data.item.creator.name }}
      </template>

      <template #cell(status)="data">
        <b-badge
          :variant="resolveStatusBadge[0][data.item.status]"
          class="badge-glow">
          {{ resolveStatusName(data.item.status) }}
        </b-badge>
      </template>

      <template #cell(opsi)="data">
        <div class="text-nowrap">
          <feather-icon
            @click="handleEditClick(data.item.id, data.item)"
            icon="Edit3Icon"
            class="cursor-pointer"
            style="margin-right: 10px;"
            size="16"/>
          <feather-icon
            icon="TrashIcon"
            class="cursor-pointer"
            size="16"/>
        </div>
      </template>

      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>

    </Table>
  </div>
</template>

<script>
import store from '@/store'
import {
  BButton,
  BBadge,
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  components: {
    BButton,
    BBadge,
    Table: () => import('@/component/utils/Datatable.vue'),
  },
  data(){
    return{
      fields: [
        { key: 'title', label: 'Judul', sortable: true, thClass: 'text-center' },
        { key: 'category', label: 'Kategori', thStyle: { width: "15%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'author', label: 'Creator', sortable: true, thStyle: { width: "20%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'status', label: 'Status', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'opsi', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
      ],
      items: [],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'created_at',
      sortByDesc: true,
      busy: false,
      statusOptions: [
        { value: 0, text: 'Down' },
        { value: 1, text: 'Draft' },
        { value: 2, text: 'Publish' }
      ],
      resolveStatusBadge: [
        { 0: 'secondary', 1: 'warning', 2: 'success' }
      ],
      selectedStatus: '',
    }
  },
  methods: {
    resolveStatusName(val){
      const result = this.statusOptions.filter(obj => { return obj.value === val })
      return result[0].text
    },
    handlePerPage(val){
      this.per_page = val
      this.loadPostData()
    },
    handlePagination(val){
      this.current_page = val
      this.loadPostData()
    },
    handleSearch(val){
      this.search = val
      this.loadPostData() 
    },
    handleSort(val){
      this.sortBy = val.sortBy
      this.sortByDesc = val.sortByDesc
      this.loadPostData()
    },
    fetchData(){
      this.busy = true
      let current_page = this.search == '' ? this.current_page : 1
      http
      .get('news/admin/data', {
        params: {
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
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
    handleEditClick(val, val2){
      this.$router.replace({ name: 'apps-news-edit', params: { id: val, detail: val2 } })
    }
  },
  mounted(){
    this.fetchData()
  },
  created(){
    called.$on('admin-newslist', () => { this.fetchData() })
  },
  beforeCreate(){
    const layout = this.$route.meta.dlayout
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', layout)
  }
}
</script>

<style lang="scss">
</style>