<template>
  <div>
    <b-form-file
      v-model="file"
      @input="onFileUpload"
      :accept="acceptext"
      placeholder="Pilih..."
      type="file"
      ref="uploadfile"/>
    <b-link
      v-show="file"
      @click="delfileupload">
      <small class="text-danger">
        Hapus
      </small>
    </b-link>
  </div>
</template>

<script>
import {
  BFormFile,
  BLink,
} from 'bootstrap-vue'

export default {
  components: {
    BFormFile,
    BLink,
  },
  props: {
    'acceptext': {
      type: String,
      default: '.pdf',
    },
  },
  data(){
    return{
      detailfile: {
        nama: null,
        size: null,
        type: null
      },
      file: null
    }
  },
  methods: {
    onFileUpload(){
      let test = this.$refs.uploadfile.files[0]
      this.detailfile = {
        nama: test.name,
        size: test.size,
        type: test.type
      }
      this.$emit('sendfile', { detail: this.detailfile, file: test })
    },
    delfileupload(){
      this.detailfile = {
        nama: null,
        size: null,
        type: null
      }
      this.file = null
      this.$emit('deletefile')
    }
  }
}
</script>

<style>

</style>