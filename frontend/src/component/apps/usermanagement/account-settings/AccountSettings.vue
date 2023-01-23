<template>
  <div>
    <b-tabs
      vertical
      content-class="col-12 col-md-9 mt-1 mt-md-0"
      pills
      nav-wrapper-class="col-md-3 col-12"
      nav-class="nav-left">
      <b-tab active>
        <template #title>
          <feather-icon
            icon="UserIcon"
            size="18"
            class="mr-50"
          />
          <span class="font-weight-bold">General</span>
        </template>
        <account-setting-general />
      </b-tab>
    </b-tabs>
  </div>
</template>

<script>
import { BTabs, BTab } from 'bootstrap-vue'
import AccountSettingGeneral from './AccountSettingGeneral.vue'

export default {
  components: {
    BTabs, BTab,
    AccountSettingGeneral,
  },
  data(){
    return{
      datauser: null,
      isadmin: false,
    }
  }
}
</script>

<style>

</style>