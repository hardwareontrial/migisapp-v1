<template>
  <div class="sidebar-left">
    <div class="sidebar">
      <div class="sidebar-content todo-sidebar">
        <div class="todo-app-menu">
          <div class="add-task">
            <b-button
              type="button"
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              block
              @click="openform(); $emit('close-left-sidebar')"
              v-if="$can('create', 'AppTodo')">
              Buat Tugas
            </b-button>
          </div>
          <vue-perfect-scrollbar
            :settings="perfectScrollbarSettings"
            class="sidebar-menu-list scroll-area">
            <b-list-group class="list-group-filters">
              <b-list-group-item
                v-for="filter in taskFilters"
                :key="filter.title + $route.path"
                :to="filter.route"
                :active="isDynamicRouteActive(filter.route)"
                @click="$emit('close-left-sidebar')">
                <feather-icon
                  :icon="filter.icon"
                  size="18"
                  class="mr-75"/>
                <span class="align-text-bottom line-height-1">{{ filter.title }}</span>
              </b-list-group-item>
            </b-list-group>
            <div class="mt-3 px-2 d-flex justify-content-between">
              <h6 class="section-label mb-1">
                Tags
              </h6>
              <feather-icon icon="BookmarkIcon" />
            </div>
            <b-list-group class="list-group-labels">
              <b-list-group-item
                v-for="tag in taskTags"
                :key="tag.title"
                :to="tag.route"
                :active="isDynamicRouteActive(tag.route)"
                @click="$emit('close-left-sidebar')">
                <span class="bullet bullet-sm mr-1" :class="`bullet-${tag.color}`"/>
                <span>{{ tag.title}}</span>
              </b-list-group-item>
            </b-list-group>
          </vue-perfect-scrollbar>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { BButton, BListGroup, BListGroupItem } from 'bootstrap-vue'
import { isDynamicRouteActive } from '@core/utils/utils'
import Ripple from 'vue-ripple-directive'

export default {
  directives: { Ripple },
  components: {
    BButton,
    BListGroup,
    BListGroupItem,
    VuePerfectScrollbar,
  },
  props: {
    taskTags: {
      type: Array,
      required: true
    }
  },
  data(){
    return{
      perfectScrollbarSettings: { maxScrollbarLength: 60 },
      isDynamicRouteActive,
      taskFilters: [
        { title: 'Tugas', icon: 'MailIcon', route: { name: 'apps-todo' } },
        { title: 'Penting', icon: 'StarIcon', route: { name: 'apps-todo-filter', params: { filter: 'important' } } },
        { title: 'Selesai', icon: 'CheckIcon', route: { name: 'apps-todo-filter', params: { filter: 'completed' } } },
        { title: 'Di-Hapus', icon: 'TrashIcon', route: { name: 'apps-todo-filter', params: { filter: 'deleted' } } },
      ],
    }
  },
  methods: {
    openform(){
      called.$emit('todoformvisible')
    }
  }
}
</script>

<style>

</style>