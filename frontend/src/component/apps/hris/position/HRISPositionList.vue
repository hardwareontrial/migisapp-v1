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

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>

      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>

      <template #cell(position)="data">
        {{ data.item.name }} <br>
        <small class="text-mute">{{ data.item.deptname.name }}</small>
      </template>

      <template #cell(user)="data">
        <div v-if="items[data.index].isEdit">
          <b-row>
            <b-col cols="12">
              <v-select
                :reduce="name=>name.id"
                :options="userlist"
                label="name"
                multiple
                v-model="items[data.index].form.usersid">
              </v-select>
            </b-col>
          </b-row>
        </div>
        <div v-else>
          <div v-if="data.item.user.length > 0">
            <span v-for="(u, i) in data.item.user" :key="i"> {{ u.name }}, </span>
          </div>
          <span v-else> </span>
        </div>
        
      </template>

      <template #cell(opsi)="data">
        <b-button
          @click=editField(data)
          v-if="!items[data.index].isEdit && $can('edit', 'AppHRPosition')"
          variant="flat-primary"
          class="btn-icon rounded">
          <feather-icon icon="EditIcon" size="16"/>
        </b-button>
        <div v-else>
          <b-button
            @click=submitAction(data)
            variant="flat-success"
            class="btn-icon rounded">
            <feather-icon icon="CheckCircleIcon" size="16"/>
          </b-button>
          <b-button
            @click=cancelAction(data)
            variant="flat-danger"
            class="btn-icon rounded">
            <feather-icon icon="XCircleIcon" size="16"/>
          </b-button>
        </div>
      </template>

    </Table>

  </div>
</template>

<script>
import http from '@/customs/axios'
import { 
  BDropdown, BDropdownItem,
  BFormSelect, BFormSelectOption,
  BRow, BCol, 
  BButton,
} from 'bootstrap-vue'
import vSelect from 'vue-select'

export default {
  components: {
    BDropdown, BDropdownItem,
    BFormSelect, BFormSelectOption,
    BRow, BCol,
    BButton,
    Table: () => import('@/component/utils/Datatable.vue'),
    vSelect,
  },
  data(){
    return{
      fields: [
        { key: 'position', label: 'Nama Jabatan', sortable: false, thStyle: { width: "25%" }, thClass: 'text-left' },
        { key: 'user', label: 'Nama User', sortable: false, thStyle: { width: "" }, thClass: 'text-left' },
        { key: 'opsi', label: 'Opsi', sortable: false, thStyle: { width: "12%" }, thClass: 'text-center', tdClass: 'text-center' },
      ],
      items: [],
      meta: [],
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'dept_id',
      sortByDesc: false,
      busy: false,
      selecteduser: '',
      userlist: [],
      selecteduser: [],
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
      .get('hr/position/data', {
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
          form: { positionid: item.id, usersid: item.user.map(({ id }) => id ) } 
        }))
      })
      .catch((err) => {
        console.error(error.response)
      })
      .finally(() => { this.busy = false })
    },
    loadListUser(){
      http
      .get('misc/list/userlist')
      .then(({data}) => {
        const userlist = data.map((x) => {
          return {
            id: x.id,
            avatar: x.avatarlink,
            name: x.name,
            nik: x.nik,
          }
        })
        this.userlist = userlist
      })
      .catch((e) => { console.error(e) })
    },
    editField(data){
      this.items[data.index].isEdit = !this.items[data.index].isEdit
    },
    cancelAction(data){
      this.items[data.index].isEdit = !this.items[data.index].isEdit
    },
    submitAction(data){
      const dataform = this.items[data.index].form
      http
        .post('hr/position/data/'+dataform.positionid, dataform, { params: {keyword: 'update-from-position-list'} })
        .then((res) => {
          this.fetchData()
        })
        .catch((e) => {
          console.error(e)
        })
    }
  },
  mounted() {
    this.fetchData()
    this.loadListUser()
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>