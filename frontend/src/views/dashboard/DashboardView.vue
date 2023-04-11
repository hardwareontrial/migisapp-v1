<template>
  <div>
    <section id="dashboard-ecommerce">
      <b-row class="match-height">
        
        <!-- Inventaris -->
        <b-col lg="8">
          <user-inventaris :user-nik="usernik" :user-id="userid" />
        </b-col>

        <!-- Elearning -->
        <b-col lg="4" md="6">
          <user-elearning :user-nik="usernik" :user-id="userid" :user-admin="useradmin"/>
        </b-col>

      </b-row>
    </section>
  </div>
</template>

<script>
import { 
  BRow, BCol,
} from 'bootstrap-vue'
import store from '@/store'

export default {
  components: {
    BRow, BCol,
    UserInventaris: () => import('@/component/dashboard/InventarisData.vue'),
    UserElearning: () => import('@/component/dashboard/ElearningData.vue')
  },
  data(){
    return{
      usernik: null,
      userid: null,
      useradmin: 0,
    }
  },
  computed: {
    userdata(){
      const data = JSON.parse(localStorage.getItem('userdata'))
      this.usernik = data.nik
      this.userid = data.detailuser.id
      this.useradmin = data.admin
    }
  },
  beforeCreate() {
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'horizontal')
  },
  created(){
    this.userdata;
  },
}
</script>

<style>

</style>