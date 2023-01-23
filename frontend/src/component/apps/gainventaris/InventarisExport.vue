<template>
  <div>

    <b-card title="Filter">
      <b-row>
        <b-col cols="12" lg="4">
          <b-form-group
            label="Berdasarkan Code"
            label-for="f-code">
            <v-select
              id="f-code" 
              label="kd_brg"
              multiple
              :options="listCode"
              v-model="selectedCode">
              <template #selected-option="{ kd_brg }">
                <span class="ml-50 d-inline-block align-middle"> {{ kd_brg }} </span>
              </template>
              <template #option="{ kd_brg }">
                <span class="ml-50 d-inline-block align-middle"> {{ kd_brg }} </span>
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" lg="4">
          <b-form-group
            label="Berdasarkan User"
            label-for="f-user">
            <v-select
              id="f-user" 
              label="name"
              @input="setInputUser"
              :options="listUser"
              v-model="selectedUser">
              <template #option="{ name, avatar, avatarlink }">
                <b-avatar 
                  variant="info"
                  size="24"
                  :src="avatar ? avatarlink : null"/>
                <span class="ml-50 d-inline-block align-middle"> {{ name }} </span>
              </template>
              <template #selected-option="{ name }">
                <span class="ml-50 d-inline-block align-middle"> {{ name }} </span>
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col cols="12" lg="4">
          <b-form-group
            label="Berdasarkan Lokasi"
            label-for="f-lokasi">
            <v-select
              id="f-lokasi" 
              label="name"
              @input="setInputLocation"
              :options="listLokasi"
              v-model="selectedLokasi">
              <template #selected-option="{ name }">
                <span class="ml-50 d-inline-block align-middle"> {{ name }} </span>
              </template>
              <template #option="{ name }">
                <span class="ml-50 d-inline-block align-middle"> {{ name }} </span>
              </template>
            </v-select>
          </b-form-group>
        </b-col>
      </b-row>
    </b-card>

    <div class="mb-2"
      v-show="returndata.length !== 0">
      <b-dropdown
        class="mb-1"
        :size="sizescreen === 'xl' || sizescreen === 'lg' ? 'md' : 'sm'"
        text="Print"
        variant="primary">
        <b-dropdown-item
          :disabled="!$can('export', 'AppInventaris') || selectedPrint.length === 0"
          @click="printList">
          <feather-icon icon="PrinterIcon" class="mr-1"/>
          List
        </b-dropdown-item>
        <b-dropdown-item
          :disabled="!$can('export', 'AppInventaris') || selectedPrint.length === 0"
          @click="printQr">
          <feather-icon icon="PrinterIcon" class="mr-1"/>
          QR
        </b-dropdown-item>
        <b-dropdown-item
          :disabled="!$can('export', 'AppInventaris') || selectedPrint.length === 0 || selectedUser === null"
          @click="printLetter(1)">
          <feather-icon icon="PrinterIcon" class="mr-1"/>
          Serah Terima
        </b-dropdown-item>
        <b-dropdown-item
          :disabled="!$can('export', 'AppInventaris') || selectedPrint.length === 0 || selectedUser === null"
          @click="printLetter(2)">
          <feather-icon icon="PrinterIcon" class="mr-1"/>
          Serah Kembali
        </b-dropdown-item>
      </b-dropdown>
      <b-card no-body
       v-show="sizescreen === 'lg' || sizescreen === 'xl'">
        <b-table-simple class="bordered">
          <b-thead>
            <b-tr>
              <b-th class="text-center align-middle">Kode</b-th>
              <b-th class="text-center align-middle">Nama Barang</b-th>
              <b-th class="text-center align-middle">Nama User</b-th>
              <b-th class="text-center align-middle">Lokasi</b-th>
              <b-th class="text-left" style="width: 10%;">
                <div class="inline">
                <b-form-checkbox
                  @change="checkall"
                  v-model="checkallvalue">
                  <span style="font-size: 12px;">
                    Check
                  </span>
                </b-form-checkbox>
                </div>
              </b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <b-tr v-for="data in returndata" :key="data.id">
              <b-td class="text-center">{{ data.kd_brg }}</b-td>
              <b-td class="text-center">{{ data.nama_brg }}</b-td>
              <b-td class="text-center">
                {{ data.user1_id ? data.user1name.name : '' }}
                <span v-if="data.user2_id">| {{ data.user2name.name }} </span>
              </b-td>
              <b-td class="text-center">{{ data.locname.name }}</b-td>
              <b-td class="text-center">
                <b-form-checkbox
                  v-model="selectedPrint"
                  :value="data"
                  style="margin-left: 50%;"/>
              </b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </b-card>
      <b-card no-body
       v-show="sizescreen === 'xs' || sizescreen === 'sm' || sizescreen === 'md'">
        <b-table-simple responsive small>
          <b-thead>
            <b-tr>
              <b-th>
                <div class="inline">
                  <b-form-checkbox
                    @change="checkall"
                    v-model="checkallvalue">
                  </b-form-checkbox>
                </div>
              </b-th>
              <b-th style="font-size: 10pt;">Kode</b-th>
              <b-th style="font-size: 10pt;">Nama</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <b-tr v-for="data in returndata" :key="data.id">
              <b-td>
                <b-form-checkbox
                  v-model="selectedPrint"
                  :value="data"
                />
              </b-td>
              <b-td style="font-size: 10pt;">{{ data.kd_brg }}</b-td>
              <b-td style="font-size: 10pt;">{{ data.nama_brg }}</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </b-card >
    </div>
    
    <print-list
      ref="printlist"
      v-show="false"
      :location-name="selectedLokasiName"
      :user-name="selectedUserName"
      :selected-print="selectedPrint" />
    
    <print-qr 
      ref="printqr"
      v-show="false"
      :selected-print="selectedPrint"/>

    <print-letter
      ref="printletter"
      v-show="false"
      :selected-print="selectedPrint"
      :user-name="selectedUserName"
      :user-id="selectedUserId"
      :user-nik="selectedUserNik"
      :user-level="selectedUserLevel"
      :user-position="selectedUserPosition"/>

  </div>
</template>

<script>
import {
  BCard,
  BRow, BCol,
  BFormGroup, 
  BButton, BButtonGroup,
  BTable, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd,
  BFormCheckbox,
  BDropdown, BDropdownItem,
  BAvatar,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import http from '@/customs/axios'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: { 
    BCard,
    BRow, BCol,
    BFormGroup, 
    BButton, BButtonGroup,
    BTable, BTableSimple, BThead, BTbody, BTfoot, BTr, BTh, BTd,
    BFormCheckbox,
    BDropdown, BDropdownItem,
    BAvatar,
    PrintList: () => import('@/component/apps/gainventaris/_InventarisPrintList.vue'),
    PrintQr: () => import('@/component/apps/gainventaris/_InventarisPrintQr.vue'),
    PrintLetter: () => import('@/component/apps/gainventaris/_InventarisPrintTurnInLetter.vue'),
    vSelect,
  },
  data(){
    return{
      selectedLokasi: null,
      selectedLokasiId: null,
      selectedLokasiName: null,
      listLokasi: [],

      selectedUser: null,
      selectedUserId: null,
      selectedUserName: null,
      selectedUserNik: null,
      selectedUserLevel: null,
      selectedUserPosition: null,
      listUser: [],

      selectedCode: null,
      selectedCodeId: null,
      selectedCodeName: null,
      listCode: [],

      returndata: [],
      selectedPrint: [],
      lettertype: null,
      checkallvalue: false,
    }
  },
  methods: {
    getListLokasi(){
      http
      .get('misc/list/location')
      .then(({data}) => {
        this.listLokasi = data
      })
      .catch((e) => { console.error(e) })
    },
    setlocation(val){
      this.selectedLokasi = val
      this.selectedLokasiId = val.id
      this.selectedLokasiName = val.name
      this.handleDataFromLokasi()
    },
    setInputLocation(val){
      if(val !== null){
        this.setlocation(val)
      }
    },
    handleDataFromLokasi(){
      this.returndata = []
      http
      .get('inventaris/export/bylocation', { params: { id: this.selectedLokasiId } })
      .then((data) => {
        if(data.data.length <= 0) {
          this.$toast({
            component: ToastificationContent,
            props: {
              icon: 'AlertTriangleIcon',
              variant: 'danger',
              title: 'Data tidak ditemukan.'
            },
          },
          { position: 'top-right' })
        }
        this.returndata = data.data
      })
      .catch((e) => { console.error(e) })
    },
    getListUser(){
      http
      .get('misc/list/userlist')
      .then(({data}) => {
        this.listUser = data
      })
      .catch((e) => { console.error(e) })
    },
    setUser(val){
      this.selectedUser = val
      this.selectedUserId = val.id
      this.selectedUserName = val.name
      this.selectedUserNik = val.nik,
      this.selectedUserLevel = val.position ? val.position.level : null,
      this.selectedUserPosition = val.position ? val.position.name : null,
      this.handleDataFromUser()
    },
    setInputUser(val){
      if(val !== null){
        this.setUser(val)
      }
    },
    handleDataFromUser(){
      this.returndata = []
      http
      .get('inventaris/export/byuser', { params: { id: this.selectedUserId } })
      .then((data) => {
        // console.log(data)
        if(data.data.message.length <= 0) {
          this.$toast({
            component: ToastificationContent,
            props: {
              icon: 'AlertTriangleIcon',
              variant: 'danger',
              title: 'Data tidak ditemukan.'
            },
          },
          { position: 'top-right' })
        }
        this.returndata = data.data.message
      })
      .catch((e) => { console.error(e) })
    },
    checkall(event){
      if(event === true){
        this.selectedPrint = this.returndata
      }else{
        this.selectedPrint = []
      }
    },
    getListCode(){
      http
      .get('misc/list/gainventaris')
      .then(({data}) => {
        this.listCode = data
      })
      .catch((e) => { console.error(e) })
    },
    printList(){
      this.$refs.printlist.printList()
    },
    printQr(){
      this.$refs.printqr.printqr()
    },
    printLetter(val){
      this.$refs.printletter.setLetterType(val)
    }
  },
  mounted(){
    this.getListLokasi()
    this.getListUser()
    this.getListCode()
  },
  created(){
    called.$on('inventarisexportsetlocation', (val) => { this.setlocation(val) })
    called.$on('inventarisexportsetuser', (val) => { this.setUser(val) })
  },
  watch: {
    'selectedLokasi': {
      handler(n){
        if(n === null){
          this.selectedLokasiId = null
          this.selectedLokasiName = null
          this.returndata = []
          this.checkallvalue = false
        }else{
          this.selectedUser = null
          this.selectedCode = null
        }
      }
    },
    'selectedUser': {
      handler(n){
        if(n === null){
          this.selectedUserId = null
          this.selectedUserName = null
          this.returndata = []
          this.selectedUserNik = null
          this.selectedUserLevel = null,
          this.selectedUserPosition = null
          this.checkallvalue = false
        }else{
          this.selectedLokasi = null
          this.selectedCode = null
        }
      }
    },
    'selectedCode': {
      handler(n){
        if(n === null){
          this.checkallvalue = false
        }else{
          this.selectedLokasi = null
          this.selectedUser = null
          this.returndata = this.selectedCode
        }
      }
    }
  },
  computed: {
    sizescreen(){
      return this.$store.getters['app/currentBreakPoint']
    }
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>

<style lang="scss" scoped>
  @import '~@core/scss/base/bootstrap-extended/include';
  .center-check-box {
    text-align:center; 
    vertical-align:middle;
  }
</style>