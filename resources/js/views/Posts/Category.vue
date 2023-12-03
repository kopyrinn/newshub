<template>
    <div class="row">
        <div class="col-lg-7">
            <PullRefresh v-model="loadingPull" @refresh="pullRefresh" :pulling-text="$t('Pull down to refresh...')" :loosing-text="$t('Release to refresh...')" :loading-text="$t('Loading...')" success-text="">
                <div v-if="loading && !posts.length">
                    <CardSkeleton v-for="n in 4"/>
                </div>
                <div v-else-if="posts.length">
                    <div v-for="(item, index) in posts" :key="item.uuid">
                        <intersection-observer
                            v-if="index == posts.length - 9 && cursor && !loading"
                            :sentinal-name="'posts' + cursor"
                            @on-intersection-element="fetchData"
                        ></intersection-observer>

                        <Card :item="item" :is="item.uuid"/>

                        <RecommendedVacancies v-if="index === 1"/>
                        <RecommendedPosts v-if="index === 3 && $root.config.lastEvents.length" :items="$root.config.lastEvents" :title="$t('Events')"  :isEvent="true"/>
                        <RecommendedPosts v-if="index === 6 && $root.config.lastArticles.length" :items="$root.config.lastArticles" :title="$t('Articles')"/>
                        <Banner v-if="index && (index === 2 || index % 6 === 0)" location="category.view" class="mb-6"/>
                    </div>

                    <intersection-observer
                        v-if="cursor && !loading"
                        :sentinal-name="'posts' + cursor"
                        @on-intersection-element="fetchData"
                    ></intersection-observer>
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
            <div class="card mb-5">
                <div class="card-body">
                    <app-link v-for="item in $root.config.rubrics" :to="{name: 'category', params: {slug, rubric: item.slug}}" class="btn btn-light btn-active-light-primary btn-sm fs-xs me-2 mb-2" :class="{'active': rubric && item.slug == rubric}">{{ item.name }}</app-link>
                </div>
            </div>
            <Sidebar/>
            <SchemaOrgWebPage type="CollectionPage" :name="$root.meta.title" />
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import Card from "@/components/Post/Card.vue"
import RecommendedPosts from "@/components/Post/RecommendedPosts.vue"
import Banner from "@/components/Ad/Banner.vue"
import RecommendedVacancies from "@/components/Vacancy/RecommendedVacancies.vue"
import CardSkeleton from "@/components/Post/CardSkeleton.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"
import Sidebar from "@/components/Sidebar.vue"
import PullRefresh from '@/components/PullRefresh.vue'

export default defineComponent({
    name: "Category",
    components: {
        Card,
        RecommendedPosts,
        Banner,
        RecommendedVacancies,
        CardSkeleton,
        IntersectionObserver,
        Sidebar,
        PullRefresh,
    },
    data() {
        return {
            slug: this.$route.params.slug || 'news',
            rubric: this.$route.params.rubric,
            loading: true,
            loadingPull: false,
            posts: [],
            cursor: null,
            // title: null
        }
    },
    head() {
        return {
            title: this.title
        }
    },
    computed: {
        title() {
            return this.$store.getters.getTitle
        },
    },
    async serverPrefetch() {
        const category = this.$root.config.categories.find((item) => item.slug == this.slug)

        if (category) {
            this.$store.commit('setTitle', category.name)
            // console.log('prefetch title')
        }

        this.loading = false

        await this.$api('category', false, {
            method: 'POST',
            data: {
                cursor: this.cursor,
                slug: this.slug,
                rubric: this.rubric,
            }
        })
        .then(({data}) => {
            if (!data.ok) return

            if (!this.posts.length) {
                this.posts = data.posts.data
            } else {
                data.posts.data.map((item) => this.posts.push(item))
            }

            this.cursor = data.posts.next_cursor
        })
        .catch((e) => {})
    },
    created() {
        this.init()

        if (!import.meta.env.SSR) {
            this.fetchData()
        }
    },
    watch: {
        $route(from, to) {
            if (from.name == 'category' && to.name == from.name && from.params.slug != to.params.slug) {
                this.reset()
            }
        }
    },
    methods: {
        init() {
            const category = this.$root.config.categories.find((item) => item.slug == this.slug)

            if (category) {
                this.$store.commit('setTitle', category.name)
            }
        },
        async pullRefresh() {
            this.loadingPull = true
            this.posts = []
            this.cursor = null
            await this.fetchData()
            this.loadingPull = false
        },
        fetchData() {
            // if (this.loading) return
            this.loading = true

            return this.$api('category', false, {
                method: 'POST',
                data: {
                    cursor: this.cursor,
                    slug: this.slug,
                    rubric: this.rubric,
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
            .catch((e) => {
                this.loading = false
            })
        },
        reset() {
            this.slug = this.$route.params.slug || 'news'
            this.loading = true
            this.posts = []
            this.cursor = null

            this.init()
            this.fetchData()
        },
    },
});
</script>