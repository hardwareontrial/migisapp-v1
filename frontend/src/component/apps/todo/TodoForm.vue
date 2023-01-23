<template>
  <div>
    <b-sidebar
      id="sidebar-task-handler"
      width="410px"
      sidebar-class="sidebar-lg"
      :visible="visible"
      bg-variant="white"
      no-close-on-backdrop
      no-close-on-esc
      no-header-close
      shadow
      backdrop
      no-header
      right>
      <template #default>
        <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
          <b-button
            type="button"
            v-if="edit"
            size="sm"
            :variant="form.isComplete ? 'outline-success' : 'outline-secondary'"
            @click="form.isComplete = !form.isComplete">
            {{ form.isComplete ? 'Selesai' : 'Tandai Selesai' }}
          </b-button>
          <h5
            v-else
            class="mb-0">
            Buat Tugas
          </h5>
          <div>
            <feather-icon
              v-show="edit"
              icon="TrashIcon"
              class="cursor-pointer"
              @click="sentMarkDelete(taskid, form.isDeleted)"/>
            <feather-icon
              class="ml-1 cursor-pointer"
              icon="StarIcon"
              size="16"
              :class="{ 'text-warning': form.isImportant }"
              @click="form.isImportant = !form.isImportant"/>
            <feather-icon
              class="ml-1 cursor-pointer"
              icon="XIcon"
              size="16"
              @click="closeform"/>
          </div>
        </div>

        <b-form
          class="p-2">
          <b-alert
            variant="danger"
            :show="error.show">
            <h4 class="alert-heading">
              Error ({{ error.code}})
            </h4>
            <div class="alert-body">
              <li v-for="(err, i) in error.message" :key="i">{{ err[0] }}</li>
            </div>
          </b-alert>

          <b-form-group
            label="Requestor"
            label-for="todo-requestor">
            <v-select 
              class="assignee-selector"
              :options="userlist"
              label="name"
              :clearable="false"
              :reduce="name => name.id"
              id="todo-requestor"
              v-model="form.requestor">
              <template #option="{ avatar, avatarlink, name }">
                <b-avatar
                  size="26"
                  :src="avatar ? avatarlink : null"
                  variant="light-primary"/>
                <span class="ml-50 d-inline-block align-middle"> {{ name }}</span>
              </template>
              <template #selected-option="{ avatar, avatarlink, name }">
                <b-avatar
                  size="26"
                  :src="avatar ? avatarlink : null"
                  variant="light-primary"/>
                <span class="ml-50 d-inline-block align-middle"> {{ name }}</span>
              </template>
            </v-select>
          </b-form-group>
          <b-form-group
            label="Kepada"
            label-for="todo-assignee">
            <v-select 
              class="assignee-selector"
              :options="userlist"
              label="name"
              :clearable="false"
              :reduce="name => name.id"
              id="todo-assignee"
              v-model="form.assignee">
              <template #option="{ avatar, avatarlink, name }">
                <b-avatar
                  size="26"
                  :src="avatar ? avatarlink : null"
                  variant="light-primary"/>
                <span class="ml-50 d-inline-block align-middle"> {{ name }}</span>
              </template>
              <template #selected-option="{ avatar, avatarlink, name }">
                <b-avatar
                  size="26"
                  :src="avatar ? avatarlink : null"
                  variant="light-primary"/>
                <span class="ml-50 d-inline-block align-middle"> {{ name }}</span>
              </template>
            </v-select>
          </b-form-group>
          <b-form-group
            label="Judul"
            label-for="todo-title">
            <b-form-input
              id="todo-title"
              v-model="form.title"/>
          </b-form-group>
          <b-form-group
            label="Batas Waktu"
            label-for="todo-duedate">
            <flat-pickr
              class="form-control"
              v-model="form.duedate"/>
          </b-form-group>
          <b-form-group
            label="Tags"
            label-for="todo-tags">
            <v-select 
              multiple
              id="todo-tags"
              :close-on-select="false"
              :options="taskTags"
              :reduce="option => option.value"
              v-model="form.tags"/>
          </b-form-group>
          <b-form-group
            label="Detail"
            label-for="todo-detail">
            <!-- <b-form-textarea 
              id="todo-detail"/> -->
            <quill-editor
              :options="editorDetail"
              id="quil-content"
              v-model="form.detail"/>
            <div
              id="quill-toolbar"
              class="d-flex justify-content-end border-top-0">
              <button class="ql-bold" />
              <button class="ql-italic" />
              <button class="ql-underline" />
              <button class="ql-strike" />
              <button class="ql-list" value="ordered" />
            </div>
          </b-form-group>
          <div class="d-flex mt-2">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              :variant="processing ? 'secondary' : 'primary'"
              :disabled="processing"
              class="mr-2"
              type="button"
              @click="edit ? submitUpdate() : submitStore()">
              {{ edit ? 'Update' : 'Tambah' }}
            </b-button>
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="button"
              variant="outline-secondary"
              :disabled="processing"
              @click="closeform">
              Tutup
            </b-button>
          </div>
        </b-form>
      </template>
    </b-sidebar>
  </div>
</template>

<script>
import {
  BSidebar, 
  BForm, BFormGroup, BFormInput, BFormTextarea,
  BAvatar, 
  BButton,
  BAlert,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import flatPickr from 'vue-flatpickr-component'
import Ripple from 'vue-ripple-directive'
import { quillEditor } from 'vue-quill-editor'
import http from '@/customs/axios'
import { avatarText } from '@core/utils/filter'

export default {
  components: {
    BSidebar, 
    BForm, BFormGroup, BFormInput, BFormTextarea,
    BAvatar, 
    BButton,
    BAlert,
    vSelect,
    flatPickr,
    quillEditor,
  },
  directives: { Ripple },
  data(){
    return{
      visible: false,
      form: {
        title: '',
        assignee: '',
        duedate: this.$moment().format('YYYY-MM-DD'),
        tags: [],
        detail: '',
        requestor: '',
        isComplete: false,
        isDeleted: false,
        isImportant: false,
      },
      editorDetail: {
        modules: {
          toolbar: '#quill-toolbar',
        },
        placeholder: 'Detail Tugas',
      },
      taskTags: [
        { label: 'Low', value: 'low' },
        { label: 'Medium', value: 'medium' },
        { label: 'High', value: 'high' },
        { label: 'Update', value: 'update' },
      ],
      avatarText,
      error: {
        code: '',
        message: '',
        show: false,
      },
      userlist: [],
      processing: false,
      edit: false,
      taskid: '',
      authuserid: '',
    }
  },
  methods: {
    openform(){
      this.visible = true
      this.form.requestor = this.userloginid
      this.form.assignee = this.userloginid
    },
    closeform(){
      this.visible = false
      this.form = {
        title: '',
        assignee: '',
        duedate: this.$moment().format('YYYY-MM-DD'),
        tags: [],
        detail: '',
        requestor: '',
        isComplete: false,
        isDeleted: false,
        isImportant: false,
      },
      this.error = {
        code: '',
        message: '',
        show: false,
      }
      this.edit = false
      this.taskid = ''
    },
    userList(){
      http
      .get('misc/list/userlist')
      .then((res) => { 
        // console.log(res.data)
        this.userlist = res.data
      })
      .catch((e) => { console.error(e) })
    },
    submitStore(){
      this.processing = true
      http
      .post('todo/data', this.form)
      .then((res) => {
        // console.log(res)
        this.processing = false
        called.$emit('todolistFetchData')
        this.closeform()
      })
      .catch((e) => {
        console.error(e)
        this.error = {
          code: e.response.status,
          message: e.response.data.errors,
          show: true,
        }
        this.processing = false
      })
    },
    editTask(val){
      this.visible = true
      this.edit = true
      this.form = {
        title: val.title,
        assignee: val.assignee_id,
        duedate: val.dueDate,
        tags: val.tags,
        detail: val.detail,
        requestor: val.requestor_id,
        isComplete: !!+val.isComplete,
        isDeleted: !!+val.isDeleted,
        isImportant: !!+val.isImportant,
      }
      this.taskid = val.id
    },
    submitUpdate(){
      this.processing = true
      http
      .put('todo/data/'+this.taskid, this.form)
      .then((res) => {
        // console.log(res)
        this.processing = false
        called.$emit('todolistFetchData')
        this.closeform()
      })
      .catch((e) => {
        console.error(e)
        this.processing = false
      })
    },
    sentMarkDelete(id, status){
      this.$emit('mark-delete', id, status)
      this.closeform()
    }
  },
  mounted(){
    called.$on('todoformvisible', () => { this.openform() })
    called.$on('todoformunvisible', () => { this.closeform() })
    called.$on('todoformEditTask', (val) => { this.editTask(val) })
    this.userList()
    const data = JSON.parse(localStorage.getItem('userdata'))
    const isAdmin = !!data.admin
    if(!isAdmin){
      this.authuserid = data.detailuser.id
    }
  },
  computed: {
    userloginid(){
      var data = JSON.parse(localStorage.getItem('userdata'))
      if(data !== undefined){
        return data.detailuser.id
      }
    },
  },
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
  @import '@core/scss/vue/libs/vue-flatpicker.scss';
  @import '@core/scss/vue/libs/quill.scss';
</style>

<style lang="scss" scoped>
  @import '~@core/scss/base/bootstrap-extended/include';

  .assignee-selector {
    ::v-deep .vs__dropdown-toggle {
      padding-left: 0;
    }
  }

  #quil-content ::v-deep {
    > .ql-container {
      border-bottom: 0;
    }

    + #quill-toolbar {
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      border-bottom-left-radius: $border-radius;
      border-bottom-right-radius: $border-radius;
    }
  }
</style>