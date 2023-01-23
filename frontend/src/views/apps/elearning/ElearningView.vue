<template>
  <div>
    <transition :name="transition">
      <router-view
        :participant-list="participantsList" 
        :level-options="levelOptions"
        :dept-list="deptlist"
        :materials-list="materialsList">
      </router-view>
    </transition>
    
    <pop-up />
  </div>
</template>

<script>
import store from '@/store'
import http from '@/customs/axios'
import PopUp from '@/component/utils/Loading.vue'

export default {
  components: {
    PopUp,
  },
  data(){
    return{
      levelOptions: [
        { 1: 1, 2: 2, 3: 3 },
        { 1: 'Pemula', 2: 'Menengah', 3: 'Lanjutan'},
        { 1: 'success', 2: 'warning', 3: 'primary'},
        { text: 'Pemula', text: 'Menengah', text: 'Lanjutan'},
        { value: 1, value: 2, value: 3},
      ],
      deptlist: [],
      materialsList: [],
      participantsList: [],
    }
  },
  methods: {
    loadDeptList(){
      // http.get('okm/material/deptlist')
      http.get('misc/list/depts')
      .then((res) => {
        this.deptlist = res.data
      })
      .catch((e) => {
        console.error(e)
      })
    },
    loadMaterial(){
      http
        .get('okm/material/list')
        .then((res) => {
          // console.log(res.data)
          this.materialsList = res.data
        })
        .catch((e) => {
          console.error(e)
        })
    },
    loadParticipant(){
      http
      .get('okm/schedule/handlelist')
      .then((res) => {
        this.participantsList = res.data
      })
      .catch((e) => { console.error(e) })
    },
  },
  computed: {
    transition(){
      return store.state.appConfig.layout.routerTransition
    }
  },
  beforeCreate() {
    // store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
  },
  mounted(){
    this.loadDeptList()
    this.loadMaterial()
    this.loadParticipant()
  },
}
</script>

<style>

</style>