<template>
  <div>
    <table-schedule
      :items="items"
      :fields="fields"
      :meta="meta"
      :isbusy="busy"
      @per_page="handlePerPage"
      @pagination="handlePagination"
      @search="handleSearch"
      @sort="handleSort"
    >
      <template #table-busy>
        <div class="text-center my-2">
          <span class="align-middle mr-1">⏳</span>
          <strong>Proses...</strong>
        </div>
      </template>
      <template #empty>
        <h5 class="text-center text-secondary my-2">😔 Tidak ada data.</h5>
      </template>
      <template v-slot:addbutton>
        <v-select
          @input="handleTiming"
          v-model="timingFilter"
          :clearable="false"
          :options="timing"
          :reduce="(text) => text.value"
          label="text"
          class="w-50"
        />
        <b-button
          v-if="$can('create', 'AppOKMSchedule')"
          :to="{ name: 'apps-elearning-schedule-create' }"
          type="button"
          variant="flat-primary"
          class="btn-icon rounded-circle"
        >
          <feather-icon icon="PlusCircleIcon" size="20" />
        </b-button>
      </template>
      <template #cell(name)="data">
        {{ data.item.title }}
        <br /><small>{{ data.item.note }}</small>
      </template>
      <template #cell(tipe)="data">
        <b-badge
          class="badge-glow"
          pill
          :variant="data.item.type === 1 ? 'success' : 'warning'"
        >
          {{ data.item.type === 1 ? "Ujian" : "Remedial" }}
        </b-badge>
      </template>
      <template #cell(date)="data">
        <strong>
          {{ $moment(data.item.startdate_exam).format("DD MMM YYYY HH:mm") }}
        </strong>
        <br />
        <em>s/d</em> <br />
        <strong>
          {{ $moment(data.item.enddate_exam).format("DD MMM YYYY HH:mm") }}
        </strong>
      </template>
      <template #cell(duration)="data">
        {{ data.item.duration }} Menit
      </template>
      <template #cell(opt)="data">
        <b-button-group>
          <b-button
            v-if="$can('read', 'AppOKMSchedule')"
            :to="{
              name: 'apps-elearning-schedule-detail',
              params: { id: data.item.id },
            }"
            class="btn-icon"
            size="sm"
            type="button"
            variant="flat-primary"
          >
            <feather-icon icon="InfoIcon" size="14" />
          </b-button>
          <b-button
            :disabled="data.item.participantscount > 0"
            v-if="
              data.item.participantscount <= 0 ||
              $can('update', 'AppOKMSchedule')
            "
            @click="
              setactive({ isactive: data.item.isactive, id: data.item.id })
            "
            class="btn-icon"
            size="sm"
            type="button"
            variant="flat-danger"
          >
            <feather-icon
              icon="PowerIcon"
              size="14"
              :class="data.item.isactive === 1 ? 'text-danger' : 'text-success'"
            />
          </b-button>
          <b-button
            @click="deleteSchedule(data.item.id)"
            class="btn-icon"
            size="sm"
            type="button"
            variant="flat-danger"
          >
            <feather-icon icon="Trash2Icon" size="14" />
          </b-button>
        </b-button-group>
      </template>
    </table-schedule>
  </div>
</template>

<script>
import TableSchedule from "@/component/utils/Datatable.vue";
import { BButton, BButtonGroup, BBadge } from "bootstrap-vue";
import http from "@/customs/axios";
import vSelect from "vue-select";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    TableSchedule,
    BButton,
    BButtonGroup,
    BBadge,
    vSelect,
  },
  data() {
    return {
      items: [],
      meta: {},
      current_page: 1,
      per_page: 10,
      search: "",
      sortBy: "id",
      sortByDesc: true,
      busy: false,
      fields: [
        {
          key: "name",
          label: "Nama Ujian",
          thClass: "text-left",
          thStyle: { width: "30%" },
        },
        {
          key: "tipe",
          label: "Jenis",
          thClass: "text-center",
          thStyle: { width: "10%" },
          tdClass: "text-center",
        },
        {
          key: "date",
          label: "Waktu",
          thClass: "text-center",
          tdClass: "text-center",
        },
        {
          key: "duration",
          label: "Durasi",
          thClass: "text-center",
          tdClass: "text-center",
        },
        {
          key: "opt",
          label: "Opsi",
          thClass: "text-center",
          tdClass: "text-center",
        },
      ],
      timing: [
        // { value: 'available', text: 'Tersedia' },
        { value: "all", text: "Semua" },
        { value: "now", text: "Berlangsung" },
        { value: "upcoming", text: "Akan Datang" },
        { value: "done", text: "Selesai" },
      ],
      timingFilter: "now",
    };
  },
  methods: {
    handlePerPage(val) {
      this.per_page = val;
      this.handleFetchData();
    },
    handlePagination(val) {
      this.current_page = val;
      this.handleFetchData();
    },
    handleSearch(val) {
      this.search = val;
      this.handleFetchData();
    },
    handleSort(val) {
      this.sortBy = val.sortBy;
      this.sortByDesc = val.sortByDesc;
      this.handleFetchData();
    },
    handleTiming(val) {
      this.timingFilter = val;
      this.handleFetchData();
    },
    handleFetchData() {
      this.busy = true;
      http
        .get("okm/schedule/data", {
          params: {
            page: this.current_page,
            per_page: this.per_page,
            q: this.search,
            sortby: this.sortBy,
            sortbydesc: this.sortByDesc ? "DESC" : "ASC",
            category: this.timingFilter,
            frompage: "elearningschedule",
          },
        })
        .then((res) => {
          let receivedData = res.data.message;
          this.items = receivedData.data;
          this.meta = {
            total: receivedData.total,
            current_page: receivedData.current_page,
            per_page: receivedData.per_page,
            from: receivedData.from,
            to: receivedData.to,
          };
          this.busy = false;
        })
        .catch((e) => {
          console.error(e);
        });
    },
    setactive(options) {
      called.$emit("showloading", { show: true, text: "Sedang memproses..." });
      let newactive = options.isactive === 1 ? 0 : 1;
      http
        .post("okm/schedule/setactive/" + options.id, { newactive: newactive })
        .then((res) => {
          this.handleFetchData();
          this.$toast(
            {
              component: ToastificationContent,
              props: {
                icon: "CheckCircleIcon",
                variant: "success",
                title: res.data.message,
              },
            },
            { position: "top-right" }
          );
          called.$emit("hideloading");
        })
        .catch((e) => {
          console.error(e);
        });
    },
    deleteSchedule(dataID) {
      this.$swal({
        title: "<h4><strong>Jadwal ini akan di HAPUS.</strong></h4>",
        text: "",
        html:
          "Ini akan menghapus seluruh data ujian meliputi:" +
          '<div class="text-left"><ul>' +
          "<li>Detail Ujian</li>" +
          "<li>Peserta Ujian</li>" +
          "<li>Hasil Peserta Ujian</li>" +
          "<li>Data di Raport</li>" +
          "</ul></div>" +
          "Apakah yakin untuk melanjutkan?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-outline-primary ml-1",
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.value) {
          called.$emit("showloading", { show: true, text: "Sedang proses..." });
          http
            .delete("okm/schedule/" + dataID)
            .then((res) => {
              // console.log(res)
              this.$toast(
                {
                  component: ToastificationContent,
                  props: {
                    icon: "AlertTriangleIcon",
                    variant: "warning",
                    title: "Data berhasil dihapus!",
                  },
                },
                { position: "top-right" }
              );
              this.handleFetchData();
              called.$emit("hideloading");
            })
            .catch((e) => {
              console.error(e);
              this.$toast(
                {
                  component: ToastificationContent,
                  props: {
                    icon: "ActivityIcon",
                    variant: "danger",
                    title: "Error !",
                  },
                },
                { position: "top-right" }
              );
              called.$emit("hideloading");
            });
        }
      });
    },
  },
  mounted() {
    this.handleFetchData();
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
