<template>
  <div>
    <b-container>
      <b-row>
        <b-col cols="12" class="d-flex justify-content-center mt-1 mb-1">
          <b-form-input
            v-model="search"
            @input="handleSearch"
            debounce="1500"
            class="w-50"
            id="searchbar"
            placeholder="Cari User"/>
        </b-col>
        <b-col cols="12" class="d-flex justify-content-center mt-1 mb-0">
          <b-row>
            <b-col 
              v-for="(data, idata) in rawuserlist" 
              :key="idata"
              cols="12"
              md="4"
              class="mb-3">
              <b-overlay 
                :show="!(!!data.active)">
                <b-card
                  :img-src="require('@/assets/images/banner/banner-6.jpg')"
                  img-alt="Profile Cover Photo"
                  img-top
                  class="card-profile">
                  <div class="profile-image-wrapper">
                    <div class="profile-image p-0">
                      <b-avatar
                        size="114"
                        variant="light-primary"
                        :src="data.avatar ? data.avatarlink : null"/>
                    </div>
                  </div>
                  <h3>{{ data.name }}</h3>
                  <h6 class="text-muted">
                    {{ data.position ? data.position.name : '-' }}
                  </h6>
                  <b-badge
                    class="profile-badge"
                    variant="light-primary">
                    {{ data.position ? data.position.deptname.name : '-' }}
                  </b-badge>
                  <div class="d-flex justify-content-center align-items-center">
                    <div>
                      <b-button
                        :to="{name: 'apps-elearning-raport-detail', params: {id: data.id, nik: data.nik } }" 
                        variant="flat-primary">
                        Detail
                      </b-button>
                    </div>
                  </div>
                </b-card>
                <template #overlay>
                  <div class="text-center">
                    <feather-icon icon="AlertCircleIcon" size="18" class="mb-50"/>
                    <p id="cancel-label">
                      User tidak ditemukan.
                    </p>
                  </div>
                </template>
              </b-overlay>
            </b-col>
          </b-row>
        </b-col>
        <b-col cols="12" class="d-flex justify-content-center mt-0 mb-0">
          <b-pagination
            v-model="meta.current_page"
            @input="handlepagination"
            :total-rows="meta.total"
            :per-page="meta.per_page"
            pills
            size="md">
          </b-pagination>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import http from '@/customs/axios'
import {
  BCard, BCardBody, BCardText,
  BRow, BCol,
  BFormInput, 
  BContainer,
  BPagination,
  BAvatar,
  BBadge,
  BOverlay,
  BButton,
} from 'bootstrap-vue'

export default {
  props: {
    userdata: {
      type: Object,
    }
  },
  components: {
    BCard, BCardBody, BCardText,
    BRow, BCol,
    BFormInput,
    BContainer,
    BPagination,
    BAvatar,
    BBadge,
    BOverlay,
    BButton,
  },
  data(){
    return{
      rawuserlist: [],
      current_page: 1,
      per_page: 12,
      search: '',
      sortBy: 'id',
      sortByDesc: false,
      meta: {},
    }
  },
  methods:{
    handleGetuserList(){
      // called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      http
      .get('user/all', {
        params: {
          // page: current_page,
          page: this.current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC',
          initialpage: 'raport'
        }
      })
      .then((res) => {
        // console.log(res.data.message)
        this.rawuserlist = res.data.message.data
        this.meta = {
          total: res.data.message.total,
          current_page: res.data.message.current_page,
          per_page: res.data.message.per_page,
          from: res.data.message.from,
          to: res.data.message.to
        }
        // called.$emit('hideloading')
      })
      .catch((err) => {
        console.error(err)
      })
    },
    handlepagination(val){
      this.current_page = val,
      this.handleGetuserList()
    },
    handleSearch(val){
      this.search = val
      this.handleGetuserList()
    }
  },
  mounted(){
    this.handleGetuserList()
  },
  computed:{
    //
  },
  // beforeRouteEnter(from, to, next){
    // let userdataAdmin = userdata.admin
    // if(userdataAdmin === 1){
    //   next()
    // }else if(userdataAdmin === 0 && userdataNik < 8000000){
    //   next({name: 'apps-elearning-raport-detail', params: {id: userdataID, nik: userdataNik } })
    // }
    // let userdata = JSON.parse(localStorage.getItem('userdata'));
    // let userdataNik = userdata.nik
    // let userdataSNik = userdata.s_nik
    // let userdataID = userdata.detailuser.id
    // let permission = userdata.permissions
  // }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/pages/page-knowledge-base.scss';
</style>