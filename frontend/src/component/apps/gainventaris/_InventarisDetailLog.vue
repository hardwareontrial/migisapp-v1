<template>
  <div>
    <b-overlay :show="showOverlay" rounded="sm" :opacity="0.90">
      <app-timeline>
        <li
          v-for="(item, i) in comlogs.data"
          v-bind="$attrs"
          class="timeline-item"
          :key="i"
          v-on="$listeners"
          :class="[`timeline-variant-${log[2][item.action]}`, fillBorder ? `timeline-item-fill-border-${log[2][item.action]}` : null]">
          
          <div
            class="timeline-item-icon d-flex align-items-center justify-content-center rounded-circle">
            <feather-icon :icon="log[1][item.action]" />
          </div>

          <slot>
            <div class="d-flex flex-sm-row flex-column flex-wrap justify-content-between mb-1 mb-sm-0">
              <h6><strong> {{ log[0][item.action] }} </strong></h6>
              <small class="timeline-item-time text-nowrap text-muted">
                {{ $moment(item.created_at).fromNow() }}
              </small>
            </div>

            <div v-if="item.action === 2">
              <div v-if="item.n_nama_brg !== item.o_nama_brg">
                Perubahan Nama: <em>{{ item.o_nama_brg }}</em> menjadi <em><strong>{{ item.n_nama_brg }}</strong></em>
              </div>

              <div v-if="item.n_status_id !== item.o_status_id" style="margin-bottom: 3px;">
                <b-badge :variant="status[1][item.o_status_id]">{{ status[0][item.o_status_id] }}</b-badge>
                  <b-icon icon="arrow-right-circle-fill" class="mx-1" variant="primary"></b-icon>
                <b-badge :variant="status[1][item.n_status_id]">{{ status[0][item.n_status_id] }}</b-badge>
              </div>

              <div>
                <div v-if="item.n_status_id === 2 && item.o_status_id === 2">
                  <div v-if="item.n_user1_id !== item.o_user1_id">
                    <p class="mb-0" v-if="item.n_user1_id !== null && item.o_user1_id !== null">
                      Perubahan User: <em>{{ item.user1nameold ? item.user1nameold.name : '' }}</em> menjadi <em><strong>{{ item.user1namenew ? item.user1namenew.name : '' }}</strong></em>.
                    </p>
                  </div>
                  <div v-if="item.n_user2_id !== item.o_user2_id">
                    <p class="mb-0" v-if="item.n_user2_id !== null && item.o_user2_id !== null">
                      Perubahan User: <em>{{ item.user2nameold ? item.user2nameold.name : '' }}</em> menjadi <em><strong>{{ item.user2namenew ? item.user2namenew.name : '' }}</strong></em>.
                    </p>
                    <p class="mb-0" v-if="item.n_user2_id !== null && item.o_user2_id === null">
                      Penambahan User: <em>{{ item.user2namenew ? item.user2namenew.name : '' }}</em>.
                    </p>
                    <p class="mb-0" v-if="item.n_user2_id === null && item.o_user2_id !== null">
                      Hapus User: <em>{{ item.user2nameold ? item.user2nameold.name : '' }}</em>.
                    </p>
                  </div>
                </div>
                <div v-if="item.n_status_id !== 2 && item.o_status_id === 2">
                  <p class="mb-0" v-if="item.o_user1_id !== null && item.o_user2_id !== null">
                    Dari User: {{ item.user1nameold ? item.user1nameold.name : '' }} dan {{ item.user2nameold ? item.user2nameold.name : '' }}
                  </p>
                  <p class="mb-0" v-if="item.o_user1_id !== null && item.o_user2_id === null">
                    Dari User: {{ item.user1nameold ? item.user1nameold.name : '' }}
                  </p>
                </div>
                <div v-if="item.n_status_id === 2 && item.o_status_id !== 2">
                  <p class="mb-0" v-if="item.n_user1_id !== null && item.o_user1_id === null">
                    User: {{ item.user1namenew ? item.user1namenew.name : '' }}
                  </p>
                  <p class="mb-0" v-if="item.n_user2_id !== null && item.o_user2_id === null">
                    User: {{ item.user2namenew ? item.user2namenew.name : '' }}
                  </p>
                </div>
              </div>

              <div v-if="item.n_tgl_beli !== item.o_tgl_beli">
                Perubahan Tgl Beli: <em>{{ item.o_tgl_beli }}</em> menjadi <em><strong>{{ item.n_tgl_beli }}</strong></em>
              </div>

              <div v-if="item.n_merk_id !== item.o_merk_id">
                Perubahan Merk: {{ item.merknameold ? item.merknameold.name : '' }} menjadi {{ item.merknamenew ? item.merknamenew.name : '' }}
              </div>

              <div v-if="item.n_lokasi_id !== item.o_lokasi_id">
                <p class="mb-0">
                  Pindah dari {{ item.locnameold ? item.locnameold.name : '' }} ke {{ item.locnamenew ? item.locnamenew.name : '' }}
                </p>
              </div>

              <div v-if="item.n_toko !== item.o_toko">
                <p class="mb-0" v-if="item.n_toko !== null && item.o_toko !== null">
                  Perubahan Toko: <em>{{ item.o_toko }}</em> menjadi <em><strong>{{ item.n_toko }}</strong></em>.
                </p>
                <p class="mb-0" v-if="item.n_toko === null && item.o_toko !== null">
                  Hapus Toko: <em>{{ item.o_toko }}</em>.
                </p>
                <p class="mb-0" v-if="item.n_toko !== null && item.o_toko === null">
                  Input Toko: <em>{{ item.n_toko }}</em>.
                </p>
              </div>

              <div v-if="item.n_harga !== item.o_harga">
                <p class="mb-0" v-if="item.n_harga !== null && item.o_harga !== null">
                  Perubahan Harga: <em>{{ item.o_harga }}</em> menjadi <strong><em>{{ item.n_harga }}</em></strong>.
                </p>
                <p class="mb-0" v-if="item.n_harga === null && item.o_harga !== null">
                  Hapus Harga: <em>{{ item.o_harga }}</em>.
                </p>
                <p class="mb-0" v-if="item.n_harga !== null && item.o_harga === null">
                  Input Harga: <em>{{ item.n_harga }}</em>.
                </p>
              </div>

              <div v-if="item.n_serialnumber !== item.o_serialnumber">
                <p class="mb-0" v-if="item.n_serialnumber !== null && item.o_serialnumber !== null">
                  Perubahan SN: <em>{{ item.o_serialnumber }}</em> menjadi <strong><em>{{ item.n_serialnumber }}</em></strong>.
                </p>
                <p class="mb-0" v-if="item.n_serialnumber === null && item.o_serialnumber !== null">
                  Hapus SN: <em>{{ item.o_serialnumber }}</em>.
                </p>
                <p class="mb-0" v-if="item.n_serialnumber !== null && item.o_serialnumber === null">
                  Input SN: <em>{{ item.n_serialnumber }}</em>.
                </p>
              </div>

              <div v-if="item.n_spesifikasi !== item.o_spesifikasi">
                <p class="mb-0" v-if="item.n_spesifikasi !== null && item.o_spesifikasi !== null">
                  Perubahan spesifikasi.
                </p>
                <p class="mb-0" v-if="item.n_spesifikasi === null && item.o_spesifikasi !== null">
                  Spesifikasi dihapus.
                </p>
                <p class="mb-0" v-if="item.n_spesifikasi !== null && item.o_spesifikasi === null">
                  Penambahan spesifikasi.
                </p>
              </div>

              <div v-if="item.n_active !== item.o_active">
                <p class="mb-0" v-if="item.n_active === 1 && item.o_active === 0">
                  <i class="fa-solid fa-archive" style="color: green;"></i> Dikembalikan dari Gudang.
                </p>
                <p class="mb-0" v-if="item.n_active === 0 && item.o_active === 1">
                  <i class="fa-solid fa-archive" style="color: tomato;"></i> Dipindah ke Gudang.
                </p>
              </div>

              <div v-if="item.n_status_id === 4">
                <p class="mb-0" v-if="item.mtc_note !== null">
                  <strong>Perawatan: </strong> {{ item.n_mtc_note }}
                </p>
              </div>
            </div>
            
            <!-- <p class="mb-0 text-muted"><small>Di {{ log[0][item.action] }} oleh {{ item.creator.name }}</small></p> -->
            <p class="mb-0 text-muted"><small>Di {{ log[0][item.action] }} oleh {{ item.created_by === userLoggedId ? 'Anda' : item.creator.name }}</small></p>

          </slot>
        </li>

      </app-timeline>

      <div class="text-center mt-2">
        <b-button
          @click="current_page --"
          type="button"
          size="sm"
          :disabled="current_page !== 1 ? false : true"
          v-show="current_page === 1 ? false : true"
          variant="outline-primary"
          class="rounded-circle btn-icon mr-1">
          <b-icon icon="arrow-left-circle"></b-icon>
        </b-button>
        <b-button
          @click="current_page ++"
          :disabled="comlogs.to >= comlogs.total "
          v-show="comlogs.to < comlogs.total "
          type="button"
          size="sm"
          variant="outline-primary" 
          class="rounded-circle btn-icon">
          <b-icon icon="arrow-right-circle"></b-icon>
        </b-button>
        <p class="mt-1">
          <small class="font-weight-bold">Tampil data ke- {{ comlogs.from }} sampai {{ comlogs.to }} dari {{ comlogs.total }}</small>
        </p>
      </div>

    </b-overlay>
  </div>
</template>

<script>
import {
  BOverlay,
  BBadge,
  BPagination, BPaginationNav,
  BButton,
  BIcon,
  BAlert,
} from 'bootstrap-vue'
import http from '@/customs/axios'
import AppTimeline from '@core/components/app-timeline/AppTimeline.vue'

export default {
  props: {
    // keydata: {
    //   type: Number/String,
    //   required: true,
    // },
    fillBorder: {
      type: Boolean,
      default: false,
    },
    detailLog: {
      type: Array,
    },
    userLoggedId: {
      type: Number, String,
    }
  },
  components: {
    BOverlay,
    BOverlay,
    BBadge,
    BPagination, BPaginationNav,
    BButton,
    BIcon,
    BAlert,
    AppTimeline,
  },
  data(){
    return{
      showOverlay: false,
      logs: [],
      url: '',
      log: [
        { 1: 'Registrasi', 2: 'Update' },
        { 1: 'PlusCircleIcon', 2: 'Edit2Icon' },
        { 1: 'success', 2: 'info' },
      ],
      status: [
        { 1: 'Stock', 2: 'User', 3: 'Properti', 4: 'Perawatan' },
        { 1: 'secondary', 2: 'info', 3: 'info', 4: 'warning' }
      ],
      nextPage: '',
      prevPage: '',
      dataFrom: '',
      dataTo: '',
      dataTotal: '',
      per_page : 5,
      current_page : 1,
    }
  },
  methods: {
    //
  },
  mounted(){
    //
  },
  computed: {
    comlogs(){
      const sortlogs = this.detailLog.sort((a,b) => a.id < b.id ? 1 : -1 )
      let totaldata, chunkeddata, localCount;
      totaldata = sortlogs.length;
      chunkeddata = sortlogs.slice(((this.current_page - 1) * this.per_page), (this.per_page * this.current_page));
      localCount =  sortlogs ? chunkeddata.length : 0;
      return {
        localCount,
        current_page: this.current_page,
        data: chunkeddata,
        from: this.per_page * (this.current_page -1) + (localCount ? 1 : 0),
        per_page: this.per_page,
        to: this.per_page * (this.current_page -1) + localCount,
        total: totaldata
      }
    },
    isloggedin(){
      let statustoken = localStorage.getItem('token')
      let statususerdata = localStorage.getItem('userdata')
      if(statustoken && statususerdata){
        return true
      }
      return false
    }
  }
}
</script>

<style lang="scss" scoped>
  @import '~@core/scss/base/bootstrap-extended/include';
  @import '~@core/scss/base/components/include';
  @import '~@core/scss/base/core/colors/palette-variables.scss';
  $timeline-border-color: $border-color;
  @each $color_name, $color in $colors {
    @each $color_type, $color_value in $color {
      @if $color_type== 'base' {
        .timeline-variant-#{$color_name} {

          &.timeline-item-fill-border-#{$color_name} {
            border-color: $color_value !important;
            &:last-of-type {
              &:after { background: linear-gradient($color_value, $white); }
            }
          }
          
          .timeline-item-point {
            background-color: $color_value;
            &:before { background-color: rgba($color_value, 0.12); }
          }

          .timeline-item-icon { color: $color_value; border: 1px solid $color_value; }
        }
      }
    }
  }
  .timeline-item {
    padding-left: 2.5rem;
    position: relative;
    &:not(:last-of-type) {
      padding-bottom: 2rem;
      border-left: 1px solid $timeline-border-color;
    }

    // This gives shade to last timeline-item but isn't that revolutionary
    &:last-of-type {
      &:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 1px;
        height: 100%;
        background: linear-gradient($timeline-border-color, $white);
      }
    }

    .timeline-item-point {
      position: absolute;
      left: -6px;
      width: 12px;
      height: 12px;
      top: 0;
      border-radius: 50%;
      z-index: 1;

      &:before {
        content: '';
        z-index: 1;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        position: absolute;
        top: -4px;
        left: -4px;
        bottom: 0;
        right: 0;
      }
    }

    .timeline-item-icon {
      position: absolute;
      left: -12px;
      top: 0;
      width: 24px;
      height: 24px;
      background-color: $white;
      z-index: 1;
    }
  }
  .dark-layout {
    .timeline-item {
      &:last-of-type {
        &:after {
          background: linear-gradient($theme-dark-border-color, $theme-dark-card-bg);
        }
      }
      &:not(:last-of-type) {
        border-left-color: $theme-dark-border-color;
      }

      .timeline-item-icon {
        background-color: $theme-dark-card-bg;
      }
    }
  }
</style>