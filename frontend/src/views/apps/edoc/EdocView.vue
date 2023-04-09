<template>
  <div>
    <button @click="increment">Count is: {{ count }}</button>
  </div>
</template>

<script>
import { ref, onMounted } from '@vue/composition-api'

export default {
  setup(){
    let count = ref(0);
    
    function increment(){
      count++;
    }

    onMounted(() => {
      console.log(`Initial count is ${count.value}.`);
    })

    return {
      increment,
      count,
    }
  }
}
</script>

<style>

</style>