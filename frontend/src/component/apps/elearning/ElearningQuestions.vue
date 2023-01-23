<template>
  <div>
    <data-table
      :items="questionsList"
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
          @input="handleSetActive"
          v-model="qstisactive"
          :options="optActive"
          label="text"
          :reduce="text=>text.value"
          :clearable="false"
          class="w-50">
        </v-select>
        <b-button
          type="button"
          variant="flat-primary"
          class="btn-icon rounded-circle"
          :to="{ name: 'apps-elearning-questions-create' }">
          <feather-icon icon="PlusCircleIcon" size="20"/>
        </b-button>
      </template>

      <template #cell(question)="data">
        {{ data.item.title }}
      </template>

      <template #cell(mat_id)="data">
        {{ data.item.material.m_title }}
      </template>

      <template #cell(level)="data">
        <b-badge 
          :variant="levelOptions[2][data.item.level]"> 
          {{ levelOptions[1][data.item.level] }}
        </b-badge>
      </template>

      <template #cell(total_question)="data">
        <div class="text-center">
          {{ data.item.questions.length }} Item
          </div>
      </template>

      <template #cell(opt)="data">
        <div class="text-center">
          <b-dropdown
            variant="link"
            no-caret>
            <template #button-content>
              <feather-icon
                icon="MoreVerticalIcon"
                size="16"
                class="align-middle text-body"/>
            </template>
            <b-dropdown-item 
              :to="{ name: 'apps-elearning-questions-detail', params: { id: data.item.id, slug: data.item.slug, title: data.item.title } }">
              <feather-icon icon="ListIcon"/>
              <span class="align-middle ml-50">Detail</span>
            </b-dropdown-item>
            <b-dropdown-item
              @click="setactivequestion({id: data.item.id, active: !!data.item.isactive })">
              <feather-icon :icon="!!data.item.isactive ? 'EyeOffIcon' : 'EyeIcon'"/>
              <span class="align-middle ml-50">{{ !!data.item.isactive ? 'Set Non-Aktif' : 'Set Aktif' }}</span>
            </b-dropdown-item>
            <b-dropdown-item
              @click="deletequestions(data.item.id)">
              <feather-icon icon="Trash2Icon"/>
              <span class="align-middle ml-50">Hapus</span>
            </b-dropdown-item>
          </b-dropdown>
        </div>
      </template>

      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>

    </data-table>
  </div>
</template>

<script>
import { 
  BButton,
  BBadge,
  BDropdown, BDropdownItem,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'

export default {
  props: {
    levelOptions: {
      type: Array,
    }
  },
  components: {
    DataTable: () => import('@/component/utils/Datatable.vue'),
    BButton,
    BBadge,
    BDropdown, BDropdownItem,
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
      fields: [
        { key: 'question', label: 'Soal', thClass: 'text-left', thStyle: { width: "30%" } },
        { key: 'mat_id', label: 'Materi', thClass: 'text-center', thStyle: { width: "30%" }, tdClass: 'text-center' },
        { key: 'level', label: 'Level', thClass: 'text-center', tdClass: 'text-center' },
        { key: 'total_question', label: 'Pertanyaan', thClass: 'text-center' },
        { key: 'opt', label: 'Opsi', thClass: 'text-center' },
      ],
      questionsList: [],
      optActive: [
        { text: 'Semua', value: '' },
        { text: 'Aktif', value: '1' },
        { text: 'Non-Aktif', value: '0' },
      ],
      qstisactive: '1',
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
    handleFetchData(){
      this.busy = true
      http
      .get('okm/question/all', { 
        params: {
          page: this.current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: 'desc',
          isactive: this.qstisactive
        }
      })
      .then((res) => {
        let qstdata = res.data.message
        this.meta = {
          total: qstdata.total,
          current_page: qstdata.current_page,
          per_page: qstdata.per_page,
          from: qstdata.from,
          to: qstdata.to
        }
        this.questionsList = qstdata.data
        this.busy = false
      })
      .catch((e) => {
        console.error(e)
      })
    },
    deletequestions(id){
      this.$swal({
        text: 'Yakin akan dihapus?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
      }).then(result => {
        if(result.value){
          called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
          http
          .delete('okm/question/'+id)
          .then((res) => { 
            // console.log(res)
            this.handleFetchData()
            this.$toast({
              component: ToastificationContent,
              props: { icon: 'AlertCircleIcon', variant: 'primary', title: res.data.message },
            }, { position: 'top-right' })
            called.$emit('hideloading')  
          })
          .catch((e) => { console.error(e) })
        }else{
          console.log(result)
        }
      })
    },
    setactivequestion(options){
      var isactive = !options.active
      var updatestatus = +(isactive)
      http
      .put('okm/question/setcative/'+options.id, { setactive: updatestatus})
      .then((res) => {
        console.log(res.data)
        this.handleFetchData()
      })
      .catch((e) => { console.error(e) })
    },
    handleSetActive(val){
      this.qstisactive = val
      this.handleFetchData()
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