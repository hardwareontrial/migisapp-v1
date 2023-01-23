  <template>
  <div>
    <material-detail-card
      background-card-color="LightBlue">
      <template v-slot:cstmtitledetail>
        <div>
          <feather-icon icon="InfoIcon" size="18"/>
          <span class="mx-50">INFO</span>
        </div>
      </template>
      <template v-slot:datadetail>
        <b-row>
          <b-col cols="4">
            <p class="small hint-text m-0"><b>Nama Materi</b></p>
            <p class="font-montserrat bold">
              <feather-icon icon="FileIcon" class="mx-25"/>
              {{ materialdata.name }}
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
            <p class="small hint-text m-0"><b>Bobot Materi</b></p>
            <p class="font-montserrat bold">
              <feather-icon icon="ShieldIcon" class="mx-25"/>
              {{ materialdata.w_materi }} Menit
            </p>
          </b-col>
        </b-row>
      </template>
      <template v-slot:cstmbtndetail>
        <b-button
          :to="{ name: 'apps-elearning-materials-edit', params: { id: materialdata.id } }"
          variant="flat-primary"
          class="btn-icon rounded-circle">
          <feather-icon icon="EditIcon" size="18"/>
        </b-button>
      </template>
    </material-detail-card>
    
    <b-card
      v-if="!materialdata.data"
      title="Upload File">
      <b-row>
        <b-col cols="9">
          <materi-upload-input 
            @sendfile="handlefile" 
            @deletefile="handledeletefile"
            acceptext=".pdf, .odp"/>
        </b-col>
        <b-col cols="3">
          <b-button
            v-show="datafile"
            @click="addmaterial()"
            variant="flat-primary"
            class="btn-icon">
            <feather-icon icon="SendIcon" class="mr-1"/>Kirim
          </b-button>
        </b-col>
      </b-row>
    </b-card>
    
    <app-collapse
      accordion
      type="margin">
      <app-collapse-item title="Materi">
        <div v-if="materialdata.data">
          <material-reader :file="materialdata.data" />
        </div>
        <div v-else>
          File materi kosong.
        </div>
        <div class="d-flex justify-content-end"  v-if="materialdata.data">
          <b-button-group>
            <b-button
              @click="deletefilematerial(materialdata.id)"
              variant="flat-danger"
              class="btn-icon"
              size="sm"
              type="button">
              <feather-icon icon="Trash2Icon" class="mr-25"/>
            </b-button>
          </b-button-group>
        </div>
      </app-collapse-item>
    </app-collapse>
    
    <b-card class="mt-1" no-body>
      <b-card-header>
        <b-card-title>Soal</b-card-title>
        <feather-icon
          @click="$router.push({name: 'apps-elearning-questions-create'})"
          icon="PlusCircleIcon"
          size="18"
          class="cursor-pointer"
        />
      </b-card-header>
      <b-card-body>
        <div v-if="qstlist.length <= 0">Tidak ada data.</div>
        <div v-else>
          <b-list-group>
            <b-list-group-item
              v-for="(qsl, iqsl) in qstlist"
              :key="iqsl"
              class="d-flex justify-content-between align-items-center">
              <span>{{ qsl.title }}</span>
              <div>
                <b-badge
                  class="mr-25"
                  :variant="qsl.isactive === 1 ? 'success':'secondary'"
                  pill>
                  {{ qsl.isactive === 1 ? 'Aktif':'Tidak Aktif' }}
                </b-badge>
                <b-button
                  :to="{ name: 'apps-elearning-questions-detail', params: { id: qsl.id, slug: qsl.slug, title: qsl.title } }"
                  variant="flat-primary"
                  class="btn-icon"
                  size="sm"
                  type="button">
                  <feather-icon icon="EyeIcon" class="mr-25"/>
                </b-button>
              </div>
            </b-list-group-item>
          </b-list-group>
        </div>
      </b-card-body>
    </b-card>
  </div>
</template>

<script>
import http from '@/customs/axios'
import {
  BCard, BCardHeader, BCardTitle, BCardBody,
  BCol, BRow,
  BFormGroup, BFormInput, BFormFile,
  BButton, BButtonGroup,
  BListGroup, BListGroupItem,
  BBadge,
} from 'bootstrap-vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import MaterialDetailCard from '@/component/utils/CardDetail.vue'
import MateriUploadInput from '@/component/utils/UploadFile.vue'
import MaterialReader from './_MaterialReader.vue'
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'

export default {
  props:{
    levelOptions: {
      type: Array,
    },
    deptList: {
      type: Array,
    }
  },
  components: {
    BCard, BCardHeader, BCardTitle, BCardBody,
    BCol, BRow,
    BFormGroup, BFormInput, BFormFile,
    BButton, BButtonGroup,
    MaterialDetailCard, MateriUploadInput, MaterialReader,
    AppCollapse, AppCollapseItem,
    BListGroup, BListGroupItem,
    BBadge,
  },
  data(){
    return{
      breadcrumbs: [],
      materialdata: {
        id: null,
        name: '',
        dept: null,
        level: null,
        data: null,
        desc: null,
        slug: null,
        m_id: null,
        w_materi: null,
      },
      stream: 'http://'+process.env.VUE_APP_API_ENDPOINT+'/storage/ViewerJS/#',
      datafile: null,
      qstlist: [],
    }
  },
  methods: {
    setbreadcrumb(title){
      this.breadcrumbs = [
        { text: 'OKM', to: { name: 'apps-elearning-dashboard'} },
        { text: 'Materi', to: { name: 'apps-elearning-materials'} },
        { text: title, active: true },
      ]
      called.$emit('appbreadcrumbcustomBreadCrumbs', this.breadcrumbs)
    },
    loaddata(){
      called.$emit('showloading', {show: true, text: 'Sedang memuat...'})
      http
      .get('okm/material/'+this.$route.params.id)
      .then((res) => {
        // console.log(res.data)
        this.materialdata = {
          id: res.data.id,
          name: res.data.m_title,
          dept: res.data.dept_id,
          level: res.data.m_level,
          desc: res.data.m_desc,
          slug: res.data.slug,
          w_materi: res.data.m_duration,
        }
        if(res.data.materialfile){
          this.materialdata.data = this.stream+res.data.materialfile.filematerial
          this.materialdata.m_id = res.data.materialfile.id
        }
        this.qstlist = res.data.questionslist
        this.setbreadcrumb(res.data.m_title)
        called.$emit('hideloading')
      })
      .catch((e) => {
        console.error(e)
      })
    },
    editdetailmaterial(){
      this.$router.push({ name: 'apps-elearning-materials-edit', params: {id: this.materialdata.id} })
    },
    addmaterial(){
      called.$emit('showloading', {show: true, text: 'Sedang proses...'})
      const addfile = new FormData
      addfile.append('id', this.materialdata.id)
      addfile.append('filematerial', this.datafile)
      http
      .post('okm/material/file/'+this.materialdata.id, addfile, {
        headers: { 'content-type': 'multipart/form-data' } 
      })
      .then((res) => {
        this.datafile = null
        this.$toast({
          component: ToastificationContent,
          props: {
            icon: 'CheckCircleIcon',
            variant: 'success',
            title: res.data.message
          },
        },
        { position: 'top-right' })
        this.loaddata()
        called.$emit('hideloading')
      })
      .catch((e) => {
        console.error(e)
      })
    },
    deletefilematerial(id){
      this.$swal({
        text: 'Yakin akan dihapus?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        customClass: {
          confirmButton: 'btn btn-outline-primary',
          cancelButton: 'btn btn-danger ml-1',
        },
        buttonsStyling: false,
      }).then(result => {
        if(result.value){
          called.$emit('showloading', {show: true, text: 'Sedang proses...'})
          http
          .delete('okm/material/file/'+id)
          .then((res) => {
            this.$toast({
            component: ToastificationContent,
            props: { icon: 'CheckCircleIcon', variant: 'success', title: res.data.message },
            }, 
            { position: 'top-right' })
            this.loaddata()
            called.$emit('hideloading')
          })
          .catch((e) => {
            console.error(e)
          })
        }
      })
    },
    handlefile(options){
      this.datafile = options.file
    },
    handledeletefile(){
      this.datafile = null
    },
  },
  mounted(){
    this.loaddata()
  },
  computed: {
    deptname(){
      let input = this.materialdata.dept
      let scoped = this.deptList.filter((item) => { return item.id === input })
      if(scoped.length !== 0){
        return scoped[0].name
      }
    }
  }
}
</script>

<style>

</style>