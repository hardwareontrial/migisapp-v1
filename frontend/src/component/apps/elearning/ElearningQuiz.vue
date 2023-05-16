<template>
  <div>
    <!-- Materi -->

    <!-- {{ filteredUser[0].isdone }} -->
    <b-row v-if="stage.intro">
      <b-col cols="12">
        <app-collapse type="margin" accordion>
          <app-collapse-item title="Materi" v-if="filteredUser[0].isdone !== 3">
            <div v-if="dataexam.materials">
              <material-reader :file="dataexam.materials" />
            </div>
            <div v-else>Tidak Ada Materi.</div>
          </app-collapse-item>
        </app-collapse>
      </b-col>
    </b-row>

    <!-- Quiz -->
    <div v-for="(participantexam, i) in filteredUser" :key="i">
      <!-- intro Stage -->
      <div class="text-center mt-1" v-if="stage.intro">
        <span v-if="participantexam.isdone === 1" class="text-success">
          <b-icon icon="check-circle-fill" class="mr-50" scale="0.8"></b-icon>
          Selesai
        </span>
        <b-link
          v-else
          @click="
            participantexam.isdone === 2
              ? setStartQuiz(participantexam)
              : setContinueQuiz(participantexam)
          "
          class="card-link"
        >
          <b-icon icon="box-arrow-up-right" class="mr-50" scale="0.8"></b-icon>
          <span class="align-middle"
            >{{
              participantexam.isdone === 2 ? "Mulai" : "Lanjutkan"
            }}
            Quiz</span
          >
        </b-link>
      </div>

      <!-- quiz Stage -->
      <div class="d-flex justify-content-center" v-if="stage.quiz">
        <b-card style="width: 75%">
          <b-card-text>
            <div class="d-flex justify-content-between">
              <div>
                <timer
                  :time-minute="quiz.timer"
                  v-bind:timeleft.sync="timeleft"
                  @timeisup="submitQuiz()"
                />
              </div>
              <div>
                <label class="text-muted" for="pagelist">Halaman</label>
                <v-select
                  @input="changePage"
                  v-model="quiz.currentQuestion"
                  :options="pagelist"
                  label="text"
                  :reduce="(text) => text.value"
                  :clearable="false"
                  id="pagelist"
                  class="per-page-selector d-inline-block mx-50"
                />
              </div>
            </div>
          </b-card-text>
          <b-card-title>
            <b-row>
              <b-col
                class="mb-50 text-center"
                cols="12"
                v-if="quiz.userQstList[quiz.currentQuestion].question.image"
              >
                <b-img
                  :src="quiz.userQstList[quiz.currentQuestion].question.image"
                  v-bind="imgQstProps"
                />
              </b-col>
              <b-col cols="12" class="text-left">
                <h3 class="text-bold">
                  {{ quiz.userQstList[quiz.currentQuestion].question.text }}
                </h3>
              </b-col>
            </b-row>
          </b-card-title>
          <b-card-text>
            <b-form-radio-group
              v-model="quiz.choosenOpt[quiz.currentQuestion]"
              id="rd-ans-opt"
            >
              <b-row>
                <b-col
                  cols="6"
                  v-for="(ao, iao) in quiz.userQstList[quiz.currentQuestion]
                    .answer_options"
                  :key="iao"
                >
                  <b-form-radio
                    @change="
                      choosen({
                        index: quiz.currentQuestion,
                        data: {
                          id: quiz.userQstList[quiz.currentQuestion].id,
                          value: ao.value,
                        },
                      })
                    "
                    :value="{
                      id: quiz.userQstList[quiz.currentQuestion].id,
                      value: ao.value,
                    }"
                    class="m-1"
                  >
                    <div>
                      <b-row v-if="ao.image" class="mb-50">
                        <b-col cols="12">
                          <b-img :src="ao.image" v-bind="imgAnsProps" />
                        </b-col>
                      </b-row>
                      <b-row>
                        <b-col cols="12">
                          <span>{{ ao.text }}</span>
                        </b-col>
                      </b-row>
                    </div>
                  </b-form-radio>
                </b-col>
              </b-row>
            </b-form-radio-group>
          </b-card-text>
          <template #footer>
            <b-row class="text-center">
              <b-col cols="4">
                <b-link
                  @click="prevBtn(quiz.currentQuestion)"
                  v-show="quiz.currentQuestion !== 0"
                  class="card-link"
                >
                  <feather-icon icon="ChevronsLeftIcon" class="mr-25" />
                  Sebelumnya
                </b-link>
              </b-col>
              <b-col cols="4"> </b-col>
              <b-col cols="4">
                <b-link
                  v-if="quiz.currentQuestion !== quiz.userQstList.length - 1"
                  @click="nextBtn(quiz.currentQuestion)"
                  class="card-link"
                >
                  Lanjut <feather-icon icon="ChevronsRightIcon" class="ml-25" />
                </b-link>
                <b-link v-else @click="submitQuiz()" class="card-link">
                  Proses <feather-icon icon="SendIcon" class="ml-25" />
                </b-link>
              </b-col>
            </b-row>
          </template>
        </b-card>
      </div>

      <!-- result Stage -->
      <div class="d-flex justify-content-center" v-if="stage.result">
        <b-card style="width: 50%" class="text-center">
          <b-card-text>
            <b-row>
              <b-col cols="12" class="mb-5 mt-1">
                <span class="h4 text-muted">{{ result.title }}</span>
              </b-col>
              <b-col cols="12" class="mb-4 mt-3">
                <b-icon
                  :icon="result.icon.name"
                  :variant="result.icon.variant"
                  scale="10"
                />
              </b-col>
              <b-col cols="12" class="mb-1 mt-3">
                <p :class="`h4 mb-1 ${result.score.variant}`">
                  <strong>{{ result.message }}</strong>
                </p>
                <p :class="`h1 ${result.score.variant}`">
                  <strong>{{ result.score.value }}</strong>
                </p>
              </b-col>
              <b-col cols="12" class="mb-0 mt-1">
                <b-button variant="flat-primary" :to="{ name: 'dashboard' }">
                  Tutup
                </b-button>
              </b-col>
            </b-row>
          </b-card-text>
        </b-card>
      </div>
    </div>
  </div>
</template>

<script>
import http from "@/customs/axios";
import {
  BRow,
  BCol,
  BCard,
  BCardText,
  BCardHeader,
  BCardFooter,
  BCardTitle,
  BCardBody,
  BButtonGroup,
  BButton,
  BIcon,
  BContainer,
  BFormRadioGroup,
  BFormGroup,
  BFormRadio,
  BImg,
  BLink,
} from "bootstrap-vue";
import MaterialReader from "./_MaterialReader.vue";
import FeatherIcon from "@/@core/components/feather-icon/FeatherIcon.vue";
import vSelect from "vue-select";
import Timer from "./_Countdown.vue";
import AppCollapse from "@core/components/app-collapse/AppCollapse.vue";
import AppCollapseItem from "@core/components/app-collapse/AppCollapseItem.vue";

export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardText,
    BCardHeader,
    BCardFooter,
    BCardTitle,
    BCardBody,
    BButtonGroup,
    BButton,
    BIcon,
    BContainer,
    BFormRadioGroup,
    BFormGroup,
    BFormRadio,
    BImg,
    BLink,
    MaterialReader,
    FeatherIcon,
    vSelect,
    Timer,
    AppCollapse,
    AppCollapseItem,
  },
  data() {
    return {
      usernik: null,
      isadmin: null,
      dataexam: {
        title: null,
        type: null,
        period: { start: null, end: null },
        qstcount: null,
        duration: null,
        scoremin: null,
        materials: null,
        participants: null,
      },
      breadcrumbs: [],
      stage: { intro: false, quiz: false, result: false },
      quiz: {
        idPartcpt: null,
        userQstList: [],
        currentQuestion: 0,
        choosenOpt: null,
        score: null,
        done: null,
        passed: null,
        timer: 0,
        dtUserStart: null,
        dtUserEnd: null,
      },
      result: {
        title: "",
        message: "",
        icon: { name: null, variant: "info" },
        score: { value: 0, variant: "text-danger" },
      },
      timeleft: 0,
      imgQstProps: {
        width: 768,
        height: 480,
        class: "m1",
      },
      imgAnsProps: {
        width: 200,
        height: 125,
        class: "m1",
      },
      menuHidden: this.$store.state.appConfig.layout.menu.hidden,
      layoutType: this.$store.state.appConfig.layout.type,
    };
  },
  methods: {
    fetchData() {
      called.$emit("showloading", { show: true, text: "Mengambil data..." });
      http
        .get("okm/schedule/detail/" + this.$route.params.id)
        .then((res) => {
          this.dataexam = {
            title: res.data.title,
            type: res.data.type,
            period: {
              start: res.data.startdate_exam,
              end: res.data.enddate_exam,
            },
            qstcount: res.data.dataquestion.qstcount,
            duration: res.data.duration,
            scoremin: res.data.nilai_min,
            participants: res.data.participants_exam,
          };
          if (res.data.dataquestion.material.materialfile !== null) {
            this.dataexam.materials =
              res.data.dataquestion.material.materialfile.filematerial;
          }
          this.setBreadcrumb(res.data.title);
          this.$store.commit("appConfig/UPDATE_NAV_MENU_HIDDEN", true);
          this.stage = { intro: true, quiz: false, result: false };
          called.$emit("hideloading");
        })
        .catch((e) => {
          console.error(e);
        });
    },
    setBreadcrumb(title) {
      this.breadcrumbs = [{ text: "QUIZ " + title, active: true }];
      called.$emit("appbreadcrumbcustomBreadCrumbs", this.breadcrumbs);
    },
    setStartQuiz(val) {
      var dtnow = new Date().getTime();
      const timer = this.dataexam.duration * 60;
      this.quiz = {
        idPartcpt: val.id,
        userQstList: val.qstpattern,
        currentQuestion: 0,
        choosenOpt: [],
        score: val.score,
        done: 3,
        passed: val.ispassed,
        timer: timer,
        dtUserStart: this.$moment(dtnow).format("YYYY-MM-DD HH:mm:ss"),
        dtUserEnd: "",
      };
      this.stage = { intro: false, quiz: true, result: false };
      this.timeleft = this.timeleft;
      this.updateUserExam();
    },
    setContinueQuiz(val) {
      this.quiz = {
        idPartcpt: val.id,
        userQstList: val.qstpattern,
        currentQuestion:
          val.answers_user.length > 0 ? val.answers_user.length - 1 : 0,
        score: val.score,
        done: val.isdone,
        passed: val.ispassed,
        timer: val.timeleft_seconds,
        dtUserStart: val.user_start_exam,
        dtUserEnd: "",
      };
      if (val.answers_user.length > 0) {
        this.quiz.choosenOpt = val.answers_user.map((d) => {
          return { id: d.id, value: d.value };
        });
      } else {
        this.quiz.choosenOpt = val.answers_user;
      }
      this.stage = { intro: false, quiz: true, result: false };
    },
    updateUserExam() {
      // console.log(this.quiz)
      let dataUpdate = new FormData();
      dataUpdate.append("answersuser", JSON.stringify(this.quiz.choosenOpt));
      dataUpdate.append("userstartexam", this.quiz.dtUserStart);
      dataUpdate.append("userendexam", this.quiz.dtUserEnd);
      dataUpdate.append("timeleft", this.quiz.timer);
      dataUpdate.append("isdone", this.quiz.done);
      dataUpdate.append("ispassed", this.quiz.passed);
      dataUpdate.append("score", this.quiz.score);
      http
        .post("okm/schedule/participant/" + this.quiz.idPartcpt, dataUpdate)
        .then((res) => {
          console.log("success");
        })
        .catch((e) => {
          console.error(e);
        });
    },
    submitQuiz() {
      var dtnow = new Date().getTime();
      this.quiz.dtUserEnd = this.$moment(dtnow).format("YYYY-MM-DD HH:mm:ss");
      this.quiz.done = 1;
      this.quiz.passed = this.quiz.score >= this.dataexam.scoremin ? 1 : 2;
      // console.log(this.quiz)
      this.updateUserExam();
      this.stage = { intro: false, quiz: false, result: true };
    },
    changePage() {
      this.quiz.timer = this.timeleft;
      this.quiz.done = 3;
    },
    prevBtn(index) {
      this.quiz.currentQuestion = index - 1;
    },
    nextBtn(index) {
      this.quiz.timer = this.timeleft;
      this.quiz.done = 3;
      this.quiz.currentQuestion = index + 1;
      this.updateUserExam();
    },
    async choosen(values) {
      let t1 = await this.quiz.choosenOpt.splice(values.index, 1, values.data);
      if (t1) {
        var ppq = 100 / this.quiz.userQstList.length;
        var x = this.quiz.userQstList.filter((el) => {
          return this.quiz.choosenOpt.some((d) => {
            return d.id === el.id && d.value === el.answer_key;
          });
        });
        this.quiz.score = Math.round(ppq * x.length);
      }
    },
  },
  watch: {
    "quiz.score": {
      handler(newValue) {
        this.result = {
          message:
            newValue >= this.dataexam.scoremin
              ? "Selamat Anda LULUS!"
              : "Anda TIDAK LULUS.",
          icon: {
            name:
              newValue >= this.dataexam.scoremin ? "patch-check" : "x-circle",
            variant: newValue >= this.dataexam.scoremin ? "success" : "danger",
          },
          score: {
            value: newValue,
            variant:
              newValue >= this.dataexam.scoremin
                ? "text-success"
                : "text-danger",
          },
          title: this.timeleft <= 0 ? "Waktu Habis" : "Selesai",
        };
      },
    },
  },
  computed: {
    filteredUser() {
      let partcptExams = this.dataexam.participants;
      if (partcptExams != null) {
        if (this.isadmin === 0) {
          return partcptExams.filter(
            (item) => this.usernik === parseInt(item.user_nik)
          );
        } else {
          return partcptExams;
        }
      }
    },
    pagelist() {
      if (this.quiz.userQstList.length > 0) {
        let tmplist = [];
        this.quiz.userQstList.forEach((data, i) => {
          tmplist.push({ value: i, text: i + 1 });
        });
        return tmplist;
      }
      return [{ value: 0, text: 1 }];
    },
  },
  mounted() {
    this.fetchData();
  },
  beforeCreate() {
    this.$store.commit("appConfig/UPDATE_LAYOUT_TYPE", "horizontal");
  },
  created() {
    var user = JSON.parse(localStorage.getItem("userdata"));
    this.isadmin = user.admin;
    this.usernik = user.nik;
  },
  beforeDestroy() {
    if (this.quiz.done === 3) {
      this.quiz.timer = this.timeleft;
      this.updateUserExam();
    }
    this.$store.commit("appConfig/UPDATE_NAV_MENU_HIDDEN", false);
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@keyframes fadeInOpacity {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
.fade-in-opacity {
  opacity: 1;
  animation-name: fadeInOpacity;
  animation-iteration-count: 1;
  animation-timing-function: ease-in;
  animation-duration: 2s;
}
</style>
