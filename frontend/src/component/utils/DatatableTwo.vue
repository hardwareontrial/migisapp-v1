<template>
  <div>
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
          </b-col>
          <b-col
            cols="12"
            md="6">
            <div class="d-flex align-items-center justify-content-end">
              <b-form-input
                class="d-inline-block mr-1"
                placeholder="Cari..."
                type="text"
                v-model="search"
                @input="inputsearch"
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
  </div>
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
    items: { required: true, type: Array },
    fields: { required: true, type: Array },
    isbusy: { type: Boolean },
    meta: { required: true }
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
      sortBy: 'id',
      sortDesc: true,
      search: ''
    }
  },
  methods: {
    changePage(val){
      this.$emit('pagination', val)
    },
    rowClicked(record, index){
      this.$emit('rowclicked', record, index)
    },
    loadPerPage(val){
      this.$emit('per_page', val)
    },
    inputsearch(val){
      this.$emit('inputsearch', val)
    }
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>