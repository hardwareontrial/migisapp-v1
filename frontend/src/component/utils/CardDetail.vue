<template>
  <div>
    <b-card class="card" :style="`background-color: ${backgroundCardColor};`">
      <b-card-title>
        <b-row>
          <b-col
            cols="6"
            class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
            <slot name="cstmtitledetail"></slot>
          </b-col>
          <b-col
            cols="6"
            class="d-flex align-items-center justify-content-end">
            <slot name="cstmbtndetail"></slot>
          </b-col>
        </b-row>
      </b-card-title>
      <b-card-text>
        <slot name="dataform"></slot>
        <slot name="datadetail"></slot>
      </b-card-text>
    </b-card>
  </div>
</template>

<script>
import {
  BCard, BCardText, BCardTitle,
  BCol, BRow,
  BFormGroup, BFormInput,
  BBadge,
  BButton,
} from 'bootstrap-vue'

export default {
  props: {
    backgroundCardColor: {
      type: String,
      default: null,
    }
  },
  components: {
    BCard, BCardText, BCardTitle,
    BCol, BRow,
    BFormGroup, BFormInput,
    BBadge,
    BButton,
  },
}
</script>

<style>

</style>