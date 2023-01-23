<template>
  <div>
    <b-row>
      <b-col cols="12">
        <b-form-group
          label="Soal"
          label-for="question-title"
          label-cols-md="3">
          <b-form-textarea
            class="my-25"
            v-model="form.question.text"
            id="question-title"
            placeholder="Soal"/>
          <b-form-file
            @input="addImageQuestion()"
            ref="questionimage"
            accept=".jpg, .png,"
            placeholder="Pilih..."
            type="file"/>
          <div
            class="my-25"
            v-if="form.question.image !== null">
            <div
              class="imagePreview"
              :style="{ 'background-image': `url(${preview.ImgQst})`}">
            </div>
            <b-link
              class="mx-50"
              @click="removeImageQuestion()">
              <small class="text-danger">Hapus</small>
            </b-link>
          </div>
        </b-form-group>
      </b-col>
      <b-col cols="12" v-for="(a, i) in form.answer_options" :key="i">
        <b-form-group
          :label="i === 0 ? 'Pilihan Jawaban': ''"
          :label-for="`${'question-answer-'}` + `${i+1}`"
          label-cols-md="3">
          <div :id="`${'div'}` + `${i}`">
            <b-row>
              <b-col cols="12">
                <b-form-input
                  v-model="a.text"
                  class="my-25"
                  :id="`${'question-answer-'}` + `${i+1}`"
                  :placeholder="`${'Pilihan '}` + `${i+1}`"/>
              </b-col>
              <b-col cols="6">
                <b-form-file
                  @input="addImgAnsOpt(i)"
                  ref="questionimgansopts"
                  accept=".jpg, .png,"
                  placeholder="Pilih..."
                  type="file"/>
                <div
                  class="my-25"
                  v-if="a.image !== null">
                  <div
                    class="imagePreview"
                    :style="{ 'background-image': `url(${preview.AnsOpt[i]})`}">
                  </div>
                  <b-link
                    @click="removeImgAnsOpt(i)"
                    class="mx-50">
                    <small class="text-danger">Hapus</small>
                  </b-link>
                </div>
              </b-col>
              <b-col cols="6">
                <div>
                  <b-button
                    v-show="i === form.answer_options.length-1 && form.answer_options.length < 4"
                    @click="addAnswerOption(i)"
                    class="btn-icon rounded-circle"
                    variant="flat-primary"
                    size="sm">
                    <feather-icon icon="PlusCircleIcon"/>
                  </b-button>
                  <b-button
                    v-show="form.answer_options.length !== 1"
                    @click="removeAnswerOption(i)"
                    class="btn-icon rounded-circle"
                    variant="flat-danger"
                    size="sm">
                    <feather-icon icon="XCircleIcon"/>
                  </b-button>
                </div>
              </b-col>
            </b-row>
          </div>
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group
          label="Kunci Jawaban"
          label-for="answer-key"
          label-cols-md="3">
          <v-select
            v-model="form.answer_key"
            :options="answerkeyOptions"
            :reduce="title=>title.value"
            :clearable="false"
            label="title"
            id="answer-key"
            placeholder="Pilih Jawaban">
          </v-select>
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group
          label=""
          label-for="question-answer-4"
          label-cols-md="3">
          <b-button
            :disabled="disableprocess"
            @click="editmode ? updateqsttbl() : addquestion()"
            size="sm"
            type="button"
            class="mr-1"
            :variant="disableprocess ? 'outline-secondary' : 'outline-primary'">
            Proses
          </b-button>
          <b-button
            @click="cancelform()"
            size="sm"
            type="button"
            variant="danger">
            Batal
          </b-button>
        </b-form-group>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import {
  BCardText,
  BFormGroup, BFormInput, BFormTextarea, BFormFile,
  BRow, BCol,
  BButton,
  BImg,
  BLink,
} from 'bootstrap-vue'
import vSelect from 'vue-select'

export default {
  props: {
    editmode: {
      type: Boolean,
      default: false,
    },
    dataqst: {
      type: Object,
      default: null,
    },
  },
  components: {
    BCardText,
    BFormGroup, BFormInput, BFormTextarea, BFormFile,
    BRow, BCol,
    BButton,
    BImg,
    BLink,
    vSelect,
  },
  data(){
    return{
      dataindex: null,
      dataid: null,
      form: {
        question: { image: null, text: '' },
        answer_options: [{ image: null, text: ''}],
        answer_key: null,
      },
      answerkeyOptions: [{ title: 'Pilihan 1', value : 1 }],
      preview: {
        ImgQst: null,
        AnsOpt: []
      },
    }
  },
  methods: {
    addAnswerOption(index){
      const xnum = index +2
      this.form.answer_options.push({ image: null, text: ''})
      this.answerkeyOptions.push({ title: 'Pilihan '+xnum, value : xnum })
    },
    removeAnswerOption(index){
      this.form.answer_options.splice(index, 1)
      this.answerkeyOptions.pop()
    },
    addImageQuestion(){
      this.form.question.image = null
      let input = this.$refs.questionimage.files[0]
      const reader = new FileReader()
      if(input){
        reader.readAsDataURL(input)
      }
      let rawQuestionImage;
      reader.onload = e => {
        rawQuestionImage = e.target.result
        this.preview.ImgQst = e.target.result
        this.form.question.image = rawQuestionImage
      }
    },
    removeImageQuestion(){
      this.preview.ImgQst = null
      this.form.question.image = null
      this.$refs.questionimage.reset()
    },
    addImgAnsOpt(index){
      this.form.answer_options[index].image = null
      let input = this.$refs.questionimgansopts[index].files[0]
      const reader = new FileReader()
      if(input){
        reader.readAsDataURL(input)
      }
      let rawImgOptAns;
      reader.onload = e => {
        rawImgOptAns = e.target.result
        this.preview.AnsOpt[index] = e.target.result
        this.form.answer_options[index].image = rawImgOptAns
      }
    },
    removeImgAnsOpt(index){
      this.preview.AnsOpt[index] = null
      this.form.answer_options[index].image = null
      this.$refs.questionimgansopts[index].reset()
    },
    addquestion(){
      this.$emit('addquestion', { data: this.form, id: this.dataid, dataindex: this.dataindex })
    },
    resetform(){
      this.form = {
        question: { image: null, text: '' },
        answer_options: [{ image: null, text: '' }],
        answer_key: null,
      }
      this.answerkeyOptions = [{ title: 'Pilihan 1', value : 1 }]
      this.preview = {
        ImgQst: null,
        AnsOpt: []
      }
    },
    cancelform(){
      this.resetform()
      this.$emit('update: editmode', false)
      this.$emit('cancelform')
    },
    updateqsttbl(){
      this.$emit('updateform', { data: this.form, id: this.dataid, dataindex: this.dataindex })
    },
    testmethod(){
      this.dataindex = this.dataqst.index
      this.dataid = this.dataqst.data.id ? this.dataqst.data.id : null
      var dataqst = this.dataqst.data
      this.form = { question: { image: dataqst.question.image, text: dataqst.question.text }, answer_key: dataqst.answer_key }
      this.preview.ImgQst = dataqst.question.image
      this.answerkeyOptions = []
      let ansopt = dataqst.answer_options
      this.form.answer_options = ansopt
      ansopt.forEach((d,i) => {
        let ansoptkey = { title: 'Pilihan '+ `${i+1}`, value: i+1 }
        this.answerkeyOptions.push(ansoptkey)
        d.image ? this.preview.AnsOpt[i] = d.image : null
      })
      if(this.dataid){
        this.form.questions_id = dataqst.questions_id
      }
    }
  },
  computed: {
    disableprocess(){
      if(this.form.question.image || this.form.question.text && 
        this.form.answer_options.length > 1 && this.form.answer_key !== null){
        if(this.form.answer_options.length >= 1){
          let result = []
          this.form.answer_options.forEach((item) => {
            (item.text !== '' || item.image !== null) ? result.push(true) : result.push(false)
          })
          let check = result.every(x => x === true)
          if(check === false){
            return true 
          }
          return false
        }
      }
      return true
    }
  },
  created(){
    if(this.editmode){
      this.testmethod()
    }
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
  .imagePreview {
    width: 40px;
    height: 30px;
    display: inline-flex;
    cursor: pointer;
    background-size: cover;
    background-position: center center;
  }
</style>