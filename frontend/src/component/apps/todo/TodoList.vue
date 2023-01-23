<template>
  <div style="height: inherit;">
    
    <div
      class="body-content-overlay"
      :class="{'show': mqShallShowLeftSidebar}"
      @click="mqShallShowLeftSidebar = false"
    />
    
    <div class="todo-app-list">
      <div class="app-fixed-search d-flex align-items-center">
        
        <div class="sidebar-toggle d-block d-lg-none ml-1">
          <feather-icon
            icon="MenuIcon"
            size="21"
            class="cursor-pointer"
            @click="mqShallShowLeftSidebar = true"/>
        </div>
        
        <div class="d-flex align-content-center justify-content-between w-100">
          <b-input-group class="input-group-merge">
            <b-input-group-prepend is-text>
              <feather-icon
                icon="SearchIcon"
                class="text-muted"
              />
            </b-input-group-prepend>
            <b-form-input
              value=""
              placeholder="Cari Tugas"
              v-model="qsearch"
            />
          </b-input-group>
        </div>
        
        <div class="dropdown">
          <b-dropdown
            variant="link"
            no-caret
            toggle-class="p-0 mr-1"
            right>
            <template #button-content>
              <feather-icon
                icon="MoreVerticalIcon"
                size="16"
                class="align-middle text-body"/>
            </template>
            <!-- <b-dropdown-item>
              Reset Sort
            </b-dropdown-item>
            <b-dropdown-item>
              Sort A-Z
            </b-dropdown-item>
            <b-dropdown-item>
              Sort Z-A
            </b-dropdown-item>
            <b-dropdown-item>
              Sort Assignee
            </b-dropdown-item> -->
            <b-dropdown-item disabled>
              Sort Due Date
            </b-dropdown-item>
          </b-dropdown>
        </div>
      </div>

      
        
        <vue-perfect-scrollbar
          :settings="perfectScrollbarSettings"
          class="todo-task-list-wrapper list-group scroll-area">
          <draggable
            v-model="tasks"
            handle=".draggable-task-handle"
            tag="ul"
            class="todo-task-list media-list">
            <li
              v-for="task in tasks"
              :key="task.id"
              class="todo-item"
              :class="{ 'completed': !!+task.isComplete }"
              @click="handleTaskClick(task)">
              <feather-icon
                icon="MoreVerticalIcon"
                class="draggable-task-handle d-inline"
              />
              <div class="todo-title-wrapper">
                <div class="todo-title-area">
                  <div class="title-wrapper">
                    <b-form-checkbox
                      :checked="!!+task.isComplete"
                      @click.native.stop 
                      @change="handleMarkComplete(task.id, !!+task.isComplete)"/>
                    <span class="todo-title">{{ task.title }}</span>
                  </div>
                </div>
                <div class="todo-item-action">
                  <div class="badge-wrapper mr-1">
                    <b-badge
                      v-for="tag in task.tags"
                      :key="tag"
                      :variant="`${resolveTagVariant(tag)}`"
                      pill
                      class="text-capitalize badge-glow">
                      {{ tag }}
                    </b-badge>
                  </div>
                  <small class="text-nowrap text-muted mr-1">{{ $moment(task.dueDate).format('DD MMM') }}</small>
                  <b-avatar
                    size="32"
                    :src="task.requestorname.avatar ? task.requestornameavatarlink : null"
                    :text="avatarText(task.requestorname.name)"
                    variant="light-primary"/>
                </div>
              </div>
            </li>
          </draggable>
        </vue-perfect-scrollbar>

        <popup-loading :showmodal="showloading"/>

      

    </div>

    <todo-form 
      @mark-delete="handleMarkDelete"/>
    
    <portal to="content-renderer-sidebar-left">
      <todo-left-sidebar
        :taskTags="taskTags"
        :class="{'show': mqShallShowLeftSidebar}"
        @close-left-sidebar="mqShallShowLeftSidebar = false"/>
    </portal>
    
  </div>
</template>

<script>
import {
  BFormInput, BInputGroup, BInputGroupPrepend, BFormCheckbox,
  BDropdown, BDropdownItem,
  BBadge, 
  BAvatar,
  BOverlay,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import draggable from 'vuedraggable'
import { useResponsiveAppLeftSidebarVisibility } from '@core/comp-functions/ui/app'
import TodoLeftSidebar from './TodoSidebar.vue'
import TodoForm from './TodoForm.vue'
import http from '@/customs/axios'
import { avatarText } from '@core/utils/filter'

export default {
  components: {
    BFormInput, BInputGroup, BInputGroupPrepend, BFormCheckbox,
    BDropdown, BDropdownItem,
    BBadge, 
    BAvatar,
    BOverlay,
    draggable,
    VuePerfectScrollbar,
    TodoLeftSidebar,
    TodoForm,
    PopupLoading: () => import('@/component/utils/PopupGetData.vue'),
  },
  data(){
    return{
      tasks: [],
      taskuserid: [],
      sortOptions: [
        'latest',
        'title-asc',
        'title-desc',
        'assignee',
        'due-date',
      ],
      perfectScrollbarSettings: { maxScrollbarLength: 150 },
      isTaskHandlerSidebarActive: false,
      taskTags: [
        { title: 'Low', color: 'success', route: {name: 'apps-todo-tag', params: { tag: 'low' }} },
        { title: 'Medium', color: 'warning', route: {name: 'apps-todo-tag', params: { tag: 'medium' }} },
        { title: 'High', color: 'danger', route: {name: 'apps-todo-tag', params: { tag: 'high' }} },
        { title: 'Update', color: 'info', route: {name: 'apps-todo-tag', params: { tag: 'update' }} },
      ],
      mqShallShowLeftSidebar: useResponsiveAppLeftSidebarVisibility().mqShallShowLeftSidebar.value,
      qsearch: '',
      showloading: false,
      avatarText,
    }
  },
  methods: {
    resolveTagVariant(val){
      if (val === 'low') return 'success'
      if (val === 'medium') return 'warning'
      if (val === 'high') return 'danger'
      if (val === 'update') return 'info'
      return 'secondary'
    },
    fetchData(){
      const data = JSON.parse(localStorage.getItem('userdata'))
      const isAdmin = !!data.admin
      const userid = data.detailuser.id

      
      this.showloading = true
      // console.log(this.$router.currentRoute.params.filter)
      http
      .get('todo/data', {
        params: {
          filter: this.$router.currentRoute.params.filter,
          tag: this.$router.currentRoute.params.tag,
          search: this.qsearch
        }
      })
      .then((res) => {
        // console.log(res)
        this.taskuserid.push(userid)
        if(!isAdmin) {
          const child = data.detail.position.children
          this.nestedobject(child)

        const result = res.data.filter((item) => 
            this.taskuserid.includes(item.assignee_id) || 
            this.taskuserid.includes(item.requestor_id) || 
            this.taskuserid.includes(item.created_by))
          this.tasks = result
          // console.log(result)
        }else{
          this.tasks = res.data
        }
        this.showloading = false
      })
      .catch((e) => { console.error(e) })
    },
    handleTaskClick(val){
      called.$emit('todoformEditTask', val)
    },
    handleMarkComplete(id, status){
      http
      .patch('todo/completed/'+id, { newstatus: !status })
      .then((res) => {
        this.fetchData()
      })
      .catch((e) => {
        console.error(e)
      })
    },
    handleMarkDelete(id, status){
      http
      .patch('todo/deleted/'+id, { newstatus: !status })
      .then((res) => {
        this.fetchData()
      })
      .catch((e) => {
        console.error(e)
      })
    },
    nestedobject(val){
      for (let i = 0; i < val.length; ++i){
        if(val[i].user){
          this.taskuserid.push(val[i].user.id)
        }
        if(val[i].children){
          const x = val[i].children;
          this.nestedobject(x)
        }
      }
    },
  },
  mounted(){
    this.fetchData()
    called.$on('todolistFetchData', () => { this.fetchData() })
  },
  watch: {
    '$route.params': {
      handler(n){
        this.fetchData()
      }
    },
    'qsearch': {
      handler(n){
        setTimeout(() => this.fetchData(), 1200)
      }
    }
  },
}
</script>

<style lang="scss" scoped>
  .draggable-task-handle {
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    visibility: hidden;
    cursor: move;

    .todo-task-list .todo-item:hover & {
      visibility: visible;
    }
  }
</style>

<style lang="scss">
  @import "~@core/scss/base/pages/app-todo.scss";
</style>