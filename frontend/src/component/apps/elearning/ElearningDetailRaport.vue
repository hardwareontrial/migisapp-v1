<template>
  <div>
    <b-card no-body>
      <b-table-simple
        small
        style="width: 100%;">
        <b-thead>
          <b-tr>
            <b-th style="width: 40%;">Nama Ujian</b-th>
            <b-th class="text-center" style="width: 10%;">Nilai/Minimal</b-th>
            <b-th class="text-center" style="width: 20%;">Status</b-th>
            <b-th class="text-center" style="width: 20%;">Tanggal Pelaksanaan</b-th>
            <b-th class="text-center" style="width: 10%;">Opsi</b-th>
          </b-tr>
        </b-thead>
        <b-tbody v-for="(userexam, i) in dataexams" :key="i">
          <b-tr>
            <b-td>{{ userexam.schedule.title }}</b-td>
            <b-td class="text-center"> {{ userexam.score }}/{{ userexam.schedule.dataquestion.nilai_min }}</b-td>
            <b-td class="text-center"> 
              <b-badge
                :variant="userexam.ispassed === 1 ? 'light-success' : 'light-danger'"
                pill>
                {{ userexam.ispassed === 1 ? 'Lulus' : 'Tidak Lulus' }}
              </b-badge>
            </b-td>
            <b-td class="text-center">
              {{ userexam.user_end_exam ? $moment(userexam.user_end_exam).format('DD/MMM/YYYY'): '-' }}
            </b-td>
            <b-td class="text-center">
              <b-button-group>
                <b-button
                  class="btn-icon"
                  size="sm"
                  type="button"
                  variant="flat-primary">
                  <feather-icon icon="InfoIcon" size="18"/>
                </b-button>
              </b-button-group>
            </b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </b-card>
  </div>
</template>

<script>
import { 
  BTableSimple, BThead, BTbody, BTh, BTd, BTr,
  BButton, BButtonGroup,
  BCard,
  BBadge,
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  components: {
    BTableSimple, BThead, BTbody, BTh, BTd, BTr,
    BButton, BButtonGroup,
    BCard,
    BBadge,
  },
  data(){
    return{
      dataexams: [],
      usernik: null,
      userID: null
    }
  },
  methods: {
    fetchdata(){
      // called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      // called.$emit('hideloading')
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      http.get('okm/userexam/all', { params: {userdataNik: this.usernik} })
      .then((res) => {
        this.dataexams = res.data
        called.$emit('hideloading')
      })
      .catch((e) => { console.error(e) })
    }
  },
  mounted(){
    this.fetchdata();
  },
  created(){
    if(this.$route.params){
      this.usernik = this.$route.params.nik
      this.userID = this.$route.params.id
    }
  }
}
</script>

<style>

</style>