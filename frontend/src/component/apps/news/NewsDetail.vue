<template>
  <content-with-sidebar
    class="cws-container cws-sidebar-right blog-wrapper">
    <div class="blog-detail-wrapper">
      <!-- {{ blog }} -->
      <b-row>
        <!-- blogs -->
        <b-col cols="12">
          <b-card
            :img-src="blog.coverimage"
            img-top
            img-alt="Blog Detail Pic"
            :title="blog.title"
          >
            <b-media no-body>
              <b-media-aside
                vertical-align="center"
                class="mr-50"
              >
                <b-avatar
                  href="javascript:void(0)"
                  size="24"
                />
              </b-media-aside>
              <b-media-body>
                <small class="text-muted mr-50">oleh</small>
                <small>
                  <b-link class="text-body">{{ creator }}</b-link>
                </small>
                <span class="text-muted ml-75 mr-50">|</span>
                <small class="text-muted">{{ $moment(blog.created_at).format('DD MMMM YYYY') }}</small>
              </b-media-body>
            </b-media>
            <div class="my-1 py-25">
              <b-link
                v-for="cat in blog.category"
                :key="cat"
              >
                <b-badge
                  pill
                  class="mr-75"
                  variant="light-primary"
                >
                  {{ cat }}
                </b-badge>
              </b-link>
            </div>
            <!-- eslint-disable vue/no-v-html -->
            <div
              class="blog-content"
              v-html="blog.content"
            />
            <hr class="my-2">
          </b-card>
        </b-col>
        <!--/ blogs -->
      </b-row>
      <!--/ blogs -->
    </div>
    <div
      slot="sidebar"
      class="blog-sidebar py-2 py-lg-0">
      <b-form-group class="blog-search">
        <b-input-group class="input-group-merge">
          <b-form-input
            id="search-input"
            placeholder="Search here"/>
          <b-input-group-append
            class="cursor-pointer"
            is-text>
            <feather-icon
              icon="SearchIcon"
            />
          </b-input-group-append>
        </b-input-group>
      </b-form-group>
      <div class="blog-categories mt-3">
        <h6 class="section-label mb-1">
          Categories
        </h6>

        <div
          class="d-flex justify-content-start align-items-center mb-75"
          v-for="(cat, i) in listCategories"
          :key="i">
          <b-link>
            <b-avatar
              rounded
              size="22"
              variant="light-primary"
              class="mr-75"
            >
              <feather-icon
                size="16"
                icon="TagIcon"
              />
            </b-avatar>
          </b-link>
          <b-link>
            <div class="blog-category-title text-body">
              {{ cat.name }}
            </div>
          </b-link>
        </div>
      </div>

    </div>
  </content-with-sidebar>
</template>

<script>
import store from '@/store'
import http from '@/customs/axios'
import {
  BFormInput, BMedia, BAvatar, BMediaAside, BMediaBody, BImg, BLink, BFormGroup, BInputGroup, BInputGroupAppend,
  BCard, BRow, BCol, BBadge, BCardText, BDropdown, BDropdownItem, BForm, BFormTextarea, BFormCheckbox, BButton,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ContentWithSidebar from '@core/layouts/components/content-with-sidebar/ContentWithSidebar.vue'

export default {
  components: {
    BFormInput, BMedia, BAvatar, BMediaAside, BMediaBody, BImg, BLink, BFormGroup, BInputGroup, BInputGroupAppend,
    BCard, BRow, BCol, BBadge, BCardText, BDropdown, BDropdownItem, BForm, BFormTextarea, BFormCheckbox, BButton,
    ContentWithSidebar,
  },
  directives: { Ripple },
  data(){
    return{
      blog: {},
      breadcrumbs: [],
      creator: '',
      listCategories: [],
    }
  },
  methods: {
    getDetail(){
      const slug = this.$route.params.id
      http
      .get('/news/detail/'+slug)
      .then((res) => {
        this.blog = res.data
        this.breadcrumbs = [
          { text: 'News', to: { name: 'apps-news'} },
          { text: this.blog.title, active: true }
        ]
        called.$emit('appbreadcrumbcustomBreadCrumbs', this.breadcrumbs)
        this.creator = this.blog.creator.name
      })
      .catch((e) => { console.log(e) })
    },
    getCategory(){
      http
      .get('news/admin/list-categories')
      .then((res) => {
        // console.log(res)
        this.listCategories = res.data
      })
      .catch((e) => { console.log(e) })
    },
  },
  beforeCreate(){
    const layout = this.$route.meta.dlayout
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', layout)
  },
  created(){
    this.getDetail()
    this.getCategory()
  },
}
</script>

<style>

</style>