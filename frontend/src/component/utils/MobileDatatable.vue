<template>
  <div style="height: inherit">
    <b-row>
      <b-col>
        <div class="d-flex align-items-center justify-content-start mb-1">
          <v-select
            :clearable="false"
            :options="optionPerPage"
            v-model="meta.per_page"
            @input="loadPerPage"
            label="text"
            :reduce="text=>text.value"
            class="per-page-selector d-inline-block"/>
          <slot name="addon-fiter-menu"></slot>
        </div>
      </b-col>
      <b-col>
        <div class="d-flex justify-content-end mb-1">
          <slot name="menu-mobile-data"></slot>
        </div>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols="12">
        <b-form-input
          v-model="searchquery"
          class="d-inline-block"
          placeholder="Cari..."
          type="text"
          debounce="700"/>
      </b-col>
    </b-row>
    <div>
      <!-- <slot name="item-accordion"></slot> -->
      <app-collapse
        accordion
        type="margin"
        v-for="(item, i) in items"
        :key="i">
        <slot :item="item"></slot>
      </app-collapse>
    </div>
    <div class="mx-1 mt-2">
      <b-row>
        <b-col
          cols="12"
          class="d-flex align-items-center justify-content-center">
          <b-pagination
           align="right"
           v-model="meta.current_page"
           :total-rows="meta.total"
           :per-page="meta.per_page"
           @change="changePage"
           aria-controls="dt-custom">
          </b-pagination>
        </b-col>
        <b-col
          cols="12"
          class="d-flex align-items-center justify-content-center">
          <small>Tampil {{ meta.from }} sampai {{ meta.to }} dari {{ meta.total }} item.</small>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
import {
  BRow, BCol,
  BDropdown, BDropdownItem,
  BFormInput,
  BPagination,
} from 'bootstrap-vue'
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import vSelect from 'vue-select'

export default {
  components: {
    BRow, BCol,
    BDropdown, BDropdownItem,
    BFormInput,
    AppCollapse,
    BPagination,
    vSelect,
  },
  props: {
    items: {
      type: Array,
      required: true,
    },
    meta: {
      type: Object,
      required: true
    },
  },
  computed: {
    sizescreen(){
      return this.$store.getters['app/currentBreakPoint']
    }
  },
  data(){
    return {
      optionPerPage: [
        { text: 10, value: 10 },
        { text: 25, value: 25 },
        { text: 50, value: 50 },
      ],
      sortBy: null,
      sortDesc: false,
      searchquery: '',
    }
  },
  methods: {
    loadPerPage(val){
      this.$emit('per_page', this.meta.per_page)
    },
    changePage(val){
      this.$emit('pagination', val)
    },
  },
  watch: {
    'searchquery': {
      handler: function(n,o){
        this.$emit('search', n)
      },
      deep: true
    }
  },
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>