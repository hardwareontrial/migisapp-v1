<template>
  <div>
    <materi-table
      :items="materilists"
      :fields="fields"
      :meta="meta"
      :isbusy="busy"
      @per_page="handlePerPage"
      @pagination="handlePagination"
      @search="handleSearch"
      @sort="handleSort">
      <template #table-busy>
        <div class="text-center my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>
      <template v-slot:addbutton>
        <v-select
          @input="handleIsactiveFilter"
          v-model="isactivefilter"
          :options="optActive"
          label="text"
          :reduce="text=>text.value"
          :clearable="false"
          class="w-50 mr-25">
        </v-select>
        <b-button
          type="button"
          v-if="$can('create', 'AppOKMMaterial')"
          variant="flat-primary"
          class="btn-icon rounded-circle"
          :to="{ name: 'apps-elearning-materials-create' }">
          <feather-icon icon="PlusCircleIcon" size="20"/>
        </b-button>
      </template>
      <template #cell(materiname)="data">
        {{ data.item.m_title }}
      </template>
      <template #cell(dept)="data">
        {{ data.item.deptname.name }}
      </template>
      <template #cell(w_material)="data">
        {{ data.item.m_duration }} Menit
      </template>
      <template #cell(total_question)="data">
        {{ data.item.questionslist ? data.item.questionslist.length : 0 }} Soal
      </template>
      <template #cell(opt)="data">
        <b-button-group>
          <b-button
            :to="{ name: 'apps-elearning-materials-detail', params: { id: data.item.id, slug: data.item.slug, title: data.item.title } }"
            variant="flat-primary"
            class="btn-icon"
            size="sm"
            type="button">
            <feather-icon icon="FileIcon" />
          </b-button>
          <b-button
            :disabled="data.item.questionslist.length > 0"
            v-if="(data.item.questionslist.length <= 0) || $can('update', 'AppOKMMaterial')"
            @click="setactive(data.item)"
            :variant="!!data.item.isactive ? 'flat-secondary':'flat-warning'"
            class="btn-icon"
            size="sm"
            type="button">
            <feather-icon :icon="!!data.item.isactive ? 'EyeOffIcon':'EyeIcon'" />
          </b-button>
          <b-button
            :disabled="data.item.questionslist.length > 0"
            v-if="(data.item.questionslist.length <= 0) || $can('delete', 'AppOKMMaterial')"
            @click="deletematerial(data.item.id)"
            variant="flat-danger"
            class="btn-icon"
            size="sm"
            type="button">
            <feather-icon icon="Trash2Icon" />
          </b-button>
        </b-button-group>
      </template>
      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>
    </materi-table>
  </div>
</template>

<script>
import MateriTable from '@/component/utils/Datatable.vue'
import http from '@/customs/axios'
import {
  BButton, BButtonGroup,
} from 'bootstrap-vue'
import vSelect from 'vue-select'

export default {
  props: {
    deptList: {
      type: Array,
    }
  },
  components: {
    MateriTable,
    BButton, BButtonGroup,
    vSelect,
  },
  data(){
    return{
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'id',
      sortByDesc: true,
      busy: false,
      isactivefilter: 1,
      fields: [
        { key: 'materiname', label: 'Soal', thClass: 'text-left', thStyle: { width: "30%" } },
        { key: 'dept', label: 'Departemen', thClass: 'text-center', tdClass: 'text-center' },
        { key: 'w_material', label: 'Bobot Materi', thClass: 'text-center', tdClass: 'text-center' },
        { key: 'total_question', label: 'Jumlah Soal', thClass: 'text-center', tdClass: 'text-center' },
        { key: 'opt', label: 'Opsi', thClass: 'text-center', tdClass: 'text-center' },
      ],
      materilists: [],
      optActive: [
        {text: 'Semua', value: ''},
        {text: 'Aktif', value: 1},
        {text: 'Non-Aktif', value: 0},
      ]
    }
  },
  methods: {
    handlePerPage(val){
      this.per_page = val
      this.handleFetchData()
    },
    handlePagination(val){
      this.current_page = val
      this.handleFetchData()
    },
    handleSearch(val){
      this.search = val
      this.handleFetchData()
    },
    handleSort(val){
      this.sortBy = val.sortBy
      this.sortByDesc = val.sortByDesc
      this.handleFetchData()
    },
    handleIsactiveFilter(val){
      this.isactivefilter = val
      this.handleFetchData()
    },
    handleFetchData(){
      this.busy = true
      http
      .get('okm/material/all', { 
        params: {
          page: this.current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC',
          isactive: this.isactivefilter,
        } 
      })
      .then((res) => {
        var datamaterial = res.data.message
        this.materilists = datamaterial.data
        this.meta = {
          total: datamaterial.total,
          current_page: datamaterial.current_page,
          per_page: datamaterial.per_page,
          from: datamaterial.from,
          to: datamaterial.to
        }
        this.busy = false
      })
      .catch((e) => { console.error(e) })
    },
    setactive(val){
      let newstatusactive = val.isactive === 1 ? 0 : 1
      let id = val.id
      var form = {
        title: val.m_title,
        dept: val.dept_id,
        level: val.m_level,
        duration: val.m_duration,
        desc: val.m_desc,
        isactive: newstatusactive,
      }
      this.busy = true
      http
      .put('okm/material/'+id, form)
      .then(() => {
        this.busy = false
        this.handleFetchData()
      })
      .catch((e) => {
        console.error(e)
      })
    },
    deletematerial(id){
      this.$swal({
        text: 'Yakin akan dihapus?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        customClass: {
          confirmButton: 'btn btn-outline-primary',
          cancelButton: 'btn btn-danger ml-1',
        },
        buttonsStyling: false,
      }).then(result => {
        if(result.value){
          this.busy = true
          http
          .delete('okm/material/'+id)
          .then((res) => {
            console.log(res.data)
            this.handleFetchData()
            this.busy = false
          })
          .catch((e)=> {
            console.error(e)
          })
        }
      })
    }
  },
  mounted(){
    this.handleFetchData()
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>