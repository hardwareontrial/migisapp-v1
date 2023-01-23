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
          variant="primary">
          <span class="text-nowrap">Tambah</span>
        </b-button>
      </template>

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>

      <template #empty="scope">
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>

    </Table>
  </div>
</template>

<script>
import {
  BButton,
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  components: {
    BButton,
    Table: () => import('@/component/utils/Datatable.vue'),
  },
  data(){
    return{
      fields: [
        { key: 'title', sortable: true },
        { key: 'author', sortable: true },
        { key: 'category', sortable: true },
        { key: 'created_at', sortable: true },
      ],
      items: [],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'created_at',
      sortByDesc: false,
      busy: false,
    }
  },
  methods: {
    loadPostData() {
      this.busy = true
      let current_page = this.search == '' ? this.current_page : 1
      http.get('dummy', {
        params: {
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      })
      .then((data) => {
        console.log(data.data.message)
        let getData = data.data.message
        this.items = getData.data
        this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to
        }
      })
      .finally(()=> { this.busy = false })
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
    }
  },
  mounted() {
    this.loadPostData()
  }
}
</script>

<style lang="scss">

</style>