<template>
  <div>
    
    <Table
      :items="items"
      :fields="fields"
      :meta="meta"
      :isbusy="busy"
      @per_page="handlePerPage"
      @pagination="handlePagination"
      @search="handleSearch"
      @sort="handleSort">

     <template v-slot:addbutton>
        <b-button
          @click="deptformaddprop.show = !deptformaddprop.show"
          type="button"
          variant="primary">
          <span class="text-nowrap">Tambah</span>
        </b-button>
      </template>

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>

      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>

      <template #cell(dept)="data">
        <div v-if="items[data.index].isEdit">
          <b-form-input
            v-model="items[data.index].form.deptname"
            id="basicInput"
            placeholder="Enter Email"
          />
        </div>
        <div v-else>
          {{ data.item.name }}
        </div>
      </template>

      <template #cell(position)="data">
        <div v-if="items[data.index].isEdit">
          <v-select
            :options="listposition"
            label="name"
            :reduce="name => name.id"
            v-model="items[data.index].form.posid"
            multiple/>
        </div>
        <div v-else>
          <span v-for="(position, i) in data.item.positions" :key="i">{{ position.name }}, </span>
        </div>
      </template>

      <template #cell(opsi)="data">
        <div v-if="items[data.index].isEdit">
          <b-button-group>
            <b-button
              @click="updatedept(data)"
              variant="flat-success"
              class="btn-icon rounded">
              <feather-icon icon="CheckCircleIcon" size="14"/>
            </b-button>
            <b-button
              @click="items[data.index].isEdit = !items[data.index].isEdit"
              variant="flat-danger"
              class="btn-icon rounded">
              <feather-icon icon="XCircleIcon" size="14"/>
            </b-button>
          </b-button-group>
        </div>
        <div v-else>
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
              @click="items[data.index].isEdit = !items[data.index].isEdit">
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Edit</span>
            </b-dropdown-item>
            <b-dropdown-item
              @click="updateactivedept(data.item)">
              <feather-icon icon="PowerIcon" :class="data.item.isactive === '1' ? 'text-danger' : 'text-success' "/>
              <span class="align-middle ml-50"> {{ data.item.isactive === '1' ? 'Set Matikan' : 'Set Aktif' }} </span>
            </b-dropdown-item>
          </b-dropdown>
        </div>
      </template>
    </Table>
    
    <dept-form-add 
      :size="deptformaddprop.size"
      :show="deptformaddprop.show"
      :title="deptformaddprop.title"
      :hidefooter="deptformaddprop.hidefooter"
      ref="deptformaddprop">
      <template v-slot:modalbody>
        <b-form-group
          hidden
          label="ID Departemen"
          label-for="f-newdept-id">
          <b-form-input
            v-model="form.iddept"
            id="f-newdept-id"
            placeholder="ID Departemen"/>
        </b-form-group>
        <b-form-group
          label="Nama Departemen"
          label-for="f-newdept-name">
          <b-form-input
            v-model="form.deptname"
            id="f-newdept-name"
            placeholder="Nama Departemen"/>
        </b-form-group>
        <b-form-group
          label="Posisi"
          label-for="f-newdept-pos">
          <v-select
            :options="listposition"
            label="name"
            :reduce="name => name.id"
            v-model="form.idpos"
            multiple/>
        </b-form-group>
      </template>
      <template v-slot:modalfooter>
        <b-button
          @click="addnewdept()"
          variant="outline-primary"
          class="mr-1">
          Tambah
        </b-button>
        <b-button
          @click="deptformaddprop.show = !deptformaddprop.show"
          variant="danger">
          Batal
        </b-button>
      </template>
    </dept-form-add>
    
  </div>
</template>

<script>
import http from '@/customs/axios'
import { 
  BDropdown, BDropdownItem,
  BButton, BButtonGroup,
  BFormGroup, BFormInput,
} from 'bootstrap-vue'
import DeptFormAdd from '@/component/utils/Modal.vue'
import vSelect from 'vue-select'

export default {
  components: {
    BDropdown, BDropdownItem,
    Table: () => import('@/component/utils/Datatable.vue'),
    BButton, BButtonGroup,
    DeptFormAdd,
    BFormGroup, BFormInput,
    vSelect,
  },
  data(){
    return{
      fields: [
        { key: 'dept', label: 'Nama Departemen', sortable: false, thStyle: { width: "30%" }, thClass: 'text-left' },
        { key: 'position', label: 'List Jabatan', sortable: false, thStyle: { width: "" }, thClass: 'text-left' },
        { key: 'opsi', label: 'Opsi', sortable: false, thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
      ],
      items: [],
      meta: [],
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'created_at',
      sortByDesc: false,
      busy: false,

      deptformaddprop: {
        show: false,
        size: 'lg',
        title: 'Tambah Departemen',
        hidefooter: false
      },
      listposition: [],
      form: {
        iddept: '',
        deptname: '',
        idpos: [],
      }
    }
  },
  methods: {
    handlePerPage(val){
      this.per_page = val
      this.fetchData()
    },
    handlePagination(val){
      this.current_page = val
      this.fetchData()
    },
    handleSearch(val){
      this.search = val
      this.fetchData() 
    },
    handleSort(val){
      this.sortBy = val.sortBy
      this.sortByDesc = val.sortByDesc
      this.fetchData()
    },
    fetchData(){
      this.busy = true
      http
      .get('hr/department/data', {
        params: {
          page: this.current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      })
      .then((res) => {
        let receivedData = res.data.message
        this.items = receivedData.data
        this.meta = {
          total: receivedData.total,
          current_page: receivedData.current_page,
          per_page: receivedData.per_page,
          from: receivedData.from,
          to: receivedData.to
        }
        this.items = this.items.map(item => ({
          ...item, isEdit: false, 
          form: { deptid: item.id, posid: item.positions.map(({ id }) => id ), deptname: item.name },
          _rowVariant: item.isactive === '1' ? '':'danger'
        }))
      })
      .catch((err) => {
        console.error(error.response)
      })
      .finally(() => { this.busy = false })
    },
    getpositionlist(){
      http.get('misc/list/positions')
      .then((res) => { 
        this.listposition = res.data
       })
      .catch((e) => { console.error(e) })
    },
    addnewdept(){
      http.post('hr/department/new', this.form)
      .then((res) => { 
        this.form = {
          iddept: '',
          deptname: '',
          idpos: [],
        }
        this.fetchData()
        this.deptformaddprop.show = false
      })
      .catch((e) => { console.error(e) })
    },
    updatedept(data){
      const dataform = this.items[data.index].form
      http.post('hr/department/data/'+dataform.deptid, dataform, { params: {keyword: 'update-from-dept-list'} })
      .then((res) => {
        // console.log(res.data)
        this.items[data.index].isEdit = false
        this.fetchData()
      })
      .catch((e) => { console.error(e) })
    },
    updateactivedept(options){
      let newactive = options.isactive === '1' ? '0' : '1'
      http.post('hr/department/data/'+options.id, { newactive: newactive }, { params: {keyword: 'update-active-from-dept-list'} })
      .then((res) => {
        console.log(res.data)
        this.fetchData()
      })
      .catch((e) => { console.error(e) })
    }
  },
  mounted() {
    this.fetchData()
    this.getpositionlist()
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>