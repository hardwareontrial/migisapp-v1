<template>
  <div>
    <b-table-simple
      style="width: 100%;"
      class="table-bordered">
      <b-thead>
        <b-tr>
          <b-th style="width: 5%;">No</b-th>
          <b-th class="text-center" style="width: 34%;">Soal</b-th>
          <b-th colspan="2" class="text-center" style="width: 50%;">Jawaban</b-th>
          <b-th class="text-center" style="width: 11%;">Opsi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-for="(mq, j) in data">
          <b-tr v-for="(item, isub) in mq.answer_options" :key="isub">
            <b-td 
              v-if="isub === 0" 
              :rowspan="mq.answer_options.length">
              {{ j +1 }}
            </b-td>
            <b-td 
              v-if="isub === 0" 
              :rowspan="mq.answer_options.length">
              <div v-if="mq.question.image !== null">
                <b-img
                  v-bind="imgprops"
                  :src="mq.question.image" /><br>
                <span>{{ mq.question.text }}</span>
              </div>
              <div v-else>
                {{ mq.question.text }}
              </div>
            </b-td>
            <b-td
              :class="mq.answer_key === isub+1 ? 'font-weight-bolder' : ''"
              :variant="mq.answer_key === isub+1 ? 'success' : ''"
              style="width: 11%;">
              Pilihan {{ isub +1}}
            </b-td>
            <b-td
              :class="mq.answer_key === isub+1 ? 'font-weight-bolder' : ''"
              :variant="mq.answer_key === isub+1 ? 'success' : ''">
              <div v-if="item.image !== null">
                <b-img v-bind="imgprops" :src="item.image" /><br>
                <span>{{ item.text }}</span>
              </div>
              <div v-else>
                {{ item.text }}
              </div>
            </b-td>
            <b-td
              class="text-center"
              style="width: 10%;" 
              v-if="isub === 0" 
              :rowspan="mq.answer_options.length">
              <b-button
                @click="editquestion(mq, j)"
                size="sm"
                variant="flat-info"
                class="btn-icon rounded-circle">
                <feather-icon icon="EditIcon" size="16"/>
              </b-button>
              <b-button
                @click="delquestion(mq, j)"
                size="sm"
                variant="flat-danger"
                class="btn-icon rounded-circle">
                <feather-icon icon="XCircleIcon" size="16"/>
              </b-button>
            </b-td>
          </b-tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </div>
</template>

<script>
import {
  BTableSimple, BThead, BTbody, BTh, BTd, BTr,
  BImg,
  BButton,
} from 'bootstrap-vue'

export default {
  components: {
    BTableSimple, BThead, BTbody, BTh, BTd, BTr,
    BImg,
    BButton,
  },
  props: {
    data: {
      type: Array
    },
  },
  data(){
    return{
      imgprops: { width: 96, height: 72, class: 'm1' },
    }
  },
  methods: {
    editquestion(data, j){
      this.$emit('editquestion', { data: data, index: j })
    },
    delquestion(data, j){
      this.$emit('deletequestion', { data: data, index: j })
    },
  }
}
</script>

<style lang="scss">

</style>