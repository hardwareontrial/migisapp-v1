<template>
  <div>
    
    <transition :name="transition">
      <router-view></router-view>
    </transition>

    <inventaris-add-merk />
    <inventaris-add-location />
    <inventaris-detail />
    <!-- <InventarisSelf /> -->

  </div>
</template>

<script>
import store from '@/store'
import InventarisDetail from '@/component/apps/gainventaris/InventarisDetail.vue'
// import InventarisSelf from '@/component/apps/gainventaris/InventarisOwned.vue'

export default {
  components: {
    InventarisAddMerk: () => import('@/component/apps/gainventaris/_InventarisAddMerk.vue'),
    InventarisAddLocation: () => import('@/component/apps/gainventaris/_InventarisAddLokasi.vue'),
    // InventarisDetail: () => import('@/component/apps/gainventaris/InventarisDetail.vue'),
    // InventarisSelf,
    InventarisDetail,
  },
  beforeCreate() {
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
  },
  computed: {
    transition(){
      return store.state.appConfig.layout.routerTransition
    }
  },
}
</script>

<style>

</style>