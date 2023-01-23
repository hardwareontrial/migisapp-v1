<template>
  <div>
    <!-- <b-pagination
      v-if="rawdata"
      v-model="current_page"
      :total-rows="bakeddata.total"
      :per-page="per_page"
      @input="handlePage">
    </b-pagination> -->

    <example-data-table
      :items="bakeddata.data"
      :fields="fields"
      :meta="bakeddata"
      :isbusy="isbusy"
      @pagination="handlePagination"
      @per_page="handlePerPage"
      @rowclicked="handleRowClicked"
      @inputsearch="handleSearch">
      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>
      <template #table-busy>
        <div class="text-center text-primary my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>
      <template #cell(number)="data">
        <strong>{{ data.item.delivery_no }}</strong>
        <small v-if="data.item.po_no"><br>PO: {{ data.item.po_no }}</small>
        <small v-if="data.item.do_no"><br>DO: {{ data.item.do_no }}</small>
      </template>
      <template #cell(customer)="data">
        <strong>{{ data.item.detail.detail_customer }}</strong>
        <br><small>{{ data.item.detail.detail_address }}</small>
        <br><small>{{ data.item.detail.detail_city }}</small>
      </template>
    </example-data-table>
  </div>
</template>

<script>
import http from '@/customs/axios'
import {
  BPagination,
} from 'bootstrap-vue'
import ExampleDataTable from '@/component/utils/DatatableTwo.vue'

export default {
  components:{
    BPagination,
    ExampleDataTable,
  },
  data(){
    return{
      rawdata: [],
      per_page: 10,
      current_page: 1,
      searchquery: '',
      fields: [
        { key: 'number', label: 'Nomor', thStyle: { width: "15%" }, thClass: 'text-center' },
        { key: 'customer', label: 'Customer', thClass: 'text-center' },
        { key: 'transporter', label: 'Transporter', thStyle: { width: "12%" }, thClass: 'text-center' },
        { key: 'sending_date', label: 'Tgl Kirim', thStyle: { width: "10%" }, thClass: 'text-center' },
        { key: 'item', label: 'Item/Qty', thStyle: { width: "10%" }, thClass: 'text-center' },
        { key: 'creator', label: 'Creator', thStyle: { width: "12%" }, thClass: 'text-center' },
        { key: 's_r', label: 'Remark/Status', thStyle: { width: "5%" } },
        { key: 'opsi', thStyle: { width: "5%" }, thClass: 'text-center', tdClass: 'text-center' },
      ],
      isbusy: false,
    }
  },
  methods:{
    fetchdata(){
      this.isbusy = true
      http
      .get('okm/schedule/testfunction2')
      .then((res) => {
        this.rawdata = res.data
        // console.log(res.data)
        this.isbusy = false
      })
      .catch((e) => {
        console.error(e)
      })
    },
    handleRowClicked(val){
      //
    },
    handlePerPage(val){
      this.per_page = val
    },
    handlePagination(val){
      this.current_page = val
    },
    handleSearch(val){
      this.searchquery = val
    }
  },
  computed:{
    searchResult(){
      let tmpresult = this.rawdata
      if(this.searchquery !== '' && this.searchquery){
        tmpresult = tmpresult.filter(item => 
          item.delivery_no.toLowerCase().search(this.searchquery.toLowerCase()) > -1 ||
          item.detail.detail_customer.toLowerCase().search(this.searchquery.toLowerCase()) > -1 || 
          item.detail.detail_city.toLowerCase().search(this.searchquery.toLowerCase()) > -1 || 
          item.creator.name.toLowerCase().search(this.searchquery.toLowerCase()) > -1
        )
      }
      return tmpresult
    },
    bakeddata(){
      let rawdata = this.searchResult
      let totalrawdata = rawdata.length
      let chunkeddata = rawdata.slice(((this.current_page - 1) * this.per_page), (this.per_page * this.current_page))
      let localCount =  rawdata ? chunkeddata.length : 0
      return {
        localCount,
        current_page: this.current_page,
        data: chunkeddata,
        from: this.per_page * (this.current_page -1) + (localCount ? 1 : 0),
        per_page: this.per_page,
        to: this.per_page * (this.current_page -1) + localCount,
        total: totalrawdata
      }
    }
  },
  created(){
    this.fetchdata()
  }
}
</script>

<style>

</style>