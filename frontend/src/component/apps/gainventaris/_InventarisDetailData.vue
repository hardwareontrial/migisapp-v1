<template>
  <div>
    <b-overlay :show="showOverlay" rounded="sm" :opacity="0.90">
      <div class="text-center" v-if="ddd">
        <b-alert
          variant="danger"
          show>
          <div class="alert-body">
            <span>Data tidak ditemukan !</span>
          </div>
        </b-alert>
      </div>
      <b-table-simple responsive small v-else>
        <b-tbody>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Status </b-th>
            <b-td style="font-size: 10pt;">
              <b-badge 
                :variant="badgestatus[1][detail.status]">
                {{ badgestatus[0][detail.status] }}
              </b-badge>
            </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Aktif </b-th>
            <b-td style="font-size: 10pt;">
              <b-badge
                pill
                :variant="active[1][detail.active_brg]">
                {{ active[0][detail.active_brg] }}
              </b-badge>
            </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Nama </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.nama_brg }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Penanggung Jawab </b-th>
            <b-td style="font-size: 10pt;"> {{ pemilikresolve[0][detail.deptbeli] }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Tanggal Beli </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.tglbeli }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Harga </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.harga }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Toko </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.toko }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Merk </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.merk }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> SN </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.sn }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Lokasi </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.lokasi }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Spesifikasi </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.spesifikasi }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> User 1 </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.user1 }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> User 2 </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.user2 }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Departemen </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.deptuser }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> Note </b-th>
            <b-td style="font-size: 10pt;"> {{ detail.mtc_note }} </b-td>
          </b-tr>
          <b-tr>
            <b-th sticky-column style="width: 25%; font-size: 10pt;"> QR </b-th>
            <b-td style="font-size: 10pt;"> 
              <b-img
                style="max-width: 12%; height: auto;"
                :src="detail.qrcode"
                fluid 
                alt="image" />
            </b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </b-overlay>
  </div>
</template>

<script>
import {
  BOverlay,
  BBadge,
  BImg,
  BAlert,
  BTable, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import { $baseUrl } from '@themeConfig'

export default {
  props:{
    detailData: {
      type: Object,
  },
  // keydata: {
  //   type: Number/String,
  //   required: true,
  // }
  },
  components: {
    BOverlay,
    BBadge,
    BImg,
    BAlert,
    BTable, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd,
  },
  data(){
    return{
      showOverlay: false,
      detail: {
        id: '',
        kode_brg: '',
        nama_brg: '',
        deptbeli: '',
        tglbeli: '',
        toko: '',
        harga: '',
        sn: '',
        status: '',
        merk: '',
        lokasi: '',
        spesifikasi: '',
        mtc_note: '',
        user1: '',
        user2: '',
        deptuser: '',
        qrcode: '',
        active_brg: '',
      },
      badgestatus: [
        { 1: 'Stock', 2: 'User', 3: 'Properti', 4: 'Perawatan' },
        { 1: 'secondary', 2: 'info', 3: 'info', 4: 'warning' },
      ],
      pemilikresolve: [
        { 1: 'GA', 2: 'IT', 3: 'HSE' },
      ],
      active: [
        { 0: 'Non-Aktif', 1: 'Aktif' },
        { 0: 'danger', 1: 'success' },
      ],
      ddd: false,
    }
  },
  methods: {
    // fetchData(){
    //   this.showOverlay = true
    //   // var id = this.keydata.split(' ').join('-')
    //   http
    //   .get('inventaris/detail/id/'+this.keydata)
    //   // .get('inventaris/detail/2')
    //   .then((data) => {
    //     // console.log(data)
    //     if(!data.data){
    //       this.ddd = true
    //       this.showOverlay = false
    //     }else{
    //       this.detail.id = data.data.id
    //       this.detail.kode_brg = data.data.kd_brg
    //       this.detail.nama_brg = data.data.nama_brg
    //       // this.detail.deptbeli = data.data.pemilikname.name
    //       this.detail.deptbeli = data.data.pemilik_id
    //       this.detail.tglbeli = this.$moment(data.data.tgl_beli).format('DD MMMM Y')
    //       this.detail.toko = data.data.toko ? data.data.toko : '-'
    //       this.detail.harga = data.data.harga ? 'Rp '+data.data.harga : 'Rp -'
    //       this.detail.sn = data.data.serialnumber ? data.data.serialnumber : '-'
    //       this.detail.status = data.data.status_id
    //       this.detail.merk = data.data.merkname.name
    //       this.detail.lokasi = data.data.locname.name
    //       this.detail.spesifikasi = data.data.spesifikasi ? data.data.spesifikasi : '-'
    //       this.detail.mtc_note = data.data.mtc_note ? data.data.mtc_note : '-'
    //       this.detail.user1 = data.data.user1_id ? data.data.user1name.name : '-'
    //       this.detail.user2 = data.data.user2_id ? data.data.user2name.name : '-'
    //       // this.detail.deptuser = data.data.status_id === 2 ? data.data.user1name.position.deptname.name : '-'
    //       this.detail.deptuser = data.data.status_id === 2 ? (data.data.user1name.position ? data.data.user1name.position.deptname.name : '') : ''
    //       // this.detail.qrcode = `${$baseUrl.url2}storage/app_inventaris/qrcode/${data.data.qrcode}`
    //       this.detail.qrcode = data.data.qrlink
    //       this.detail.active_brg = data.data.active
    //       this.showOverlay = false
    //       this.$emit('s_code', this.detail.kode_brg)
    //     }
    //   })
    //   .catch((e) => {
    //     console.error(e)
    //   })
    // }
  },
  mounted(){
    // this.fetchData()
  },
  watch: {
    'detailData':{
      immediate: false,
      deep: true,
      handler(n, o){
        this.showOverlay = true
        if(n !== null){
          let detail = this.detailData
          this.detail.id = detail.id
          this.detail.kode_brg = detail.kd_brg
          this.detail.nama_brg = detail.nama_brg
          this.detail.deptbeli = detail.pemilik_id
          this.detail.tglbeli = this.$moment(detail.tgl_beli).format('DD MMMM Y')
          this.detail.toko = detail.toko ? detail.toko : '-'
          this.detail.harga = detail.harga ? 'Rp '+detail.harga : 'Rp -'
          this.detail.sn = detail.serialnumber ? detail.serialnumber : '-'
          this.detail.status = detail.status_id
          this.detail.merk = detail.merkname.name
          this.detail.lokasi = detail.locname.name
          this.detail.spesifikasi = detail.spesifikasi ? detail.spesifikasi : '-'
          this.detail.mtc_note = detail.mtc_note ? detail.mtc_note : '-'
          this.detail.user1 = detail.user1_id ? detail.user1name.name : '-'
          this.detail.user2 = detail.user2_id ? detail.user2name.name : '-'
          this.detail.deptuser = detail.status_id === 2 ? (detail.user1name.position ? detail.user1name.position.deptname.name : '') : ''
          this.detail.qrcode = detail.qrlink
          this.detail.active_brg = detail.active
          this.showOverlay = false
        }else{
          this.ddd = true
          this.showOverlay = false
        }
      }
    }
  }
}
</script>

<style>

</style>