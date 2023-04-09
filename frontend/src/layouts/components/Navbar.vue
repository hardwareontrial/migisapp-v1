<template>
  <div class="navbar-container d-flex content align-items-center">

    <!-- Nav Menu Toggler -->
    <ul class="nav navbar-nav d-xl-none">
      <li class="nav-item">
        <b-link
          class="nav-link"
          @click="toggleVerticalMenuActive"
        >
          <feather-icon
            icon="MenuIcon"
            size="21"
          />
        </b-link>
      </li>
    </ul>

    <!-- Left Col -->
    <div class="bookmark-wrapper align-items-center flex-grow-1 d-none d-lg-flex">
      <dark-Toggler class="d-none d-lg-block" />
    </div>

    <b-navbar-nav class="nav align-items-center ml-auto">
      <b-nav-item-dropdown
        right
        toggle-class="d-flex align-items-center dropdown-user-link"
        class="dropdown-user"
        v-if="!userdata">
        <template #button-content>
          <div class="d-sm-flex d-none user-nav">
            <span class="user-status"></span>
          </div>
          <b-avatar
            size="40"
            variant="light-secondary"
          />
        </template>
        <b-dropdown-item link-class="d-flex align-items-center" :to="{ name: 'login' }">
          <feather-icon
            size="16"
            icon="LogInIcon"
            class="mr-50"
          />
          <span>Login</span>
        </b-dropdown-item>

      </b-nav-item-dropdown>

      <b-nav-item-dropdown
        right
        toggle-class="d-flex align-items-center dropdown-user-link"
        class="dropdown-user"
        v-else>
        <template #button-content>
          <div class="d-sm-flex d-none user-nav">
            <p class="user-name font-weight-bolder mb-0">
              {{ userdata.detailuser.name }}
            </p>
            <span class="user-status">{{ userdata.detailuser.nik }} <span v-if="userdata.detailuser.position"> | {{ userdata.detailuser.position.name }} </span> </span>
          </div>
          <b-avatar
            size="40"
            variant="light-primary"
            badge
            :src="profilepict"
            class="badge-minimal"
            badge-variant="success"
          />
        </template>

        <b-dropdown-item 
          :to="{ name: 'apps-usermgt-accountsettings', params: {id: userdata.detailuser.id, nik: userdata.nik } }"
          link-class="d-flex align-items-center">
          <feather-icon
            size="16"
            icon="SettingsIcon"
            class="mr-50"
          />
          <span>Pengaturan</span>
        </b-dropdown-item>
        <b-dropdown-item 
          :to="{ name: 'apps-elearning-raport-nik', params: {id: userdata.detailuser.id, nik: userdata.nik} }"
          link-class="d-flex align-items-center">
          <feather-icon
            size="16"
            icon="BookIcon"
            class="mr-50"
          />
          <span>Raport</span>
        </b-dropdown-item>

        <b-dropdown-divider />

        <b-dropdown-item link-class="d-flex align-items-center" @click="signout()">
          <feather-icon
            size="16"
            icon="LogOutIcon"
            class="mr-50"
          />
          <span>Keluar</span>
        </b-dropdown-item>
      </b-nav-item-dropdown>
    </b-navbar-nav>
  </div>
</template>

<script>
import {
  BLink, BNavbarNav, BNavItemDropdown, BDropdownItem, BDropdownDivider, BAvatar,
} from 'bootstrap-vue'
import DarkToggler from '@core/layouts/components/app-navbar/components/DarkToggler.vue'
import http from '@/customs/axios'
import { initialAbility } from '@/libs/acl/config'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    BLink,
    BNavbarNav,
    BNavItemDropdown,
    BDropdownItem,
    BDropdownDivider,
    BAvatar,

    // Navbar Components
    DarkToggler,
  },
  props: {
    toggleVerticalMenuActive: {
      type: Function,
      default: () => {},
    },
  },
  computed: {
    userdata(){
      return JSON.parse(localStorage.getItem('userdata'))
    },
    profilepict(){
      if(this.userdata.detailuser.avatar){
        return this.userdata.detailuser.avatarlink
      }else{
        let pict = require('@/assets/images/user-avatar.png')
        return pict
      }
    }
  },
  methods: {
    signout(){
      http.post('user/auth/logout')
      .then((res) => {
        localStorage.removeItem('userdata')
        localStorage.removeItem('token')
        this.$ability.update(initialAbility)
        this.$router.push({ name: 'login' })
        this.$toast({
          component: ToastificationContent,
          props: { title: res.data.message, icon: 'CoffeeIcon', variant: 'success' }
        }, { position: 'top-right' })
      })
    }
  }
}
</script>
