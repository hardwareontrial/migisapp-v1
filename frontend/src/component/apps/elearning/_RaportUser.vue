<template>
  <div>
    <b-row>
      <b-col cols="12" md="6" lg="4">
        <v-select
          label="name"
          v-model="selectedNik"
          @input="loadDataSubordinates()"
          :options="positionChild"
          :reduce="name=>name.nik"
          :clearable="false"
          class="mb-1">
          <template #option="{ avatar, avatarlink, name }">
            <b-avatar
              size="26"
              :src="avatar ? avatarlink : null"
              variant="light-primary"/>
            <span class="ml-50 d-inline-block align-middle"> {{ name }}</span>
          </template>
          <template #selected-option="{ avatar, avatarlink, name }">
            <b-avatar
              size="26"
              :src="avatar ? avatarlink : null"
              variant="light-primary"/>
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
              <span
                class="font-weight-bolder"
                :class="`text-${data.item.isdone === 3 ? 'danger' : (data.item.score >= data.item.schedule.nilai_min) ? 'success' : 'danger'}`">
                {{ data.item.isdone === 3 ? '0' : data.item.score }}
              </span>
              / {{ data.item.schedule.nilai_min}}
            </template>
            <template #cell(status)="data">
              <feather-icon
                :icon="data.item.isdone === 3 ? 'ClockIcon' : (data.item.ispassed === 1 ? 'CheckCircleIcon' : 'XCircleIcon')"
                :class="`text-${data.item.isdone === 3 ? 'success' : (data.item.ispassed === 1 ? 'primary' : 'danger')}`"
                size="14"/>
            </template>
            <template #cell(date)="data">
              {{ data.item.user_end_exam ? $moment(data.item.user_end_exam).format('DD/MMM/YYYY'): '-' }}
            </template>
            <template #cell(opt)="data">
              <b-dropdown
                variant="link"
                no-caret>
                <template #button-content>
                  <feather-icon
                    icon="MoreVerticalIcon"
                    size="16"
                    class="align-middle text-body"/>
                </template>
                <b-dropdown-item 
                  @click="openDetailExam(data.item)"
                  :disabled="data.item.isdone === 3"
                  >
                  <feather-icon icon="InfoIcon"/>
                  <span class="align-middle ml-50">Hasil Tes</span>
                </b-dropdown-item>
                <b-dropdown-item
                  v-if="data.item.schedule.certificate_id"
                  @click="setPrintCertificate(data.item.certificatelink)">
                  <feather-icon icon="PrinterIcon"/>
                  <span class="align-middle ml-50">Cetak Sertifikat</span>
                </b-dropdown-item>
              </b-dropdown>
            </template>
          </b-table>
        </b-card>
      </b-col>
    </b-row>

    <detail-result
      :show="detailResultProps.show"
      :title="detailResultProps.title"
      :size="detailResultProps.size">
      <template v-slot:modalbody>
        <table-result :user-data-exam="detailResultProps.data"></table-result>
      </template>
      <template v-slot:modalfooter>
        <b-button
          @click="detailResultProps.show = !detailResultProps.show;detailResultProps.data={}"
          variant="flat-primary">
          Tutup
        </b-button>
      </template>
    </detail-result>

    <certificate-user :prop-img-certificate.sync="userCert" ref="printCert" v-show="false" v-if="userCert"/>

  </div>
</template>

<script>
import {
  BRow, BCol, BCard, BTable, BButton, BAvatar, BDropdown, BDropdownItem,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import vSelect from 'vue-select'
import InfoExam from '@/component/utils/Modal.vue'
import store from '@/store'
import TableResult from '@/component/apps/elearning/_ParticipantResult.vue'
import DetailResult from '@/component/utils/Modal.vue'
import CertificateUser from './_CertificateUser.vue'

export default {
  components: {
    InfoExam, vSelect, TableResult, DetailResult,
    BRow, BCol, BCard, BTable, BButton, BAvatar,
    CertificateUser, BDropdown, BDropdownItem,
  },
  data(){
    return{
      items: [],
      fields: [
        { key: 'exam', label: 'Nama Ujian'},
        { key: 'score', label: 'Nilai/Nilai Min.', thStyle: { width: "25%" }, thClass: 'text-center', tdClass: 'text-center'},
        { key: 'status', label: 'Status', thStyle: { width: "15%" }, thClass: 'text-center', tdClass: 'text-center'},
        { key: 'date', label: 'Tgl. Pelaksanaan', thStyle: { width: "20%" }, thClass: 'text-center', tdClass: 'text-center'},
        { key: 'opt', label: 'Option', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center'},
      ],
      userIsAdmin: 0,
      userIsActive: 1,
      userNik: null,
      userDetailProps: { id: null, position: null },
      positionChild: [],
      selectedNik: 0,
      detailResultProps: {
        show: false,
        title: 'Info Hasil Ujian User',
        size: 'lg',
        data: {},
      },
      badgeStatusProps: [
        { 1: 'Lulus', 2: 'Tidak Lulus' },
        { 1: 'primary', 2: 'danger', 3: 'success' },
        { 1: 'Selesai', 2: 'N/A', 3: 'Proses' },
        { 1: 'CheckCircleIcon', 2: 'XCircleIcon', 3: 'ClockIcon' },
      ],
      userCert: '',
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
      }else if(this.userIsAdmin === 0 && this.userNik >= 8000000){
        this.userList();
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
          // console.log(res.data)
          this.items = res.data
        })
        .catch((e) => { console.error(e) })
      }else{
        this.items = []
      }
    },
    openDetailExam(value){
      this.detailResultProps.show = true;
      this.detailResultProps.data = value;
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
    setPrintCertificate(data){
      this.userCert = data;
      setTimeout(() => {
        this.$refs.printCert.printCert();
    }, 750)
    }
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