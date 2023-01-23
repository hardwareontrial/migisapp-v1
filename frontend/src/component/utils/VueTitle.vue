<script>
export default {
  name: 'vue-title',
  props: {
    'title': {
      type: String
    }
  },
  watch: {
    'title': {
      immediate: true,
      handler(){
        document.title = this.title
      }
    }
  },
  render(){

  },
}
</script>