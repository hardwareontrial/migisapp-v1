<template>
  <b-card class="card-transaction" no-body>
    <b-card-header>
      <b-card-title>Elearning</b-card-title>
      <!-- <b-link class="text-secondary">
        <feather-icon icon="RefreshCwIcon" size="16" @click="refreshFethData()"/>
      </b-link> -->
    </b-card-header>
    <b-card-body>
      <div v-if="exams_schedules.length > 0">
        <div
          v-for="(exam, i) in exams_schedules"
          :key="i"
          class="transaction-item"
        >
          <b-media no-body>
            <b-media-aside v-if="UserAdmin === 0">
              <b-avatar
                rounded
                size="42"
                :variant="`light-${doneProps[1][exam.participant_exam.isdone]}`"
              >
                <feather-icon
                  size="18"
                  :icon="
                    exam.participant_exam.isdone === 1
                      ? 'CheckIcon'
                      : 'AwardIcon'
                  "
                />
              </b-avatar>
            </b-media-aside>
            <b-media-aside v-else>
              <b-avatar rounded size="42" variant="light-success">
                <feather-icon size="18" icon="AwardIcon" />
              </b-avatar>
            </b-media-aside>
            <b-media-body>
              <h6 class="transaction-title">
                {{ exam.title }}
              </h6>
              <small>
                <feather-icon icon="EditIcon" size="12" />
                {{ exam.type === 1 ? "Ujian" : "Remedial" }} |
                <feather-icon icon="ListIcon" size="12" />
                {{ exam.questions_count }} Soal |
                <feather-icon icon="ClockIcon" size="12" />
                {{ exam.duration }} Menit |
                <feather-icon icon="UserIcon" size="12" />
                {{ exam.creator.name }}
              </small>
            </b-media-body>
          </b-media>
          <b-link
            :to="
              exam.participant_exam.isdone === 1
                ? {
                    name: 'apps-elearning-raport-nik',
                    params: { id: UserId, nik: UserNik },
                  }
                : {
                    name: 'apps-elearning-quiz',
                    params: {
                      id: exam.id,
                      slug: exam.dataquestion.slug,
                      data: exam,
                    },
                  }
            "
            v-if="UserAdmin === 0"
            class="font-weight-bolder card-link"
          >
            <feather-icon
              :icon="doneProps[0][exam.participant_exam.isdone]"
              :class="`text-${doneProps[1][exam.participant_exam.isdone]}`"
              size="20"
            />
          </b-link>
          <!--          {{ exam.participant_exam }}-->
        </div>
      </div>
      <div v-else>
        <p class="text-center">😔 Tidak ada data.</p>
      </div>
    </b-card-body>
    <!-- {{ UserId }} {{ UserNik}} {{ UserAdmin }} -->
  </b-card>
</template>

<script>
import {
  BCard,
  BCardHeader,
  BCardTitle,
  BCardBody,
  BMediaBody,
  BMedia,
  BMediaAside,
  BAvatar,
  BLink,
} from "bootstrap-vue";
import http from "@/customs/axios";

export default {
  props: {
    UserId: { type: Number, required: true },
    UserNik: { type: Number, required: true },
    UserAdmin: { type: Number, required: true },
  },
  components: {
    BCard,
    BCardHeader,
    BCardTitle,
    BCardBody,
    BMediaBody,
    BMedia,
    BMediaAside,
    BAvatar,
    BLink,
  },
  data() {
    return {
      exams_schedules: [],
      doneProps: [
        { 1: "InfoIcon", 2: "PlayCircleIcon", 3: "PauseCircleIcon" },
        { 1: "info", 2: "primary", 3: "warning" },
      ],
    };
  },
  methods: {
    fetchData() {
      http
        .get("okm/schedule/data", {
          params: { frompage: "elearningdashboard" },
        })
        .then((res) => {
          let z = res.data.message;
          if (this.UserAdmin === 0 && this.UserNik < 8000000) {
            let examFitered = z.filter((x) =>
              x.participants_exam.some(
                (y) => parseInt(y.user_nik) === this.UserNik
              )
            );
            const newArray = [];
            if (examFitered.length > 0) {
              for (let test1 of examFitered) {
                const usernik = this.UserNik;
                let filteredUser = test1.participants_exam.filter(function (
                  el
                ) {
                  return usernik === parseInt(el.user_nik);
                });
                test1["participant_exam"] = filteredUser[0];
                newArray.push(test1);
              }
            }
            this.exams_schedules = newArray;
          } else if (this.UserAdmin === 1) {
            this.exams_schedules = z;
          }
          // if(this.exams_schedules.length > 0){ this.alertprops = { show: false, variant: 'primary', message: 'Sedang memuat ...', icon: 'ClockIcon' } }
        })
        .catch((e) => {
          console.error(e);
        });
    },
    refreshFethData() {
      alert("clicked");
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style></style>
