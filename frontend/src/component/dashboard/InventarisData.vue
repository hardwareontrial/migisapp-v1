<template>
  <div>
    <b-card no-body>
      <b-table
        :items="inventarisuser"
        :fields="fields"
        :busy="tablebusy"
        sticky-header
        responsive
        show-empty>

        <template #empty>
          <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
        </template>

        <template #cell(code)="data">
          {{ data.item.kd_brg }}
        </template>
        <template #cell(name)="data">
          {{ data.item.nama_brg }}
        </template>
        <template #cell(status)="data">
          <feather-icon 
            :icon="status[0][data.item.status_id]" 
            :class="status[1][data.item.status_id]" 
            size="16" />
        </template>
        <template #cell(action)="data">
          <feather-icon icon="InfoIcon" class="text-primary" size="16"/>
        </template>

      </b-table>
    </b-card>
  </div>
</template>

<script>
import { 
  BTable, BCard, BOverlay,
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  props:{
    UserId: { type: Number, required: true },
    UserNik: { type: Number/String, required: true },
  },
  components: {
    BTable, BCard, BOverlay,
  },
  data(){
    return{
      inventarisuser: [],
      status: [ 
        { 2: 'CheckCircleIcon', 4: 'AlertCircleIcon', },
        { 2: 'text-success', 4: 'text-warning', }
      ],
      fields: [
        { key: 'code', label: 'Kode Barang', thStyle: { width: "20%" } },
        { key: 'name', label: 'Nama Barang',},
        { key: 'status', label: 'Status', thStyle: { width: "15%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'action', label: 'Opsi', thStyle: { width: "5%" }, thClass: 'text-center', tdClass: 'text-center' },
      ],
      tablebusy: false,
    }
  },
  methods: {
    async fetchData(){
      this.tablebusy = true
      await http.get('inventaris/user', { params: { id: this.UserId } })
      .then((res) => {
        this.inventarisuser = res.data.message
        this.tablebusy = false
      })
      .catch((e) => { console.error(e) })
    }
  },
  mounted(){
    this.fetchData()
  }
}
</script>

<style>

</style>