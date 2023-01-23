<template>
  <div>
    <iframe
      :src="file" 
      width="100%"
      height="600px"
      allowfullscreen>
    </iframe>
  </div>
</template>

<script>
import {
  BCard,
} from 'bootstrap-vue'

export default {
  components: {
    BCard,
  },
  props: {
    file: {
      type: String,
    }
  },
}
</script>

<style>

</style>