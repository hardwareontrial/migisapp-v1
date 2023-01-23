<template>
  <div>
    <content-with-sidebar class="blog-wrapper">
      <b-row class="blog-list-wrapper">
        <b-col
          md="6"
          v-for="(blog, i) in items"
          :key="i">
          <b-card
            tag="article"
            no-body>
            <b-link 
              :to="{ name: 'apps-news-detail', params: { id: blog.slug } }">
              <b-img 
                :src="blog.coverimage ? blog.coverimage : img"
                class="card-img-top"/>
            </b-link>
            <b-card-body>
              <b-card-title>
                <b-link
                  :to="{ name: 'apps-news-detail', params: { id: blog.slug } }"
                  class="blog-title-truncate text-body-heading">
                  {{ blog.title }}
                </b-link>
              </b-card-title>
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
                  <small class="text-muted mr-50">by</small>
                  <small>
                    <b-link class="text-body">{{ blog.creator.name }}</b-link>
                  </small>
                  <span class="text-muted ml-75 mr-50">|</span>
                  <small class="text-muted">{{ $moment(blog.created_at).format('dddd, DD MMMM YYYY') }}</small>
                </b-media-body>
              </b-media>
              <div class="my-1 py-25">
                <b-link
                  v-for="(cat, index) in blog.category"
                  :key="index">
                  <b-badge
                    pill
                    class="mr-75"
                    variant="light-primary">
                    {{ cat }}
                  </b-badge>
                </b-link>
              </div>
              <b-card-text class="blog-content-truncate">
                <span v-html="blog.content"></span>
              </b-card-text>
              <hr>
              <div class="d-flex justify-content-between align-items-center">
                <b-link
                  :to="{ name: 'apps-news-detail', params: { id: blog.slug } }"
                  class="font-weight-bold">
                  <feather-icon icon="EyeIcon" style="margin-right: 10px;" />Lanjutkan 
                </b-link>
              </div>
            </b-card-body>
          </b-card>
        </b-col>
        
        <b-col cols="12">
          <b-pagination
           align="center"
           v-model="meta.currentpage"
           :total-rows="meta.total"
           :per-page="meta.per_page"
           @change="changePage">
          </b-pagination>
        </b-col>
      </b-row>

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
  </div>
</template>

<script>
import store from '@/store'
import {
  BRow, BCol, BCard, BFormInput, BCardText, BCardTitle, BMedia, BAvatar, BMediaAside, BMediaBody, BImg, BCardBody, BLink, BBadge, BFormGroup, BInputGroup, BInputGroupAppend, BPagination,
} from 'bootstrap-vue'
import { kFormatter } from '@core/utils/filter'
import ContentWithSidebar from '@core/layouts/components/content-with-sidebar/ContentWithSidebar.vue'
import http from '@/customs/axios'

export default {
  components: {
    BRow, BCol,
    BCard, BCardText, BCardBody, BCardTitle,
    BFormInput, BFormGroup, BInputGroup, BInputGroupAppend,
    BAvatar,
    BMedia, BMediaAside, BMediaBody,
    BLink,
    BBadge,
    BImg,
    BPagination,
    ContentWithSidebar,
  },
  data(){
    return{
      listCategories: [],
      items: [],
      meta: {
        currentpage: 1,
        total:'',
        per_page: ''
      },
      current_page: 1,
      per_page: 10,
      search: '',
      sortBy: 'created_at',
      sortByDesc: true,
      breadcrumbs: [],
    }
  },
  methods: {
    getCategory(){
      http
      .get('news/admin/list-categories')
      .then((res) => {
        // console.log(res)
        this.listCategories = res.data
      })
      .catch((e) => { console.log(e) })
    },
    changePage(val){
      this.current_page = val
      this.fetchData()
    },
    fetchData(){
      // let current_page = this.search == '' ? this.current_page : 1
      http
      .get('news/data', {
        params: {
          // page: current_page,
          page: this.current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      })
      .then((res)=> {
        // console.log(res)
        let receivedData = res.data.message
        this.items = receivedData.data
        this.meta = {
          total: receivedData.total,
          current_page: receivedData.current_page,
          per_page: receivedData.per_page,
          from: receivedData.from,
          to: receivedData.to
        }
      })
      .catch((e) => {
        console.error(e)
      })
    },
  },
  mounted(){
    this.fetchData()
    this.getCategory()
  },
  computed: {
    img(){
      return require('@/assets/images/slider/06.jpg')
    }
  },
  beforeCreate(){
    const layout = this.$route.meta.dlayout
    store.commit('appConfig/UPDATE_LAYOUT_TYPE', layout)
  }
}
</script>

<style lang="scss">
  @import '@core/scss/vue/pages/page-blog.scss';
</style>