<template>
  <div>
    <div class="d-flex mt-0">
      <feather-icon
        icon="InfoIcon"
        size="19" />
      <h4 class="mb-1 ml-50"> User Info </h4>
    </div>
    <validation-observer
      ref="infouser"
      #default="{invalid}">
      <b-row>
        <b-col cols="12">
          <b-row>
            <b-col
              cols="12"
              md="6">
              <b-media class="mb-1">
                <template #aside>
                  <b-avatar
                    :src="previewavatar"
                    :text="avatarText(userInfo.name)"
                    variant="light-primary"
                    size="90px"
                    rounded>
                  </b-avatar>
                </template>
                <h4 class="mb-1">{{ tmpform.name }}</h4>
                <validation-provider
                  rules="size:100|mimes:image/jpg,image/png,image/jpeg"
                  name="Avatar"
                  v-slot="{validate, errors}">
                  <div class="d-flex flex-wrap">
                    <b-form-file
                      class=""
                      accept=".jpg, .png, .jpeg"
                      ref="refInputEl"
                      placeholder="Pilih..."
                      type="file"
                      @change="fileOnChange($event) || validate($event)"/>
                  </div>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-media>
            </b-col>
          </b-row>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group
            label="Nama"
            label-for="fi-name">
            <validation-provider
              #default="{errors}"
              name="Nama"
              vid="fiv-name"
              rules="required">
              <b-form-input
                v-model="tmpform.name"
                :state="errors.length > 0 ? false:null"
                name="fi-name"
                id="fi-name" />
                <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group
            label="Position"
            label-for="fi-position">
            <v-select
              v-model="tmpform.position"
              :reduce="name=>name.id"
              label="name"
              :clearable="true"
              :options="positionlist"
              id="fi-position"/>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group
            label="Active"
            label-for="fi-active">
            <v-select
              v-model="tmpform.isactive"
              :reduce="text=>text.value"
              label="text"
              :clearable="false"
              :options="optionsactive"
              id="fi-active"/>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row class="mt-1">
        <b-col>
          <b-button
            :disabled="invalid"
            @click="updateinfo()"
            type="button"
            variant="outline-primary"
            class="mb-1 mb-sm-0 mr-0 mr-sm-1"
            :block="$store.getters['app/currentBreakPoint'] === 'xs'">
            Update
          </b-button>
          <b-button
            @click="closeform()"
            type="button"
            variant="danger"
            :block="$store.getters['app/currentBreakPoint'] === 'xs'">
            Tutup
          </b-button>
        </b-col>
      </b-row>
    </validation-observer>
  </div>
</template>

<script>
import {
  BRow, BCol,
  BFormGroup, BFormInput, BFormFile,
  BMedia, BAvatar,
  BButton,
} from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, size, mimes, image } from '@validations'
import vSelect from 'vue-select'
import http from '@/customs/axios'
import { avatarText } from '@core/utils/filter'

export default {
  props: {
    userInfo: {
      type: Object,
      required: true,
    }
  },
  components: {
    BRow, BCol,
    BFormGroup, BFormInput, BFormFile,
    ValidationProvider, ValidationObserver,
    vSelect,
    BMedia, BAvatar,
    BButton,
  },
  data(){
    return{
      required, size, mimes, image,
      optionsactive: [
        { value: 1, text: 'Aktif'},
        { value: 0, text: 'Non-Aktif'}
      ],
      positionlist: [],
      avatarText,
      tmpform: {
        name: null,
        position: '',
        isactive: '',
        avatar: null,
      },
      previewavatar: null,
      processing: false,
    }
  },
  methods: {
    getpositionlist(){
      http.get('misc/list/positions')
      .then((res) => {
        const response = res.data
        const filtered = response.filter(function(obj){
          return obj.id !== 1
        })
        this.positionlist = filtered
      })
      .catch((e) => {
        console.error(e)
      })
    },
    fileOnChange(e){
      const file = e.target.files[0]
      this.tmpform.avatar = file
      if(file){
        let reader = new FileReader()
        reader.onload = evt => {
          this.previewavatar = evt.target.result
        }
        reader.readAsDataURL(file)
      }else{
        this.previewavatar = this.userInfo.avatar ? this.userInfo.avatarlink : null
      }
    },
    updateinfo(){
      this.$refs.infouser.validate().then(success => {
        if(success){
          const id = this.userInfo.id
          const nik = this.userInfo.nik
          let formupdateinfo = new FormData()
            formupdateinfo.append('name', this.tmpform.name)
            formupdateinfo.append('position_id', this.tmpform.position)
            formupdateinfo.append('isactive', this.tmpform.isactive)
            formupdateinfo.append('avatar', this.tmpform.avatar)
            formupdateinfo.append('userid', id)
            formupdateinfo.append('usernik', nik)
          called.$emit('showloading', {show: true, text: 'Sedang memproses...'})
          http.post('user/update/'+id, formupdateinfo, { params: {keyword: 'update-from-usermgmt'} })
          .then((res) => {
            // console.log(res)
            called.$emit('hideloading')
            this.closeform()
          })
          .catch((e) => { console.error(e) })
        }
      })
    },
    resetform(){
      this.tmpform = {
        name: null,
        position: null,
        isactive: null,
        avatar: null,
      }
      this.previewavatar = null
    },
    closeform(){
      this.resetform()
      this.$router.replace({ name: 'apps-usermgt-list' })
    }
  },
  watch: {
    'userInfo': {
      immediate: true,
      deep: true,
      handler(n){
        if(n !== null){
          this.tmpform.name = this.userInfo.name,
          this.tmpform.position = this.userInfo.position_id ? this.userInfo.position_id : '',
          this.tmpform.isactive = this.userInfo.active,
          this.previewavatar = this.userInfo.avatar ? this.userInfo.avatarlink : null
        }
      }
    }
  },
  computed:{
    userdata(){
      const data = JSON.parse(localStorage.getItem('userdata'))
      return data
    }
  },
  mounted(){
    this.getpositionlist()
  },
}
</script>

<style lang="scss">
  @import '@core/scss/vue/libs/vue-select.scss';
</style>