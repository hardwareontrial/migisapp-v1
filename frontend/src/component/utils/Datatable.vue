<template>

  <b-card
    no-body
    class="mb-0">
    <div class="m-2">
      <b-row>
        <b-col
          cols="12"
          md="6"
          class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
          <label>Tampil</label>
          <v-select
            :clearable="false"
            :options="optionPerPage"
            v-model="meta.per_page"
            @input="loadPerPage"
            label="text"
            :reduce="text=>text.value"
            class="per-page-selector d-inline-block mx-50"/>
          <label>item</label>
          <slot name="addbutton-second"></slot>
        </b-col>
        <b-col
          cols="12"
          md="6">
          <div class="d-flex align-items-center justify-content-end">
            <b-form-input
              class="d-inline-block mr-1"
              placeholder="Cari..."
              type="text"
              v-model="te"
              debounce="700"/>

            <slot name="addbutton"></slot>
            
          </div>
        </b-col>
      </b-row>
    </div>

    <b-table
      responsive
      :items="items" 
      :fields="fields" 
      :sort-by.sync="sortBy" 
      :sort-desc.sync="sortDesc"
      :busy="isbusy"
      @row-clicked="rowClicked"
      show-empty>
      
      <template v-for="(_, slotName) of $scopedSlots" v-slot:[slotName]="scope">
        <slot :name="slotName" v-bind="scope"></slot>
      </template>
    </b-table>
    
    <div class="mx-2 mb-2">
      <b-row>
        <b-col
          cols="12"
          sm="6"
          class="d-flex align-items-center justify-content-center justify-content-sm-start">
          <small>Tampil {{ meta.from }} sampai {{ meta.to }} dari {{ meta.total }} item.</small>
        </b-col>
        <b-col
          cols="12"
          sm="6"
          class="d-flex align-items-center justify-content-center justify-content-sm-end">
          <b-pagination
           align="right"
           v-model="meta.current_page"
           :total-rows="meta.total"
           :per-page="meta.per_page"
           @change="changePage"
           aria-controls="dt-custom">
          </b-pagination>
        </b-col>
      </b-row>
    </div>

  </b-card>

</template>

<script>
import {
  BCard,
  BRow, BCol,
  BFormInput, BFormSelect,
  BTable, BSpinner,
  BPagination,
} from 'bootstrap-vue'
import vSelect from 'vue-select'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
    fields: {
      type: Array,
      required: true
    },
    meta: {
      required: true
    },
    isbusy: {
      type: Boolean,
      required: false
    },
  },
  components: {
    BCard,
    BRow, BCol,
    BFormInput, BFormSelect,
    BTable, BSpinner,
    BPagination,
    vSelect,
  },
  data(){
    return{
      optionPerPage: [
        { text: 10, value: 10 },
        { text: 25, value: 25 },
        { text: 50, value: 50 },
      ],
      sortBy: null,
      sortDesc: false,
      te: ''
    }
  },
  watch: {
    sortBy(val){
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    },
    sortDesc(val){
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      })
    }
  },
  watch: {
    'te': {
      handler: function(n,o){
        this.$emit('search', n)
      },
      deep: true
    }
  },
  methods: {
    loadPerPage(val){
      this.$emit('per_page', this.meta.per_page)
    },
    changePage(val){
      this.$emit('pagination', val)
    },
    rowClicked(record, index){
      this.$emit('rowclicked', record, index)
    }
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>