<template>
  <div>
    <b-row>
      <b-col cols="12" md="6" lg="3">
        <v-select
          label="name"
          v-model="selectedNik"
          @input="loadDataSubordinates()"
          :options="positionChild"
          :reduce="name=>name.nik"
          :clearable="false"
          class="mb-1">
          <template #option="{ avatar, avatarlink, name }">
            <!-- <b-avatar
              size="26"
              :src="avatar ? avatarlink : null"
              variant="light-primary"/> -->
            <span class="ml-50 d-inline-block align-middle"> {{ name }}</span>
          </template>
          <template #selected-option="{ avatar, avatarlink, name }">
            <!-- <b-avatar
              size="26"
              :src="avatar ? avatarlink : null"
              variant="light-primary"/> -->
            <span class="ml-50 d-inline-block align-middle"> {{ name }}</span>
          </template>
        </v-select>
      </b-col>
      <b-col cols="12">
        <b-card no-body>
          <b-table
            :fields="fields"
            :items="items"
            show-empty
            responsive
            sticky-header>
            <template #empty>
              <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
            </template>
            <template #cell(exam)="data">
              {{ data.item.schedule.title }}
            </template>
            <template #cell(score)="data">
              {{ data.item.isdone === 3 ? '0' : data.item.score }} / {{ data.item.schedule.nilai_min}}
            </template>
            <template #cell(status)="data">
              {{ data.item.isdone === 3 ? 'Proses' : (data.item.ispassed === 1 ? 'Lulus' : 'Tidak Lulus') }}
            </template>
            <template #cell(date)="data">
              {{ data.item.user_end_exam ? $moment(data.item.user_end_exam).format('DD/MMM/YYYY'): '-' }}
            </template>
            <template #cell(opt)="data">
              <b-button
                :disabled="data.item.isdone === 3"
                @click="openDetailExam(data.item)"
                class="btn-icon"
                size="sm"
                type="button"
                variant="flat-primary">
                <feather-icon icon="InfoIcon" size="18"/>
              </b-button>
            </template>
          </b-table>
        </b-card>
      </b-col>
    </b-row>
    {{ positionChild.length }}
  </div>
</template>

<script>
import {
  BRow, BCol, BCard, BTable, BButton,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import vSelect from 'vue-select'
import InfoExam from '@/component/utils/Modal.vue'
import store from '@/store'

export default {
  components: {
    InfoExam, vSelect,
    BRow, BCol, BCard, BTable, BButton,
  },
  data(){
    return{
      items: [],
      fields: [
        { key: 'exam', label: 'Nama Ujian'},
        { key: 'score', label: 'Nama Nilai/Nilai Min.', thStyle: { width: "25%" }, thClass: 'text-center', tdClass: 'text-center'},
        { key: 'status', label: 'Status', thStyle: { width: "15%" }, thClass: 'text-center', tdClass: 'text-center'},
        { key: 'date', label: 'Tgl. Pelaksanaan', thStyle: { width: "20%" }, thClass: 'text-center', tdClass: 'text-center'},
        { key: 'opt', label: 'Option', thStyle: { width: "5%" }, thClass: 'text-center', tdClass: 'text-center'},
      ],
      userIsAdmin: 0,
      userIsActive: 1,
      userNik: null,
      userDetailProps: { id: null, position: null },
      positionChild: [],
      selectedNik: 0,
    }
  },
  computed:{
    userdata(){
      return JSON.parse(localStorage.getItem('userdata'))
    },
  },
  methods: {
    async setUserProp(){
      this.userIsAdmin = this.userdata.admin;
      this.userIsActive = this.userdata.active;
      this.userNik = parseInt(this.userdata.nik);

      if(this.userIsAdmin !== 1 && this.userNik < 8000000){
        this.selectedNik = this.userNik
        await this.fetchRaport(this.userNik)
        var userDetail = await this.userdata.detailuser;
        if(await userDetail){
          // var newnik = userDetail.nik
          await delete userDetail.nik
          userDetail['nik'] = this.userNik
          const userPosition = await userDetail.position
          if(await userPosition)delete userDetail.position
          await this.positionChild.push(userDetail)
          this.extractPositionChildren(userPosition)
        }
      }else if(this.userIsAdmin === 1 && this.userNik >= 8000000){
        this.userList()
        var paramsid = parseInt(this.$route.params.nik)
        if(paramsid < 8000000){ 
          this.selectedNik = await paramsid
          await this.fetchRaport(paramsid)
        }else{
          this.selectedNik = 0
        }
      }
    },
    async extractPositionChildren(value){
      var posChild = await value.children
      for(let el of posChild){
        if(el.user.length > 0)await this.extractUserArray(el.user)
        await this.extractPositionChildren(el)
      }
    },
    async extractUserArray(value){
      const posUser = value
      if(await posUser.length > 0){
        for(let us of posUser){
          var newnik = us.nik
          await delete us.nik
          us['nik'] = parseInt(newnik)
          await this.positionChild.push(us)
        }
      }
    },
    fetchRaport(value){
      if(this.selectedNik !== 0){
        http.get('okm/userexam/all', { params: {userdataNik: value} })
        .then((res) => {
          console.log(res.data)
          this.items = res.data
        })
        .catch((e) => { console.error(e) })
      }else{
        this.items = []
      }
    },
    openDetailExam(value){
      console.log(value)
    },
    loadDataSubordinates(){
      this.fetchRaport(this.selectedNik)  
    },
    userList(){
      http
      .get('misc/list/userlist')
      .then((res) => {
        var modify = res.data.map((el, index) => { 
          var newnik = parseInt(el.nik);
          delete el.nik;
          el['nik'] = newnik;
          return el
        })
        var filtered = modify.filter(function (el) {
          return el.active === 1
        })
        this.positionChild = filtered
      })
      .catch((e) => { console.error(e) })
    },
  },
  mounted(){
    this.setUserProp();
  },
  beforeCreate() {
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'horizontal')
  },
  beforeDestroy(){
    this.selectedNik = 0
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/quill.scss';
  @import '@core/scss/vue/libs/vue-select.scss';
</style>