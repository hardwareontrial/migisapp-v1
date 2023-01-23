<template>
  <div>
    <strong :class="timeLeft <= '30' ? 'text-danger h4':'text-primary h4'"> {{ timeLeftString }} </strong>
  </div>
</template>

<script>
export default {
  props: {
    timeMinute: {
      type: Number,
      required: true
    },
  },
  data(){
    return{
      timeLeft: 0,
      // timeLeft: this.timeMinute *60,
      timeLeftString: '',
      timer: null,
    }
  },
  watch: {
    timeLeft: function(val){
      const timeZero = this.$moment("1900-01-01 00:00:00")
      this.timeLeftString = timeZero.add(val, 'seconds').format('HH:mm:ss')
    }
  },
  mounted(){
    // this.timeLeft = 10 // in seconds
    this.timeLeft = this.timeMinute
    this.timer = setInterval(() => {
      if(this.timeLeft <= 0){
        clearInterval(this.timer)
        // alert('Done')
        this.$emit('timeisup')
      }else{
        this.timeLeft--
        this.$emit('update:timeleft', this.timeLeft )
      }
    }, 1000)
    // this.getdifferent()
  },
  methods: {
    getdifferent(){ // in seconds
      var now = this.$moment(new Date()).format('YYYY-MM-DD HH:mm:ss')
      var end = '2022-11-16 12:00:00'
      var x = this.$moment(end).diff(this.$moment(now))
      var y = this.$moment.duration(x).asSeconds()
      this.timeLeft = y
    }
  }
}
</script>

<style>

</style>
