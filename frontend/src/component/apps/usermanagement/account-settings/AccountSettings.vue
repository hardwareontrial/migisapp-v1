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
      <b-tab active>
        <template #title>
          <feather-icon
            icon="LockIcon"
            size="18"
            class="mr-50"
          />
          <span class="font-weight-bold">Ganti Password</span>
        </template>
        <account-setting-password :user-data="userdata"/>
      </b-tab>
    </b-tabs>
  </div>
</template>

<script>
import { BTabs, BTab } from 'bootstrap-vue'
import AccountSettingGeneral from './AccountSettingGeneral.vue';
import AccountSettingPassword from "@/component/apps/usermanagement/account-settings/AccountSettingPassword";

export default {
  components: {
    BTabs, BTab,
    AccountSettingGeneral,
    AccountSettingPassword,
  },
  computed: {
    userdata(){
      return JSON.parse(localStorage.getItem('userdata'))
    }
  },
}
</script>

<style>

</style>
