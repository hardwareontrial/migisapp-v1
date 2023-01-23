<template>
  <div>
    
    <material-detail-card>
      <template v-slot:cstmtitledetail>
        <div v-if="editdetail">
          <feather-icon icon="EditIcon" size="18"/>
          <span class="mx-50">Edit</span>
        </div>
        <div v-else>
          <feather-icon icon="InfoIcon" size="18"/>
          <span class="mx-50">INFO</span>
        </div>
      </template>
      <template v-slot:datadetail>
        <div v-if="editdetail">
          <b-row>
            <b-col cols="6">
              <b-form-group
                label="Nilai Minimum"
                label-for="eqd-point">
                <b-form-input
                  v-model="form.exam_point"
                  id="eqd-point"/>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group
                label="Durasi Soal"
                label-for="eqd-duration">
                <b-form-input
                  v-model="form.exam_duration"
                  id="eqd-duration"/>
              </b-form-group>
            </b-col>
          </b-row>
        </div>
        <div v-else>
          <b-row>
            <b-col cols="4">
              <p class="small hint-text m-0"><b>Nama Materi</b></p>
              <p class="font-montserrat bold">
                <feather-icon icon="FileIcon" class="mx-25"/>
                {{ questiondata.material_title }}
              </p>
            </b-col>
            <b-col cols="4">
              <p class="small hint-text m-0"><b>Departemen</b></p>
              <p class="font-montserrat bold">
                <feather-icon icon="ShieldIcon" class="mx-25"/>
                {{ deptname }}
              </p>
            </b-col>
            <b-col cols="4">
              <p class="small hint-text m-0"><b>Level Materi</b></p>
              <p class="font-montserrat bold">
                <feather-icon icon="TrendingUpIcon" class="mx-25"/>
                {{ levelOptions[1][questiondata.level] }}
              </p>
            </b-col>
            <b-col cols="4">
              <p class="small hint-text m-0"><b>Jumlah Soal</b></p>
              <p class="font-montserrat bold">
                <feather-icon icon="ListIcon" class="mx-25"/>
                {{ questiondata.totalqst }} Soal
              </p>
            </b-col>
            <b-col cols="4">
              <p class="small hint-text m-0"><b>Nilai Minimum</b></p>
              <p class="font-montserrat bold">
                <feather-icon icon="PercentIcon" class="mx-25"/>
                {{ questiondata.point }}
              </p>
            </b-col>
            <b-col cols="4">
              <p class="small hint-text m-0"><b>Durasi Soal</b></p>
              <p class="font-montserrat bold">
                <feather-icon icon="ClockIcon" class="mx-25"/>
                {{ questiondata.duration }} Menit
              </p>
            </b-col>
          </b-row>
        </div>
      </template>
      <template v-slot:cstmbtndetail>
        <b-button
          v-if="!editdetail"
          @click="editdetailqst()"
          variant="flat-primary"
          class="btn-icon rounded-circle">
          <feather-icon icon="EditIcon" size="18"/>
        </b-button>
        <div v-else>
          <b-button
            @click="updateqstdetail()"
            variant="flat-primary"
            class="btn-icon rounded-circle">
            <feather-icon icon="SaveIcon" size="18"/>
          </b-button>
          <b-button
            @click="canceleditdtl()"
            variant="flat-danger"
            class="btn-icon rounded-circle">
            <feather-icon icon="XCircleIcon" size="18"/>
          </b-button>
        </div>
        <b-dropdown
          v-show="!editdetail"
          text="Tambah"
          right
          variant="flat-primary">
          <b-dropdown-item @click="showadquestiondb()">Buat Soal</b-dropdown-item>
          <b-dropdown-item @click="showuploadquestiondb()">Upload Soal</b-dropdown-item>
        </b-dropdown>
      </template>
    </material-detail-card>
    
    <b-alert
      :variant="alert.variant"
      :show="alert.show">
      <div class="alert-body">
        <feather-icon
          class="mr-25"
          :icon="alert.icon"/>
        <span class="ml-25">{{ alert.text }}</span>
      </div>
    </b-alert>

    <b-card
      v-if="questiondata.data.length > 0 && !editquestionmodalprop.show"
      no-body>
      <question-table
        :data="questiondata.data"
        @editquestion="editquestiondb"
        @deletequestion="deleteqstcolldb"
        ref="tabledwtailqst"/>
    </b-card>

    <add-quetions-modal
      :size="addqstmodalprop.size"
      :title="addqstmodalprop.title"
      :show="addqstmodalprop.show"
      :hidefooter="addqstmodalprop.hidefooter"
      ref="addqstmodalprop">
      <template v-slot:modalbody>
        <question-form
          @addquestion="addquestiontodb"
          @cancelform="addqstmodalprop.show = !addqstmodalprop.show"
          ref="questionform"/>
      </template>
      <template v-slot:modalfooter>
        <b-button
          variant="outline-primary"
          class="mr-1">
          Proses
        </b-button>
        <b-button
          @click="addqstmodalprop.show = !addqstmodalprop.show"
          variant="danger">
          Batal
        </b-button>
      </template>
    </add-quetions-modal>
    
    <upload-quetions-modal
      :size="uploadqstmodalprop.size"
      :title="uploadqstmodalprop.title"
      :show="uploadqstmodalprop.show"
      :hidefooter="uploadqstmodalprop.hidefooter"
      ref="uploadqstmodalprop">
      <template v-slot:modalbody>
        <qst-file-upload
          @sendfile="handlefile" 
          @deletefile="handledelfile" 
          :acceptext="qstfileuploadprop.acceptext" />
        <ul>
          <li v-if="datafile.detail && datafile.detail.size > 8388608"><small class="text-danger">File terlalu besar</small></li>
          <li v-if="datafile.detail && datafile.detail.type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'"><small class="text-danger">Tipe file bukan XLSX</small></li>
        </ul>
      </template>
      <template v-slot:modalfooter>
        <b-button
          size="sm"
          @click="handleUploadFile()"
          :disabled="disableduploadbtn"
          :variant="disableduploadbtn ? 'secondary' : 'outline-primary'"
          class="mr-1">
          Upload
        </b-button>
        <b-button
          size="sm"
          @click="(uploadqstmodalprop.show = !uploadqstmodalprop.show, datafile.detail = null, datafile.exam_add_upload = null)"
          variant="danger">
          Batal
        </b-button>
      </template>
    </upload-quetions-modal>

    <edit-quetions-modal
      :size="editquestionmodalprop.size"
      title="Edit Soal"
      :show="editquestionmodalprop.show"
      :hidefooter="editquestionmodalprop.hidefooter"
      ref="editquestionmodalprop">
        <template v-slot:modalbody>
          <question-form
            :dataqst="editquestionmodalprop.dataqst"
            :editmode.sync="editquestionmodalprop.edit"
            @cancelform="canceleditquestion"
            @updateform="updatequestiondb"
            ref="editquestionform"/>
        </template>
        <template v-slot:modalfooter>
          <b-button
            variant="outline-primary"
            class="mr-1">
            Proses
          </b-button>
          <b-button
            @click="addqstmodalprop.show = !addqstmodalprop.show"
            variant="danger">
            Batal
          </b-button>
        </template>
    </edit-quetions-modal>

  </div>
</template>

<script>
import MaterialDetailCard from '@/component/utils/CardDetail.vue'
import AddQuetionsModal from '@/component/utils/Modal.vue'
import UploadQuetionsModal from '@/component/utils/Modal.vue'
import EditQuetionsModal from '@/component/utils/Modal.vue'
import QstFileUpload from '@/component/utils/UploadFile.vue'
import QuestionForm from './_QuestionForm.vue'
import QuestionTable from './_QuestionTable.vue'
import http from '@/customs/axios'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import {
  BButton,
  BDropdown, BDropdownItem,
  BRow, BCol,
  BFormGroup, BFormInput,
  BCard,
  BAlert,
} from 'bootstrap-vue'

export default {
  props: {
    levelOptions: { type: Array },
    deptList: { type: Array }
  },
  components: {
    MaterialDetailCard, AddQuetionsModal, UploadQuetionsModal, QstFileUpload, QuestionForm, EditQuetionsModal,
    QuestionTable,
    BButton,
    BDropdown, BDropdownItem,
    BRow, BCol,
    BFormGroup, BFormInput,
    BCard, 
    BAlert,
  },
  data(){
    return{
      editdetail: false,
      form: {
        exam_point: null,
        exam_duration: null,
        exam_title: null,
        exam_material_id: null,
      },
      questiondata: {
        title: '',
        material_title: '',
        dept: '',
        level: '',
        totalqst: 0,
        point: 0,
        duration: 0,
        data: [],
        material_id: null,
      },
      statusupload: null,
      breadcrumbs: [],
      alert: {
        show: false,
        icon: '',
        variant: null,
        text: null,
      },
      addqstmodalprop: {
        show: false,
        size: 'lg',
        title: 'Tambah Soal',
        hidefooter: true
      },
      uploadqstmodalprop: {
        show: false,
        size: 'md',
        title: 'Upload Soal',
        hidefooter: false
      },
      qstfileuploadprop: {
        acceptext: '.xlsx',
      },
      editquestionmodalprop: {
        show: false,
        size: 'lg',
        title: 'Tambah Soal',
        hidefooter: true,
        edit: false,
        dataqst: null,
      },
      datafile: {
        detail: null,
        exam_add_upload: null,
      },
    }
  },
  methods: {
    editdetailqst(){
      this.editdetail = true
      this.form = {
        exam_point: this.questiondata.point,
        exam_duration: this.questiondata.duration,
        exam_title: this.questiondata.title,
        exam_material_id: this.questiondata.material_id,
      }
    },
    updateqstdetail(){
      var id = this.$route.params.id
      http
      .put('okm/question/'+id, this.form)
      .then((res) => {
        this.$toast({
          component: ToastificationContent,
          props: {
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: res.data.message
          },
        },
        { position: 'top-right' })
        this.getdata()
        this.canceleditdtl()
      })
      .catch((e) => {
        console.error(e)
      })
    },
    canceleditdtl(){
      this.editdetail = false
      this.form = {
        exam_point: null,
        exam_duration: null,
        exam_title: null,
        exam_material_id: null,
      }
    },
    getdata(){
      http
      .get('okm/question/detail/'+this.$route.params.id)
      .then((res) => {
        this.questiondata = {
          title: res.data.title,
          material_title: res.data.material.m_title,
          dept: res.data.material.dept_id,
          level: res.data.material.m_level,
          totalqst: res.data.questions.length,
          point: res.data.nilai_min,
          duration: res.data.duration,
          data: res.data.questions,
          material_id: res.data.material_id,
        }
        this.setBreadcrumb(this.questiondata.title)
      })
      .catch((e) => { console.error(e) })
    },
    setBreadcrumb(title){
      this.breadcrumbs = [
        { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
        { text: 'Master Soal', to: { name: 'apps-elearning-questions'} },
        { text: title, active: true },
      ]
      called.$emit('appbreadcrumbcustomBreadCrumbs', this.breadcrumbs)
    },
    getstatus(){
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      http
      .get('okm/question/collection/'+this.$route.params.id+'/status')
      .then((res) => {
        // console.log(res)
        this.statusupload = res.data.status
        if(this.statusupload === 1){
          this.alert = {
            show: true,
            icon: 'InfoIcon',
            text: 'Data sedang diproses. Cek kembali secara berkala.',
            variant: 'warning'
          }
        }else if(this.statusupload === 3){
          this.alert = {
            show: true,
            icon: 'XCircleIcon',
            text: 'Data gagal diproses. Silahkan coba kembali.',
            variant: 'danger'
          }
        }else {
          this.alert = {
            show: false,
            icon: 'CheckCircleIcon',
            text: 'Data berhasil diproses.',
            variant: 'primary'
          }
        }
        this.getdata()
        called.$emit('hideloading')
      })
      .catch((e) => {
        console.error(e)
      })
    },
    showadquestiondb(){
      this.addqstmodalprop.show = true
    },
    showuploadquestiondb(){
      this.uploadqstmodalprop.show = true
    },
    addquestiontodb(options){
      // console.log(options)
      let rawdata = new FormData()
      rawdata.append('question', JSON.stringify(options.data.question))
      rawdata.append('answer_options', JSON.stringify(options.data.answer_options))
      rawdata.append('answer_key', options.data.answer_key)
      rawdata.append('questions_id', this.$route.params.id)
      let id = null
      http
      .post('okm/question/collection/'+id, rawdata)
      .then((res) => {
        // console.log(res)
        this.$toast({
          component: ToastificationContent,
          props: {
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: res.data.message
          },
        },
        { position: 'top-right' })
        this.getdata()
        this.addqstmodalprop.show = false
      })
      .catch((e) => {
        console.error(e)
      })
    },
    editquestiondb(options){
      this.editquestionmodalprop = {
        show: true,
        edit: true,
        hidefooter: true,
        dataqst: options,
      }
    },
    updatequestiondb(options){
      let rawdata = new FormData()
      rawdata.append('question', JSON.stringify(options.data.question))
      rawdata.append('answer_options', JSON.stringify(options.data.answer_options))
      rawdata.append('answer_key', options.data.answer_key)
      rawdata.append('questions_id', this.$route.params.id)
      let qscid = options.id
      http
      .post('okm/question/collection/'+qscid, rawdata)
      .then((res) => {
        this.$toast({
          component: ToastificationContent,
          props: {
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: res.data.message
          },
        },
        { position: 'top-right' })
        this.getdata()
        this.editquestionmodalprop = {
          show: false,
          edit: false,
          dataqst: null,
        }
      })
      .catch((e) => {
        console.error(e)
      })
    },
    canceleditquestion(){
      this.editquestionmodalprop = {
        show: false,
        hidefooter: true,
        dataqst: null,
      },
      this.getdata()
    },
    deleteqstcolldb(options){
      var qstcollid = options.data.id
      var dataqstlength = this.questiondata.data.length
      if(dataqstlength === 1){
        alert('Data soal tersisa 1. Mohon tambahkan data, sebelum menghapus.')
      }else{
        this.$swal({
          text: 'Yakin akan dihapus?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya',
          cancelButtonText: 'Batal',
          customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ml-1',
          },
          buttonsStyling: false,
        }).then(result => {
          if(result.value){
            http
            .delete('okm/question/collection/'+qstcollid)
            .then((res) => {
              this.$toast({
                component: ToastificationContent,
                props: {
                  icon: 'CheckCircleIcon',
                  variant: 'warning',
                  title: res.data.message
                },
              },
              { position: 'top-right' })
              this.getdata()
            })
            .catch((e) => {
              console.error(e)
            })
          }else{
            console.log(result)
          }
        })
      }
    },
    handlefile(options){
      this.datafile = {
        detail: options.detail,
        exam_add_upload: options.file,
        questions_id: parseInt(this.$route.params.id),
        exam_material: this.questiondata.material_id,
      }
    },
    handledelfile(){
      this.datafile = {
        detail: null,
        exam_add_upload: null,
      }
    },
    handleUploadFile(){
      // console.log(this.datafile)
      let uploadfile = new FormData()
      uploadfile.append('exam_add_upload', this.datafile.exam_add_upload)
      uploadfile.append('questions_id', this.datafile.questions_id)
      uploadfile.append('exam_material', this.datafile.exam_material)
      http
      .post('okm/question/collection/'+this.datafile.questions_id+'/addfromfile', uploadfile, {
        headers: { 'content-type': 'multipart/form-data' }
      })
      .then((res) => {
        console.log(res)
        this.getdata()
        this.uploadqstmodalprop.show = false
        this.datafile = {
          detail: null,
          exam_add_upload: null,
        }
      })
      .catch((e) => {
        console.error(e)
      })
    }
  },
  computed: {
    deptname(){
      let input = this.questiondata.dept
      let scoped = this.deptList.filter((item) => { return item.id === input })
      if(scoped.length !== 0){
        return scoped[0].name
      }
    },
    disableduploadbtn(){
      if(this.datafile.exam_add_upload !== null && 
        this.datafile.detail.size <= 8388608 && 
        this.datafile.detail.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
        return false
      }
      return true
    }
  },
  mounted(){
    this.getstatus()
  },
}
</script>

<style>

</style>