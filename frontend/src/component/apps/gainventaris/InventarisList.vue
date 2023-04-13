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

      <template #table-busy>
        <div class="text-center my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>

      <template v-slot:addbutton>
        <b-button
          type="button"
          v-if="$can('create', 'AppInventaris')"
          variant="primary"
          :to="{ name: 'apps-inventaris-add' }">
          Tambah
        </b-button>
      </template>

      <template #cell(code)="data">
        <strong> {{ data.item.kd_brg }} </strong>
        <div v-if="data.item.status_id === 2">
          <b-link @click="sentDetailUser(data.item.user1name)">
            <small>{{ data.item.user1name.name }}</small>
          </b-link><br>
          <b-link @click="sentDetailUser(data.item.user2name)" v-if="data.item.user2_id">
            <small>{{ data.item.user2name.name }}</small>
          </b-link>
        </div>
      </template>

      <template #cell(namabrg)="data">
        <strong> {{ data.item.nama_brg }} </strong>
      </template>

      <template #cell(dept)="data">
        <strong> {{ data.item.status_id === 2 ? (data.item.user1name.position ? data.item.user1name.position.deptname.name : '') : '' }} </strong>
      </template>

      <template #cell(status)="data">
        <feather-icon :icon="status[0][data.item.status_id]" size="20" :class="`text-${status[1][data.item.status_id]}`"/>
        <!-- <b-badge
          pill
          :variant="status[1][data.item.status_id]"
          class="badge-glow">
          {{ status[0][data.item.status_id] }}
        </b-badge> -->
      </template>

      <template #cell(location)="data">
        <b-link @click="sentDetailLokasi(data.item.locname)">
          {{ data.item.locname.name }}
        </b-link>
      </template>

      <template #cell(is_active)="data">
        <b-badge
          pill
          :variant="active[1][data.item.active]"
          class="badge-glow">
          {{ active[0][data.item.active] }}
        </b-badge>
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
          <b-dropdown-item @click="showDetail(data.item.id)">
            <feather-icon icon="EyeIcon"/>
            <span class="align-middle ml-50">Detail</span>
          </b-dropdown-item>
          <b-dropdown-item
            :to="{ name: 'apps-inventaris-edit', params: { id: data.item.kd_brg.split(' ').join('-') }, query: { q: data.item.id} }"
            :disabled="!$can('edit', 'AppInventaris')">
            <feather-icon icon="Edit3Icon" />
            <span class="align-middle ml-50">Edit</span>
          </b-dropdown-item>
          <b-dropdown-item
            @click="handleToggleActive(data.item.kd_brg.split(' ').join('-'))"
            :disabled="!$can('archieve', 'AppInventaris')">
            <feather-icon icon="ArchiveIcon" />
            <span class="align-middle ml-50" v-if="data.item.active === 1">Tandai di Gudang</span>
            <span class="align-middle ml-50" v-else>Aktifkan</span>
          </b-dropdown-item>
          <b-dropdown-item
            @click="handleOpenSellModal(data.item.id)"
            :disabled="!$can('sell', 'AppInventaris')">
            <feather-icon icon="ShoppingCartIcon" />
            <span class="align-middle ml-50">Tandai Jual</span>
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
            :to="{ name: 'apps-inventaris-add' }"
            :disabled="!$can('create', 'AppInventaris')">Tambah</b-dropdown-item>
        </b-dropdown>
      </template>
      <template v-slot:default="data">
        <app-collapse-item :title="data.item.kd_brg">
          <b-table-simple responsive small>
            <b-tbody>
              <b-tr>
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Nama </b-th>
                <b-td style="font-size: 10pt;"> {{ data.item.nama_brg }} </b-td>
              </b-tr>
              <b-tr>
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Aktif </b-th>
                <b-td style="font-size: 10pt;">
                  <b-badge
                    pill
                    :variant="active[1][data.item.active]"
                    class="badge">
                    {{ active[0][data.item.active] }}
                  </b-badge>
                </b-td>
              </b-tr>
              <b-tr>
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Status </b-th>
                <b-td style="font-size: 10pt;">
                  <b-badge
                    pill
                    :variant="status[1][data.item.status_id]"
                    class="badge">
                    {{ status[0][data.item.status_id] }}
                  </b-badge>
                </b-td>
              </b-tr>
              <b-tr>
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Lokasi </b-th>
                <b-td style="font-size: 10pt;">
                  <b-link @click="sentDetailLokasi(data.item.locname)">
                    {{ data.item.locname.name }}
                  </b-link>
                </b-td>
              </b-tr>
              <b-tr v-if="data.item.status_id === 2">
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> User </b-th>
                <b-td style="font-size: 10pt;">
                  <b-link @click="sentDetailUser(data.item.user1name)">
                    <small>{{ data.item.user1name.name }}</small>
                  </b-link>
                </b-td>
              </b-tr>
              <b-tr v-if="data.item.status_id === 2 && data.item.user2_id">
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> </b-th>
                <b-td style="font-size: 10pt;">
                  <b-link @click="sentDetailUser(data.item.user2name)">
                    <small>{{ data.item.user2name.name }}</small>
                  </b-link>
                </b-td>
              </b-tr>
              <b-tr v-show="data.item.status_id === 2">
                <b-th sticky-column style="width: 25%; font-size: 10pt;"> Dept </b-th>
                <b-td style="font-size: 10pt;">
                  {{ data.item.status_id === 2 ? (data.item.user1name.position ? data.item.user1name.position.deptname.name : '') : '' }}
                </b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <div class="d-flex justify-content-end">
            <b-button
              @click="showDetail(data.item.id)"
              variant="flat-warning"
              class="btn-icon rounded-circle">
              <feather-icon icon="EyeIcon" />
            </b-button>
            <b-button
              :to="{ name: 'apps-inventaris-edit', params: { id: data.item.kd_brg.split(' ').join('-') }, query: { q: data.item.id} }"
              variant="flat-warning"
              class="btn-icon rounded-circle">
              <feather-icon icon="Edit3Icon" />
            </b-button>
            <b-button
              variant="flat-warning"
              class="btn-icon rounded-circle">
              <feather-icon icon="ArchiveIcon" />
            </b-button>
            <b-button
              variant="flat-warning"
              class="btn-icon rounded-circle">
              <feather-icon icon="ShoppingCartIcon" />
            </b-button>
          </div>
        </app-collapse-item>
      </template>
    </m-table>

    <sell-modal ref="invsellmodal"/>

  </div>
</template>

<script>
import {
  BButton,
  BDropdown, BDropdownItem,
  BLink,
  BBadge,
  BTable, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd,
  BCard,
  BRow, BCol,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'

export default {
  components: {
    Table: () => import('@/component/utils/Datatable.vue'),
    mTable: () => import('@/component/utils/MobileDatatable.vue'),
    SellModal: () => import('@/component/apps/gainventaris/_InventarisInputSellPrice.vue'),
    BButton,
    BDropdown, BDropdownItem,
    BLink,
    BBadge,
    BTable, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd,
    BCard,
    BRow, BCol,
    AppCollapseItem,
  },
  data(){
    return{
      fields: [
        { key: 'code', label: 'Kode', thStyle: { width: "20%" } },
        { key: 'namabrg', label: 'Nama Barang', thClass: 'text-left' },
        { key: 'dept', label: 'Departemen', thStyle: { width: "12%" } },
        { key: 'status', label: 'Status', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'location', label: 'Lokasi', thStyle: { width: "15%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'is_active', label: 'Status', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
        { key: 'opsi', thStyle: { width: "10%" }, thClass: 'text-center', tdClass: 'text-center' },
      ],
      items: [],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'id',
      sortByDesc: true,
      busy: false,

      status: [
        { 1: 'BoxIcon', 2: 'UsersIcon', 3: 'BoxIcon', 4: 'ToolIcon' },
        { 1: 'secondary', 2: 'info', 3: 'info', 4: 'warning' }
      ],
      active: [
        { 0: 'Non-Aktif', 1: 'Aktif' },
        { 0: 'danger', 1: 'success' },
      ],
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
      .get('inventaris/data', {
        params: {
          page: this.current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC',
          authuserdept: this.userdata.paramsdept,
          authuseradmin: this.userdata.admin,
        }
      })
      .then((res) => {
        this.items = res.data.message.data
        let receivedData = res.data.message
        this.items = receivedData.data
        this.meta = {
          total: receivedData.total,
          current_page: receivedData.current_page,
          per_page: receivedData.per_page,
          from: receivedData.from,
          to: receivedData.to
        }
      })
      .catch((e) => {
        console.error(e)
      })
      .finally(() => { this.busy = false })
    },
    showDetail(val1){
      called.$emit('inventarisdetailshow', val1)
    },
    sentDetailUser(val){
      this.$router.push({ name: 'apps-inventaris-export' }).then(()=> { called.$emit('inventarisexportsetuser', val) })
    },
    sentDetailLokasi(val){
      this.$router.replace({ name: 'apps-inventaris-export' }).then(()=> {
        called.$emit('inventarisexportsetlocation', val)
      })
    },
    handleToggleActive(val){
      this.busy = true
      http
      .put('inventaris/data/'+val, { data: '' }, { params: { keyword: 'update-active-from-table' } })
      .then((data) => {
        this.handleFetchData()
        this.busy = false
      })
      .catch((e) => {
        console.error(e)
        this.handleFetchData()
        this.busy = false
      })
    },
    handleOpenSellModal(val){
      this.$refs.invsellmodal.openSell(val)
    },
  },
  mounted(){
    this.handleFetchData()
  },
  created(){
    called.$on('inventarislistFetchData', () => { this.handleFetchData() })
    called.$emit('_inventarisprintlistLoadListLokasi')
  },
  computed: {
    sizescreen(){
      return this.$store.getters['app/currentBreakPoint']
    },
    userdata(){
      let rawdata, useradmin, userdept, fdept;
      rawdata = JSON.parse(localStorage.getItem('userdata'))
      useradmin = rawdata.admin
      if(useradmin !== 1){
        userdept = rawdata.detailuser.position.dept_id
        if(userdept === 6){
          fdept = '='
        }else if(userdept = 8){
          fdept = '!='
        }
      }
      return { admin: useradmin, paramsdept: fdept, data: rawdata }
    }
  }
}
</script>

<style>

</style>
