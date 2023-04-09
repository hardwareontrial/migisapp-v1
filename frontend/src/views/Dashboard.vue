<template>
  



    <!-- <b-row> -->
      <!-- <b-col></b-col> -->
      <!-- <b-col lg="6">
        <InventarisSelf />
      </b-col> -->
    <!-- </b-row> -->
    <!-- <inventaris-detail /> -->
  <!-- </section>   -->
  

</template>

<script>
import { 
  BRow, BCol,
} from 'bootstrap-vue'
import store from '@/store'
import InventarisSelf from '@/component/apps/gainventaris/InventarisOwned.vue'

export default {
  components: {
    BRow, BCol,
    // InventarisAddMerk: () => import('@/component/apps/gainventaris/_InventarisAddMerk.vue'),
    // InventarisAddLocation: () => import('@/component/apps/gainventaris/_InventarisAddLokasi.vue'),
    // InventarisDetail: () => import('@/component/apps/gainventaris/InventarisDetail.vue'),
    InventarisSelf,
  },
  beforeCreate() {
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'horizontal')
  },
  computed: {
    transition(){
      return store.state.appConfig.layout.routerTransition
    }
  },
  mounted() {
    // console.log('dashboard')
    // window.Echo.channel('channel')
    //   .listen('Hello', (e) => { console.log(e) })
  }
}
</script>

<style>

</style>