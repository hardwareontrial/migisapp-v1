<template>
  <div>
    <b-modal
      v-model="show"
      :size="size"
      no-close-on-backdrop
      no-close-on-esc
      :title="title"
      :hide-footer="hidefooter"
      hide-header-close>
      <slot name="modalbody"></slot>
      <div slot="modal-footer">
        <slot name="modalfooter"></slot>
      </div>
    </b-modal>
  </div>
</template>

<script>
import {
  BModal,
  BButton,
} from 'bootstrap-vue'

export default {
  components: {
    BModal,
    BButton,
  },
  props: {
    'show': {
      default: false,
      type: Boolean,
    },
    'size': {
      default: 'lg',
      type: String,
    },
    'title':{
      default: 'Modal',
      type: String,
    },
    'hidefooter': {
      default: false,
      type: Boolean,
    }
  },
}
</script>

<style>

</style>