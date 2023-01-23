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
      @sort="handleSort"
      @rowclicked="handleRowClicked">
      <template v-slot:addbutton>
        <b-button
          type="button"
          v-if="$can('create', 'AppPhonebook')"
          variant="primary"
          @click="openform()">
          <span class="text-nowrap">Tambah</span>
        </b-button>
      </template>
      <template #table-busy>
        <div class="text-center text-primary my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>
      <template #cell(name)="data">
        <strong v-if="data.item.type === 1"> {{ data.item.name }} </strong>
        <strong v-else> {{ data.item.company_name }} </strong>
      </template>
      <template #cell(address)="data">
        <strong> {{ data.item.address ? data.item.address : data.item.company_address }} </strong>
      </template>
      <template #cell(number)="data">
        {{ data.item.detail[0].number }}
        <br><small>{{ data.item.detail[0].pic ? data.item.detail[0].pic : null  }}</small>
      </template>
      <template #cell(opsi)="data">
        <div class="text-nowrap">
          <!-- <b-button
            type="button"
            @click="handleRowClicked(data.item)"
            variant="flat-primary"
            class="btn-icon">
            <feather-icon icon="EyeIcon" size="16" />
          </b-button> -->
          <b-button
            type="button"
            :disabled="!$can('edit', 'AppPhonebook')"
            @click="handleEdit(data.item.id)"
            :variant="$can('edit', 'AppPhonebook') ? 'flat-primary' : 'flat-secondary'"
            class="btn-icon">
            <feather-icon icon="EditIcon" size="16"/>
          </b-button>
          <b-button
            type="button"
            :disabled="!$can('delete', 'AppPhonebook')"
            @click="handleDelete(data.item.id)"
            :variant="$can('delete', 'AppPhonebook') ? 'flat-danger' : 'flat-secondary'"
            class="btn-icon">
            <feather-icon icon="Trash2Icon" size="16"/>
          </b-button>
        </div>
      </template>
      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>
    </table>
  </div>
</template>

<script>
import {
  BButton,
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  components: {
    Table: () => import('@/component/utils/Datatable.vue'),
    BButton,
  },
  data(){
    return{
      fields: [
        { key: 'name', label: 'Nama Kontak', sortable: false },
        { key: 'address', label: 'Alamat Kontak', sortable: false },
        { key: 'number', label: 'Nomor', sortable: false, thStyle: { width: "30%" } },
        { key: 'opsi', label: 'Opsi', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center', visible: false },
      ],
      items: [],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'id',
      sortByDesc: true,
      busy: false,
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
    handleRowClicked(record, index){
      called.$emit('phonebookdetailOpenModal', record)
    },
    handleFetchData(){
      this.busy = true
      http
      .get('phonebook/external/data', {
        params: {
          // page: current_page,
          page: this.current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      })
      .then((res) => {
        // console.log(res.data.message)
        let receivedData = res.data.message
        this.items = receivedData.data
        this.meta = {
          total: receivedData.total,
          current_page: receivedData.current_page,
          per_page: receivedData.per_page,
          from: receivedData.from,
          to: receivedData.to
        }
        this.busy = false
      })
      .catch((e) => { 
        console.error(e)
      })
    },
    openform(){
      called.$emit('phonebookaddOpenForm')
    },
    handleEdit(val){
      called.$emit('phonebookaddOpenEditForm', val)
    },
    handleDelete(val){
      this.busy = true
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
          http
          .delete('phonebook/external/data/'+val)
          .then((res) => {
            this.$swal({
              icon: 'success',
              text: res.data.message,
              customClass: {
                confirmButton: 'btn btn-success',
              },
            }).then(() => {
              this.handleFetchData()
              this.busy = false
            })
          })
          .catch((e) => {
            console.error(e)
          })
        }else{
          this.busy = false
        }
      })
    }
  },
  mounted(){
    this.handleFetchData()
    called.$on('phonebooklistHandleFetchData', () => { this.handleFetchData() })
  }
}
</script>

<style>

</style>