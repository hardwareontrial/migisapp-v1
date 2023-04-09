<template>
  <div>
    <b-card
      :title="editmode ? '' : 'Form Tambah Materi'">
      <validation-observer
        ref="formdelivery"
        #default="{invalid}">
        <b-row>
          <b-col cols="12">
            <validation-provider
              #default="{errors}"
              name="Judul Materi"
              vid="elr-fm-title"
              rules="required">
              <b-form-group
                label="Judul Materi"
                label-for="f-material-title"
                label-cols-md="3">
                <b-form-input
                  v-model="form.title"
                  placeholder="Judul Materi"
                  id="f-material-title"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </b-form-group>
            </validation-provider>
          </b-col>
          <b-col cols="12">
            <validation-provider
              #default="{errors}"
              name="Sub Departemen"
              vid="elr-fm-dept"
              rules="required">
              <b-form-group
                label="Sub Departemen"
                label-for="f-material-sub_dept"
                label-cols-md="3">
                <v-select 
                  v-model="form.dept"
                  label="name"
                  :options="listDept"
                  :reduce="name => name.id"
                  :clearable="false"
                  />
                <small class="text-danger">{{ errors[0] }}</small>
              </b-form-group>
            </validation-provider>
          </b-col>
          <b-col cols="12">
            <validation-provider
              #default="{errors}"
              name="Pilihan Level"
              vid="elr-fm-level"
              rules="required">
              <b-form-group
                label="Level"
                label-for="f-material-level"
                label-cols-md="3">
                <select 
                  v-model="form.level"
                  id="selectlevel" 
                  class="form-control">
                  <option 
                    :value="opt"
                    :key="i"
                    v-for="(opt, i) in levelOptions[0]">
                    {{ levelOptions[1][opt] }}
                  </option>
                </select>
                <small class="text-danger">{{ errors[0] }}</small>
              </b-form-group>
            </validation-provider>
          </b-col>
          <!-- <b-col cols="12">
            <validation-provider
              #default="{errors}"
              name="Bobot Durasi"
              vid="elr-fm-duration"
              rules="required">
              <b-form-group
                label="Bobot (Menit)"
                label-for="f-material-duration"
                label-cols-md="3">
                <b-form-input
                  v-model="form.duration"
                  @keypress="isNumber($event)"
                  placeholder="Bobot Materi (Menit)"
                  id="f-material-duration"/>
                <small class="text-danger">{{ errors[0] }}</small>
              </b-form-group>
            </validation-provider>
          </b-col> -->
          <b-col cols="12">
            <validation-provider
              #default="{errors}"
              name="Deskripsi"
              vid="elr-fm-title"
              rules="required">
              <b-form-group
                label="Deskripsi"
                label-for="f-material-desc"
                label-cols-md="3">
                <div>
                  <quill-editor
                    v-model="form.desc"
                    :options="editorOption">
                    <div
                      id="toolbar"
                      slot="toolbar">
                      <button class="ql-bold">
                        Bold
                      </button>
                      <button class="ql-italic">
                        Italic
                      </button>
                      <select class="ql-size">
                        <option value="small" />
                        <option selected />
                        <option value="large" />
                        <option value="huge" />
                      </select>
                      <select class="ql-font">
                        <option selected="selected" />
                        <option value="serif" />
                        <option value="monospace" />
                      </select>
                    </div>
                  </quill-editor>
                </div>
                <small class="text-danger">{{ errors[0] }}</small>
              </b-form-group>
            </validation-provider>
          </b-col>
          <b-col cols="12" v-show="!editmode">
            <b-form-group
              label="Upload Materi"
              label-for="f-material-upload"
              label-cols-md="3">
              <materi-upload-file
                @sendfile="handlefile" 
                @deletefile="handledeletefile"
                acceptext=".pdf, .odp"/>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label=""
              label-for="f-material-upload"
              label-cols-md="3">
              <b-button
                :disabled="invalid"
                @click="editmode ? updatedatamateri() : storedatamateri()"
                type="button"
                :variant="invalid ? 'secondary' : 'primary'"
                class="mr-1">
                {{ editmode ? 'Update' : 'Tambah' }}
              </b-button>
              <b-button
                @click="handleBack"
                type="button"
                variant="danger">
                {{ editmode ? 'Batal' : 'Tutup' }}
              </b-button>
            </b-form-group>
          </b-col>
        </b-row>
      </validation-observer>
    </b-card>
  </div>
</template>

<script>
import {
  BCard,
  BRow, BCol,
  BFormGroup, BFormInput, BForm, BFormTextarea, BFormRadioGroup, BFormFile, BFormSelect, BFormSelectOption,
  BButton,
  BAlert,
} from 'bootstrap-vue'
import { quillEditor } from 'vue-quill-editor'
import http from '@/customs/axios'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import MateriUploadFile from '@/component/utils/UploadFile.vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'

export default {
  components: {
    BCard,
    BRow, BCol,
    BFormGroup, BFormInput, BForm, BFormTextarea, BFormRadioGroup, BFormFile, BFormSelect, BFormSelectOption,
    BButton,
    BAlert,
    quillEditor,
    vSelect,
    MateriUploadFile,
    ValidationProvider, ValidationObserver,
  },
  props: {
    levelOptions: {
      type: Array,
    }
  },
  data(){
    return{
      editorOption: {
        modules: {
          toolbar: '#toolbar',
        },
      },
      form: {
        title: '',
        dept: '',
        level: '',
        duration: '',
        desc: '',
        filematerial: null,
        isactive: '',
      },
      listDept: [],
      editmode: false,
      required,
    }
  },
  methods: {
    isNumber(e){
      e = (e) ? e : window.event;
      let cCode = (e.which) ? e.which : e.kCode;
      if ((cCode > 31 && (cCode < 48 || cCode > 57)) && (cCode < 40 || cCode > 41) && cCode !== 43 && cCode !== 45) {
        e.preventDefault();
      } else {
        return true;
      }
    },
    resetform(){
      this.form = {
        title: '',
        dept: null,
        level: null,
        duration: null,
        desc: '',
        filematerial: null,
      }
    },
    handleBack(){
      this.resetform()
      if(this.editmode){
        this.editmode = false
        this.$router.back()
      }else{
        called.$emit('loadmaterial')
        this.$router.replace({ name: 'apps-elearning-materials' })
      }
    },
    loadDeptList(){
      http
      .get('misc/list/depts')
      .then((res) => {
        this.listDept = res.data
      })
      .catch((e) => {
        console.error(e)
      })
    },
    onFileChange(e){
      this.form.filematerial = e.target.files[0]
    },
    storedatamateri(){
      // if(!window.confirm('Apakah Anda Yakin?')){
      //   return
      // }
      // console.log('submitted')
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      let materialFormData = new FormData
      materialFormData.append('title', this.form.title)
      materialFormData.append('dept', this.form.dept)
      materialFormData.append('level', this.form.level)
      materialFormData.append('duration', this.form.duration)
      materialFormData.append('desc', this.form.desc)
      materialFormData.append('filematerial', this.form.filematerial)
      http
      .post('okm/material/new', materialFormData, {
        headers: { 'content-type': 'multipart/form-data' } 
      })
      .then((res) => {
        called.$emit('hideloading')
        this.$toast({
          component: ToastificationContent,
          props: {
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: res.data.message
          },
        },
        { position: 'top-right' })
        this.handleBack()
      })
      .catch((e) => {
        called.$emit('hideloading')
        console.error(e)
        this.$toast({
          component: ToastificationContent,
          props: {
            icon: 'XCircleIcon',
            variant: 'danger',
            title: 'Gagal menyimpan data'
          },
        },
        { position: 'top-right' })
      })
    },
    getdatamateri(){
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      http
      .get('okm/material/'+this.$route.params.id)
      .then((res) => {
        this.form = {
          title: res.data.m_title,
          dept: res.data.dept_id,
          level: res.data.m_level,
          duration: res.data.m_duration,
          desc: res.data.m_desc,
          isactive: res.data.isactive,
        }
        this.setbreadcrumb(this.form.title)
        called.$emit('hideloading')
      })
      .catch((e) => {
        console.error(e)
      })
    },
    setbreadcrumb(title){
      this.breadcrumbs = [
        { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
        { text: 'Materi', to: { name: 'apps-elearning-materials'} },
        { text: title, active: true },
      ]
      called.$emit('appbreadcrumbcustomBreadCrumbs', this.breadcrumbs)
    },
    updatedatamateri(){
      called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
      http
      .put('okm/material/'+this.$route.params.id, this.form)
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
        called.$emit('hideloading')
        this.$router.replace({ name: 'apps-elearning-materials-detail', params: { id: res.data.id, slug: res.data.slug } })
      })
      .catch((e) => {
        console.error(e)
      })
    },
    handlefile(options){
      this.form.filematerial = options.file
    },
    handledeletefile(){
      this.form.filematerial = null
    },
  },
  mounted(){
    this.loadDeptList()
    if(this.$route.params.id){
      this.editmode = true
      this.getdatamateri()
    }
  },
  computed: {
    // disableprocess(){
    //   if(this.form.title && this.form.dept && this.form.level && this.form.duration && this.form.desc){ return false }
    //   return true
    // }
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/quill.scss';
  @import '@core/scss/vue/libs/vue-select.scss';
</style>