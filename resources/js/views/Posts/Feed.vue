<template>
    <div class="row">
        <div class="col-lg-7">
            <PullRefresh v-model="loadingPull" @refresh="pullRefresh" :pulling-text="$t('Pull down to refresh...')" :loosing-text="$t('Release to refresh...')" :loading-text="$t('Loading...')" success-text="">
                <div v-if="loading && !posts.length">
                    <CardSkeleton v-for="n in 4"/>
                </div>  
                <div v-else-if="posts.length">
                    <div v-for="(item, index) in posts" :key="item.uuid">
                        <Card :item="item" :is="item.uuid"/>

                        <RecommendedVacancies v-if="index === 1"/>
                        <RecommendedPosts v-if="index === 3 && $root.config.lastEvents.length" :items="$root.config.lastEvents" :title="$t('Events')"  :isEvent="true"/>
                        <RecommendedPosts v-if="index === 6 && $root.config.lastArticles.length" :items="$root.config.lastArticles" :title="$t('Articles')"/>
                        <Banner v-if="index && (index === 2 || index % 6 === 0)" location="category.view" class="mb-6"/>

                        <intersection-observer
                            v-if="index == posts.length - 9 && cursor"
                            :sentinal-name="'posts' + cursor"
                            @on-intersection-element="fetchData"
                        ></intersection-observer>
                    </div>
                </div>
                <div v-else class="card mb-5">
                    <div class="card-body text-center">
                        <!--begin::Icon-->
                        <div class="pt-10 pb-10">
                            <i class="ki-duotone ki-search-list fs-4x opacity-50"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        </div>
                        <div class="pb-15 fw-semibold">
                            <h3 class="text-gray-600 fs-5 mb-2">{{ $t('No posts yet')}}</h3>
                            <div class="text-muted fs-7">{{ $t('Please try again with a different query') }}</div>
                        </div>
                    </div>
                </div>
            </PullRefresh>
        </div>
        <div class="col-lg-5">
            <Sidebar/>
            <SchemaOrgWebPage type="CollectionPage" :name="$root.meta.title" />
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import VLazyImage from "v-lazy-image"
import { Carousel, Slide } from 'vue3-carousel'
import Card from "@/components/Post/Card.vue"
import RecommendedPosts from "@/components/Post/RecommendedPosts.vue"
import Banner from "@/components/Ad/Banner.vue"
import RecommendedVacancies from "@/components/Vacancy/RecommendedVacancies.vue"
import CardSkeleton from "@/components/Post/CardSkeleton.vue"
import Sidebar from "@/components/Sidebar.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"
import PullRefresh from '@/components/PullRefresh.vue'

export default defineComponent({
    name: "Index",
    components: {
        VLazyImage,
        Carousel,
        Slide,
        CardSkeleton,
        Card,
        RecommendedPosts,
        Banner,
        RecommendedVacancies,
        Sidebar,
        IntersectionObserver,
        PullRefresh,
    },
    data() {
        return {
            currentSlide: null,
            loading: false,
            loadingPull: false,
            posts: [],
            cursor: null,
        }
    },
    head() {
        return {
            title: this.$store.state.meta.title
        }
    },
    created() {
        if (this.$route.meta.noSsr && import.meta.env.SSR) return false

        this.fetchData()
    },
    methods: {
        async pullRefresh() {
            this.loadingPull = true
            this.posts = []
            this.cursor = null
            await this.fetchData()
            this.loadingPull = false
        },
        fetchData() {
            this.loading = true

            return this.$api('feed', true, {
                params: {
                    cursor: this.cursor
                }
            })
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                if (!this.posts.length) {
                    this.posts = data.posts.data
                } else {
                    data.posts.data.map((item) => this.posts.push(item))
                }

                this.cursor = data.posts.next_cursor
            })
        },
        reset() {
            this.posts = []
            this.cursor = null
            this.fetchData()
        },
    },
});
</script>