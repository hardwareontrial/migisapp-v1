<template>
  <div>
    
    <Table
      v-show="sizescreen === 'lg' || sizescreen === 'xl'"
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
          type="button"
          v-if="$can('create', 'AppDelivery')"
          variant="primary"
          :to="{ name: 'apps-sj-create' }">
          <span class="text-nowrap">Tambah</span>
        </b-button>
      </template>

      <template #table-busy>
        <div class="text-center text-primary my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>

      <template #cell(number)="data">
        <strong>{{ data.item.delivery_no }}</strong>
        <small v-if="data.item.po_no"><br>PO: {{ data.item.po_no }}</small>
        <small v-if="data.item.do_no"><br>DO: {{ data.item.do_no }}</small>
      </template>

      <template #cell(customer)="data">
        <strong>{{ data.item.detail.detail_customer }}</strong>
        <br><small>{{ data.item.detail.detail_address }}</small>
        <br><small>{{ data.item.detail.detail_city }}</small>
      </template>

      <template #cell(transporter)="data">
        {{ data.item.detail.detail_driver }}
        <br><small>{{ data.item.detail.detail_nopol }}</small>
      </template>

      <template #cell(sending_date)="data">
        {{ $moment(data.item.detail.detail_sending_date).format('DD/MMM/YY') }}
      </template>

      <template #cell(item)="data">
        {{ data.item.detail.detail_item }}
        <br><small>{{ data.item.detail.detail_qty }} {{ data.item.detail.detail_uom }}</small>
      </template>

      <template #cell(creator)="data">
        {{ data.item.creator.name }}<br>
        <small>{{ $moment(data.item.created_at).format('DD/MMM/YY') }}</small>
      </template>

      <template #cell(s_r)="data">
        <div v-if="+data.item.is_processing" class="text-center">
          <b-form-checkbox
            style="margin-left: 50%;"
            :checked="+data.item.is_processing"
            @change="processInvoiced(data.item.delivery_no)"
            v-if=" $can('invoice', 'AppDelivery')"/>
          <em v-else>Processing</em>
        </div>
        <div v-if="+data.item.is_remark">
          {{ data.item.invoice_no }}
        </div>
      </template>

      <template #cell(opsi)="data">
        <b-dropdown
          variant="link"
          no-caret>
          <template #button-content>
            <feather-icon
              icon="MoreVerticalIcon"
              size="16"
              class="align-middle text-body"/>
          </template>
          <b-dropdown-item @click="processPrinted(data.item, true)">
            <feather-icon icon="PrinterIcon"/>
            <span class="align-middle ml-50">Print</span>
          </b-dropdown-item>
          <b-dropdown-item 
            :disabled="data.item.print_count > 0 || data.item.is_remark === '1' ? true : false || !$can('edit', 'AppDelivery') || data.item.created_by !== userdata.detailuser.id"
            :to="{ name: 'apps-sj-edit', params: { id: data.item.delivery_no }}">
            <feather-icon icon="EditIcon" />
            <span class="align-middle ml-50">Edit</span>
          </b-dropdown-item>
        </b-dropdown>
      </template>

      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>

    </Table>

    <m-table
      v-show="sizescreen === 'xs' || sizescreen === 'sm' || sizescreen === 'md'"
      :items="items"
      :meta="meta"
      @per_page="handlePerPage"
      @pagination="handlePagination"
      @search="handleSearch"
      @sort="handleSort">
      <template v-slot:menu-mobile-data>
        <b-dropdown
          right
          variant="link"
          no-caret>
          <template #button-content>
            <feather-icon
              icon="MoreVerticalIcon"
              size="16"
              class="align-middle text-body"/>
          </template>
          <b-dropdown-item
            :to="{ name: 'apps-sj-create' }"
            :disabled="!$can('create', 'AppDelivery')">
            <feather-icon icon="PlusCircleIcon"/> Tambah
          </b-dropdown-item>
          <b-dropdown-item
            @click="handleExport">
            <feather-icon icon="UploadIcon"/> Export
          </b-dropdown-item>
        </b-dropdown>
      </template>
      <template v-slot:default="data">
        <app-collapse-item :title="data.item.delivery_no">
          <b-table-simple responsive small>
            <b-tbody>
              <b-tr v-if="data.item.po_no">
                <b-th 
                  sticky-column 
                  style="width: 25%; font-size: 10pt;">
                    PO 
                </b-th>
                <b-td style="font-size: 10pt;"> {{ data.item.po_no }} </b-td>
              </b-tr>
              <b-tr v-if="data.item.do_no">
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> DO </b-th>
                <b-td style="font-size: 10pt;"> {{ data.item.do_no }} </b-td>
              </b-tr>
              <b-tr>
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Customer </b-th>
                <b-td style="font-size: 10pt;"> {{ data.item.detail.detail_customer }} </b-td>
              </b-tr>
              <b-tr>
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Alamat </b-th>
                <b-td style="font-size: 10pt;"> {{ data.item.detail.detail_address }} </b-td>
              </b-tr>
              <b-tr>
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Tgl Kirim </b-th>
                <b-td style="font-size: 10pt;"> {{ $moment(data.item.detail.detail_sending_date).format('DD/MMM/YYYY') }} </b-td>
              </b-tr>
              <b-tr>
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Item/Qty </b-th>
                <b-td style="font-size: 10pt;"> {{ data.item.detail.detail_qty }} {{ data.item.detail.detail_uom }} </b-td>
              </b-tr>
              <b-tr>
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Creator </b-th>
                <b-td style="font-size: 10pt;"> {{ data.item.creator.name }} </b-td>
              </b-tr>
              <b-tr>
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Tgl Dibuat </b-th>
                <b-td style="font-size: 10pt;"> {{ $moment(data.item.created_at).format('DD/MMM/YYYY') }} </b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <div class="d-flex justify-content-end">
            <b-button
              @click="processPrinted(data.item, true)"
              variant="flat-primary"
              class="btn-icon rounded-circle">
              <feather-icon icon="PrinterIcon"/>
            </b-button>
            <b-button
              :disabled="data.item.print_count > 0 || data.item.is_remark === '1' ? true : false || !$can('edit', 'AppDelivery') || data.item.created_by !== userdata.detailuser.id"
              :to="{ name: 'apps-sj-edit', params: { id: data.item.delivery_no }}"
              variant="flat-primary"
              class="btn-icon rounded-circle">
              <feather-icon icon="EditIcon" />
            </b-button>
          </div>
        </app-collapse-item>
      </template>
    </m-table>
  </div>
</template>

<script>
import http from '@/customs/axios'
import { 
  BRow, BCol,
  BButton,
  BDropdown, BDropdownItem,
  BFormCheckbox,
  BTable, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd,
} from 'bootstrap-vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'

export default {
  components: {
    Table: () => import('@/component/utils/Datatable.vue'),
    mTable: () => import('@/component/utils/MobileDatatable.vue'),
    BRow, BCol,
    BButton,
    BDropdown, BDropdownItem,
    BFormCheckbox,
    AppCollapseItem,
    BTable, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd,
  },
  data(){
    return{
      // Table
      fields: [
        { key: 'number', label: 'Nomor', thStyle: { width: "15%" }, thClass: 'text-center' },
        { key: 'customer', label: 'Customer', thClass: 'text-center' },
        { key: 'transporter', label: 'Transporter', thStyle: { width: "12%" }, thClass: 'text-center' },
        { key: 'sending_date', label: 'Tgl Kirim', thStyle: { width: "10%" }, thClass: 'text-center' },
        { key: 'item', label: 'Item/Qty', thStyle: { width: "10%" }, thClass: 'text-center' },
        { key: 'creator', label: 'Creator', thStyle: { width: "12%" }, thClass: 'text-center' },
        { key: 's_r', label: 'Remark/Status', thStyle: { width: "5%" } },
        { key: 'opsi', thStyle: { width: "5%" }, thClass: 'text-center', tdClass: 'text-center' },
      ],
      items: [],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'created_at',
      sortByDesc: true,
      busy: false,
      count: 1,
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
      http.get('deliverynote/data', {
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
        this.busy = false
      })
      .catch((e) => { 
        console.error(e)
      })
    },
    processInvoiced(val){
      called.$emit('suratjalanInvoiceShow', val)
    },
    processPrinted(val, val2){
      called.$emit('suratjalanPrintShow', val, val2)
    },
    handleExport(){
      http
      .get('test/export', { responseType: 'arraybuffer'})
      .then((res) => {
        // console.log(res)
      })
      .catch((e) => {console.error(e)})
    },
    testreceiveitem(){
      // console.log('button clicked')
      let timenow = this.$moment.now()
      let code = 'S-'+this.$moment(timenow).format('YYMMDD')+'000'+`${this.count++}`
      let newdata = {
        delivery_no: code,
        do_no: "",
        po_no: 'ALIND0'+`${this.count++}`+this.$moment(timenow).format('YYMMDD'),
        created_by: 56,
        is_processing: "1",
        is_remark: "0",
        invoice_no: "",
        print_count: 1,
        created_at: "2022-04-27T04:41:00.000000Z",
        updated_at: "2022-04-27T04:41:00.000000Z",
        detail: {
            deliveryno_id: code,
            detail_customer: "PT. AIR LIQUIDE INDONESIA (JABABEKA )",
            detail_address: "Kws Ind jababeka 2 Blok PP 1 CIKARANG CIKARANG",
            detail_city: "CIKARANG",
            detail_nopol: "B 9140 FFU",
            detail_driver: "Erwan",
            detail_item: "Liquid CO2",
            detail_qty: 6000,
            detail_uom: "KG",
            detail_sending_date: "2022-04-27",
            created_at: "2022-04-27T04:41:00.000000Z",
            updated_at: "2022-04-27T04:41:00.000000Z"
        },
        creator: {
            id: 56,
            nik: "2130158",
            name: "MESRAWATI",
            dept_id: null,
            position_id: 15,
            group_id: null,
            avatar: "MESRAWATI.jpg",
            active: 1,
            created_at: "2022-07-25T06:00:00.000000Z",
            updated_at: "2022-07-25T06:00:00.000000Z",
            avatarlink: "http://127.0.0.1:8000/storage/app_user/avatar/MESRAWATI.jpg"
        }
      }
      this.items.unshift(newdata)
      this.items.pop()
      this.meta.total = this.meta.total +1
    }
  },
  mounted(){
    this.fetchData()
  },
  created(){
    // console.clear()
    called.$on('suratjalanlistFetchData', () => { this.fetchData() })
  },
  computed: {
    sizescreen(){
      return this.$store.getters['app/currentBreakPoint']
    },
    userdata(){
      return JSON.parse(localStorage.getItem('userdata'))
    }
  }
}
</script>

<style>

</style>