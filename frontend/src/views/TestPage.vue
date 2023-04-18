<template>
  <div>
    <datatable/>
<!--    <b-card no-body>-->
<!--      <b-table-->
<!--        ref="mytable"-->
<!--        id="mytable-id"-->
<!--        sticky-header-->
<!--        bordered-->
<!--        responsive-->
<!--        :items="items"-->
<!--        :fields="fields">-->

<!--      </b-table>-->
<!--    </b-card>-->
  </div>
</template>

<script>
import store from '@/store';
import Datatable from '@/component/utils/DtInfiniteScroll.vue'

// const database = [];
// for(let i=0; i < 1000; i++){
//   database.push({ id: i +1 })
// }

// import {
//   BTable,BSpinner,BCard,BRow,BCol,BOverlay,
// } from 'bootstrap-vue'

export default {
  components: {
    // BTable,BSpinner,BCard,BRow,BCol,BOverlay,
    Datatable,
  },
  // data(){
  //   return {
  //     fields: [
  //       { key: 'id', label: 'ID', thClass: 'text-left', tdClass: 'text-left' },
  //     ],
  //     items: [],
  //     currentPage: 0,
  //     perPage: 10,
  //     isBusy: false,
  //     totalItems: database.length,
  //   }
  // },
  // methods: {
  //   async fetchData(){
  //     if(this.items.length === this.totalItems) return;
  //     this.isBusy = true;
  //     const startIndex = this.currentPage++ * this.perPage;
  //     const endIndex = startIndex + this.perPage;
  //     const newItems = await this.callDatabase(startIndex, endIndex);
  //     this.items = this.items.concat(newItems);
  //     this.isBusy = false;
  //   },
  //   callDatabase(startIndex, endIndex){
  //     return new Promise((resolve) => {
  //       const delay = Math.floor(Math.random() *1250) +250;
  //       setTimeout(() => {
  //         resolve(database.slice(startIndex, endIndex));
  //       }, delay);
  //     })
  //   },
  //   onScroll(event){
  //     if(event.target.scrollTop + event.target.clientHeight >= event.target.scrollHeight){
  //       if(!this.isBusy){ this.fetchData() }
  //     }
  //   }
  // },
  // watch: {
  //   isBusy(newVal, oldVal){
  //     if(newVal != oldVal){
  //       const tableScrollBody = this.$refs['mytable'].$el
  //       if(newVal === true){ tableScrollBody.classList.add('overflow-hidden') }
  //       else { tableScrollBody.classList.remove('overflow-hidden') }
  //     }
  //   }
  // },
  // mounted(){
  //   const tableScrollBody = this.$refs['mytable'].$el
  //   tableScrollBody.addEventListener('scroll', this.onScroll)
  // },
  // created(){
  //   this.fetchData()
  // },
  beforeCreate(){
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', 'vertical')
  },
  // beforeDestroy() {
  //   const tableScrollBody = this.$refs['mytable'].$el
  //   tableScrollBody.removeEventListener('scroll', this.onScroll)
  // }
}

</script>

<style>

</style>