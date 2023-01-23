<template>
  <b-card>
    <b-media no-body>
      <b-media-aside>
        <b-link>
          <b-img
            ref="previewEl" 
            height="80"
            rounded>
          </b-img>
        </b-link>
      </b-media-aside>
      <b-media-body
        class="mt-75 ml-75">
        <b-button
          variant="outline-primary"
          size="sm"
          class="mb-75 mr-75">
          Upload
        </b-button>
        <b-form-file
          ref="refInputEl"
          :hidden="true"
          accept=".jpg, .png"
          plain>
        </b-form-file>
        <b-button
          variant="danger"
          size="sm"
          class="mr-75 mb-75">
          Hapus
        </b-button>
        <b-card-text>Tipe file JPG, PNG. Ukuran maksimal 1MB.</b-card-text>
      </b-media-body>
    </b-media>
    <b-row>
      <b-col 
        cols="12" sm="6">
        <b-form-group
          label="Name"
          label-for="account-name">
          <b-form-input
            name="name"
            placeholder="Name"
          />
        </b-form-group>
      </b-col>
      <b-col 
        cols="12" sm="6">
        <b-form-group
          label="Posisi"
          label-for="account-position-name">
          <b-form-input
            name="name"
            placeholder="Name"
          />
        </b-form-group>
      </b-col>
      <b-col 
        cols="12" sm="6">
        <b-form-group
          label="Departemen"
          label-for="account-dept-name">
          <b-form-input
            name="name"
            placeholder="Name"
          />
        </b-form-group>
      </b-col>
    </b-row>
  </b-card>
</template>

<script>
import {
  BFormFile, BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BAlert, BCard, BCardText, BMedia, BMediaAside, BMediaBody, BLink, BImg,
} from 'bootstrap-vue'
import { useInputImageRenderer } from '@core/comp-functions/forms/form-utils'

export default {
  components: {
    BFormFile, BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BAlert, BCard, BCardText, BMedia, BMediaAside, BMediaBody, BLink, BImg,
  }
}
</script>

<style>

</style>