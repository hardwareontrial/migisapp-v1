<template>
  <b-card class="blog-edit-wrapper">

      <b-media
      no-body
      vertical-align="center"
    >
      <b-media-aside class="mr-75">
        <b-avatar
          size="38"
          variant="light-primary"
        />
      </b-media-aside>
      <b-media-body>
        <h6 class="mb-25">
          Name
        </h6>
        <b-card-text>Date</b-card-text>
      </b-media-body>
    </b-media>

    <b-alert
      :show="error.show"
      variant="danger"
      class="mt-2">
      <div class="alert-body">
        <strong>Error Code: {{ error.code}}</strong>
        <ul>
          <li v-for="(err, i) in error.message" :key="i">{{ err[0] }}</li>
        </ul>
      </div>
    </b-alert>

    <b-form class="mt-2">
      <b-row>
        <b-col md="6">
          <b-form-group
            label="Judul"
            label-for="blog-edit-title"
            class="mb-2">
            <b-form-input
              id="blog-edit-title"
              v-model="form.title"/>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group
            label="Kategori"
            label-for="blog-edit-category"
            class="mb-2">
            <v-select
              id="blog-edit-category"
              multiple
              v-model="form.category"
              :options="listCategories"
              label="name"
              :reduce="name => name.name"/>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group
            label="Slug"
            label-for="blog-edit-slug"
            class="mb-2">
            <b-form-input
              :disabled="true"
              id="blog-edit-slug"
              v-model="form.slug"/>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group
            label="Status"
            label-for="blog-edit-status"
            class="mb-2">
            <v-select
              id="blog-edit-status"
              :options="statusOptions"
              label="text"
              :reduce="text=>text.value"
              v-model="form.status"
              :clearable="false"/>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group
            label="Content"
            label-for="blog-content"
            class="mb-2">
            <quill-editor
              id="blog-content"
              v-model="form.content"
              :options="snowOption"/>
          </b-form-group>
        </b-col>
        <b-col
          cols="12"
          class="mb-2">
          <div class="border rounded p-2">
            <h4 class="mb-1">
              Gambar Sampul
            </h4>
            <b-media
              no-body
              vertical-align="center"
              class="flex-column flex-md-row">
              <b-media-aside>
                <!-- <b-img
                  ref="refPreviewEl"
                  height="110"
                  width="170"
                  class="rounded mr-2 mb-1 mb-md-0"
                /> -->
                <div
                  class="imagePreview"
                  :style="{ 'background-image': `url(${previewImage})`}">
                </div>
              </b-media-aside>
              <b-media-body>
                <small class="text-muted">Resolusi gambar 800x400, ukuran gambar 2 mb.</small>
                <b-card-text class="my-50">
                  <!-- <b-link id="blog-image-text">
                    C:\fakepath\
                  </b-link> -->
                </b-card-text>
                <div class="d-inline-block">
                  <b-form-file
                    ref="refInputEl"
                    accept=".jpg, .png, .gif"
                    placeholder="Pilih..."
                    type="file"
                    @input="inputImageRenderer"/>
                </div>
              </b-media-body>
            </b-media>
          </div>
        </b-col>
        <b-col
          cols="12"
          class="mt-50">
          <b-button
            type="button"
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="primary"
            class="mr-1"
            :disabled="processing"
            @click="edit? handleUpdate() : handleSubmit()">
            {{ edit? 'Update' : 'Simpan' }}
          </b-button>
          <b-button
            type="button"
            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
            variant="outline-secondary"
            @click="closeForm()">
            Tutup
          </b-button>
        </b-col>
      </b-row>
    </b-form>
  </b-card>

</template>

<script>
import store from '@/store'
import {
  BCard, BMedia, BAvatar, BCardText, BMediaAside, BMediaBody, BForm, BRow, BCol, BFormGroup, BFormInput, BImg, BFormFile, BLink, BButton, BAlert,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import { quillEditor } from 'vue-quill-editor'
import Ripple from 'vue-ripple-directive'
import http from '@/customs/axios'

export default {
  components:{
    BCardText, BCard, 
    BMediaBody, BMedia, BMediaAside, 
    BAvatar,
    BForm, BFormGroup, BFormInput, BFormFile,
    BLink,
    BImg,
    BRow, BCol,
    BButton,
    BAlert,
    vSelect,
    quillEditor,
  },
  directives: { Ripple },
  data(){
    return{
      form: {
        title: '',
        slug: '',
        category: [],
        status: '',
        content: '',
        coverimage: '',
        creator: ''
      },
      refInputEl: null,
      refPreviewEl: null,
      edit: false,
      processing: false,
      newsid: '',
      snowOption: { theme: 'snow' },
      statusOptions: [
        { value: 0, text: 'Down' },
        { value: 1, text: 'Draft' },
        { value: 2, text: 'Publish' }],
      listCategories: [],
      previewImage: null,
      error: {
        code: '',
        message: [],
        show: false
      },
      newsid : ''
    }
  },
  methods: {
    getCategory(){
      http
      .get('news/admin/list-categories')
      .then((res) => {
        // console.log(res)
        this.listCategories = res.data
      })
      .catch((e) => { console.log(e) })
    },
    inputImageRenderer(){
      let input = this.$refs.refInputEl
      let file = input.files
      if(input && file){
        if(file[0]['size'] >= 2097152) {
          alert('file besar')
          return false
        }
        if(file[0]['type'] !== 'image/jpeg' && file[0]['type'] !== 'image/png'){
          alert('File harus bertipe JPG/JPEG/PNG.')
          return false
        }

        let reader = new FileReader();
        reader.onload = e => {
          this.previewImage = e.target.result
          this.form.coverimage = e.target.result
        }
        reader.readAsDataURL(file[0])
      }
    },
    closeForm(){
      this.edit = false
      this.processing = false
      this.error = {
        code: '',
        message: [],
        show: false
      }
      this.previewImage = null
      this.refInputEl = null
      this.refPreviewEl = null
      called.$emit('admin-newslist')
      this.$router.push({ name: 'apps-news-admin' })
    },
    handleSubmit(){
      this.error = {
        code: '',
        message: [],
        show: false
      }
      this.processing = true
      http
      .post('news/admin/datastore', this.form)
      .then((res) => {
        console.log(res)
        this.processing = false
        this.closeForm()
      })
      .catch((e) => {
        console.error(e)
        this.processing = false
        this.error = {
          code: e.response.status,
          message: e.response.data.errors,
          show: true
        }
      })
    },
    editMode(val){
      this.form = {
        title: val.title,
        slug: val.slug,
        category: val.category,
        status: val.status,
        content: val.content,
        creator: val.created_by,
        coverimage: val.coverimage
      }
      this.previewImage = val.coverimage
      this.newsid = val.id
    },
    handleUpdate(){
      // console.log(this.form)
      this.error = {
        code: '',
        message: [],
        show: false
      }
      this.processing = true
      http
      .put('news/admin/datastore/'+this.newsid, this.form)
      .then((res) => {
        console.log(res)
        this.processing = false
        this.closeForm()
      })
      .catch((e) => {
        console.error(e)
        this.processing = false
        this.error = {
          code: e.response.status,
          message: e.response.data.errors,
          show: true
        }
      })
    }
  },
  watch: {
    'form.title': {
      handler(n){
        const res = n.replace(/\s+/g, '-').toLowerCase()
        this.form.slug = res
      }
    },
  },
  created(){
    if('id' in this.$route.params){
      const detail = this.$route.params.detail
      if(detail === undefined) return this.$router.replace({ name: 'apps-news-admin' })
      console.log('News Form: Edit Mode')
      this.edit = true
      this.editMode(detail)
    }else{
      console.log('News Form: Create Mode')
      this.edit = false
    }
    const userlogin = JSON.parse(localStorage.getItem('userdata'))
    if(userlogin)this.form.creator = userlogin.detail.id
  },
  mounted(){
    this.getCategory()
  },
  beforeCreate(){
    const layout = this.$route.meta.dlayout
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', layout)
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
  @import '@core/scss/vue/libs/quill.scss';
  @import '@core/scss/vue/pages/page-blog.scss';

  .imagePreview {
    width: 170px;
    height: 110px;
    display: block;
    cursor: pointer;
    background-size: cover;
    background-position: center center;
  }
</style>