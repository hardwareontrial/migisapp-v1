<template>
  <div>
    <!-- Info Table -->
    <b-table-simple responsive small>
      <b-tbody>
        <b-tr>
          <b-th sticky-column style="width: 25%; font-size: 10pt;"> Nilai</b-th>
          <b-td style="font-size: 10pt;"><strong>{{ UserDataExam.score }}</strong></b-td>
        </b-tr>
        <b-tr>
          <b-th sticky-column style="width: 25%; font-size: 10pt;"> Tgl Ujian</b-th>
          <b-td style="font-size: 10pt;">{{ UserDataExam.user_end_exam ? $moment(UserDataExam.user_end_exam).format('DD MMM YYYY, HH:mm') : '-' }}</b-td>
        </b-tr>
        <b-tr>
          <b-th sticky-column style="width: 25%; font-size: 10pt;"> Status</b-th>
          <b-td style="font-size: 10pt;">
            <b-badge :variant="UserDataExam.ispassed === 1 ? 'success' : 'danger' " v-if="UserDataExam.isdone !== 3">
              <feather-icon
                :icon="UserDataExam.ispassed === 1 ? 'CheckCircleIcon' : 'XCircleIcon' "
                class="mr-25"
              />
              <span>{{UserDataExam.ispassed === 1 ? 'Lulus' : 'Tidak Lulus'}}</span>
            </b-badge>
            <b-badge variant="success" v-else>
              Proses
            </b-badge>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>

    <!-- Result Table -->
    <b-table-simple bordered responsive="md">
      <b-thead>
        <b-tr>
          <b-th style="width: 5%;" class="text-center">No</b-th>
          <b-th style="width: 30%;" class="text-left">Soal</b-th>
          <b-th style="width: 32%;" class="text-center" :class="$can('read', 'AppOKM') ? '':'d-none'">Jawaban Benar</b-th>
          <b-th class="text-center" colspan="2">Jawaban User</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-for="(qst, iqst) in UserDataExam.answers_user" :key="iqst">
          <td class="text-center">{{ iqst +1 }}</td>
          <b-td>
            <div v-if="qst.question.image">
              <b-img
                v-bind="imgprops"
                :src="qst.question.image" /><br>
            </div>
            {{ qst.question.text }}
          </b-td>
          <b-td :class="$can('read', 'AppOKM') ? '':'d-none'">
            <div v-for="(ao, iao) in qst.answer_options" :key="iao">
              <div v-if="ao.value === qst.answer_key">
                <div v-if="ao.image">
                  <b-img
                    v-bind="imgprops"
                    :src="ao.image" /><br>
                </div>
                {{ ao.text }}
              </div>
            </div>
          </b-td>
          <b-td>
            <div v-for="(ao, iao) in qst.answer_options" :key="iao">
              <div v-if="ao.value === qst.value">
                <div v-if="ao.image">
                  <b-img
                    v-bind="imgprops"
                    :src="ao.image" /><br>
                </div>
                {{ ao.text }}
              </div>
            </div>
          </b-td>
          <b-td style="width: 5%;" class="text-center">
            <b-icon
              :icon="qst.answer_key === qst.value ? 'check-circle-fill' : 'x-circle-fill'"
              :class="qst.answer_key === qst.value ? 'text-success' : 'text-danger'"/>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </div>
</template>

<script>
import {
  BTableSimple, BTr, BTh, BThead, BTd, BTbody, BImg, BBadge,
} from 'bootstrap-vue';

export default {
  components: {
    BTableSimple, BTr, BTh, BThead, BTd, BTbody, BImg, BBadge,
  },
  props: {
    UserDataExam: { type: Object },
  },
  data(){
    return{
      imgprops: { width: 48, height: 36, class: 'm1' },
    }
  },
  methods: {
    //
  }
}
</script>

<style></style>