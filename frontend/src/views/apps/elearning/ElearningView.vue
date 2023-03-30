<template>
  <div>
    <transition :name="transition">
      <router-view
        :userdata="userdata"
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
    },
    userdata(){
      let data = JSON.parse(localStorage.getItem('userdata'))
      return data
    }
  },
  beforeDestroy() {
    called.$off('loadDeptList', () => this.loadDeptList())
    called.$off('loadMaterial', () => this.loadMaterial())
    called.$off('loadParticipant', () => this.loadParticipant())
  },
  mounted(){
    this.loadDeptList()
    this.loadMaterial()
    this.loadParticipant()
  },
  created(){
    called.$on('loadDeptList', () => this.loadDeptList())
    called.$on('loadMaterial', () => this.loadMaterial())
    called.$on('loadParticipant', () => this.loadParticipant())
  }
}
</script>

<style>

</style>