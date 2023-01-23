<template>
  <div>
    
    <b-card
      title="Data Inventaris Anda">
      <div>
        <!-- <b-table-simple
          caption-top
          responsive="sm"
          class="rounded-bottom mb-0">
          <b-thead>
            <b-tr>
              <b-th>Kode</b-th>
              <b-th>Nama Barang</b-th>
              <b-th class="text-center">Status</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <b-tr v-if="inventarisuser.length <= 0">
              <b-td colspan="3" class="text-center">😔 Tidak ada data.</b-td>
            </b-tr>
            <b-tr v-else v-for="(item, i) in inventarisuser" :key="i">
              <b-td>
                <strong> {{ item.kd_brg }} </strong>
              </b-td>
              <b-td>
                <strong> {{ item.nama_brg }} </strong>
              </b-td>
              <b-td class="text-center">
                <b-badge
                  :variant="status[1][item.status_id]">
                  {{ status[0][item.status_id] }}
                </b-badge>
              </b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple> -->

        <b-table
          sticky-header
          responsive
          :items="inventarisuser"
          :fields="fields"
          @row-clicked="showDetail">
          <template #cell(code)="data">
            <!-- <span @click="showDetail(data.item.id)"> {{ data.item.kd_brg }} </span> -->
            {{ data.item.kd_brg }}
          </template>
          <template #cell(name)="data">
            {{ data.item.nama_brg }}
          </template>
          <template #cell(status)="data">
            <b-badge
              :variant="status[1][data.item.status_id]">
              {{ status[0][data.item.status_id] }}
            </b-badge>
          </template>
        </b-table>
      
      </div>
    </b-card>

  </div>
</template>

<script>
import {
  BModal,
  BButton,
  BBadge,
  BTableSimple, BThead, BTbody, BTr, BTd, BTh,
  BCard,
  BTable,
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  components: {
    BModal,
    BButton,
    BBadge,
    BTableSimple, BThead, BTbody, BTr, BTd, BTh,
    BCard,
    BTable,
  },
  data(){
    return{
      show: false,
      userid: '',
      usernik: '',
      inventarisuser: [],
      status: [ 
        { 2: 'Terpasang', 4: 'Perawatan', },
        { 2: 'info', 4: 'warning', }
      ],
      fields: [
        { key: 'code', label: 'Kode Barang', sortable: true },
        { key: 'name', label: 'Nama Barang' },
        { key: 'status', label: 'Status' },
      ]
    }
  },
  methods: {
    fetchdata(){
      this.overlay = true
      http
      .get('inventaris/detail/inventarisuser/', { params: { id: this.userid } })
      .then((res) => {
        // console.log(res.data)
        this.inventarisuser = res.data.message
        this.overlay = false
      })
      .catch((e) => {
        console.error(e)
      })
    },
    showDetail(data, index, event){
      let dataid = data.id
      called.$emit('inventarisdetailshow', dataid)
      // console.log(val1.id)
    },
  },
  computed: {
    userdata(){
      const data = JSON.parse(localStorage.getItem('userdata'))
      this.usernik = data.nik
      this.userid = data.detailuser.id
      // this.userid = 2
    }
  },
  mounted() {
    this.userdata
    this.fetchdata()
  }
}
</script>

<style>

</style>