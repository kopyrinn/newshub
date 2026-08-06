<template>
    <div class="" :class="{'app-container container-xxl': $root.isXxxl}">
        <div class="d-flex flex-wrap flex-xl-nowrap">
            <Slider/>
            <div class="d-flex flex-wrap w-auto flex-grow-1">
                <div v-for="(item, index) in $root.config.postFeatured" class="w-xxl-50 mw-xxl-50 w-xl-100 mw-xl-100 w-sm-50 mw-sm-50 w-100 mw-100 featured-card" :key="item.slug">
                    <div  class="h-200px mw-100 w-100 position-relative">
                        <img class="object-fit-cover object-position-center h-100 w-100" :src="$storage(item.image_sm)"/>
                        <div class="position-absolute h-100 w-100 bg-black bg-opacity-50 top-0 bottom-0 text-start">
                            <div class="h-100 d-flex flex-column justify-content-end p-5">
                                <div class="fs-4 fw-bold text-white mb-6 text-truncate-2">{{ item.title }}</div>
                                <div class="fw-semibold text-white fs-6 mb-8 opacity-75 text-truncate-2">{{ item.summary }}</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <app-link :to="{name: 'post', params: {slug: item.slug}}"  class="btn btn-primary py-3 px-4 fs-7 lh-1" style="background: rgba(255, 255, 255, 0.2)">{{ $t('Read more') }}</app-link>
                                    <span class="badge badge-light-primary py-3 px-4 fs-7">{{ $t('News') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="app-toolbar">
            <div class="app-container container-fluid d-flex align-items-stretch ">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                    <li v-for="item in $root.config.categories" class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-5 me-lg-8 pt-2 pb-3 pt-lg-4 pb-lg-5" href="" @click.prevent="tab = item.slug, reset()" :class="{'active': tab == item.slug}">{{ item.name }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="app-content flex-column-fluid">
        <div class="app-container container-xxl">
            <div class="row">
                <div class="col-lg-7">
                    <transition name="fade" mode="out-in" appear  :key="tab">
                        <div>
                            <div v-if="loading && !posts.length">
                                <CardSkeleton v-for="n in 5"/>
                            </div>  
                            <div v-else>
                                <div v-for="(item, index) in posts" :key="item.uuid">
                                    <Card :item="item" :is="item.uuid" :event-layout="tab === 'sobitiya'"/>

                                    <RecommendedVacancies v-if="index === 1"/>
                                    <RecommendedPosts v-if="index === 3 && $root.config.lastEvents.length" :items="$root.config.lastEvents" :title="$t('Events')" :isEvent="true"/>
                                    <RecommendedPosts v-if="index === 6 && $root.config.lastArticles.length" :items="$root.config.lastArticles" :title="$t('Articles')"/>
                                    <Banner v-if="index && (index === 2 || index % 6 === 0)" location="category.view" class="mb-6"/>

                                    <intersection-observer
                                        v-if="index == posts.length - 9 && cursor"
                                        :sentinal-name="'posts' + cursor"
                                        @on-intersection-element="fetchData"
                                    ></intersection-observer>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
                <div class="col-lg-5">
                    <Sidebar/>
                    <SchemaOrgWebPage :name="$root.meta.title" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent, defineAsyncComponent } from "vue";
import VLazyImage from "v-lazy-image"
import Card from "@/components/Post/Card.vue"
import RecommendedPosts from "@/components/Post/RecommendedPosts.vue"
import Banner from "@/components/Ad/Banner.vue"
import RecommendedVacancies from "@/components/Vacancy/RecommendedVacancies.vue"
import CardSkeleton from "@/components/Post/CardSkeleton.vue"
import Sidebar from "@/components/Sidebar.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"

export default defineComponent({
    name: "Index",
    components: {
        VLazyImage,
        [!import.meta.env.VITE_SSR && 'Slider']: defineAsyncComponent(() =>
            import('@/components/Slider.vue')
        ),
        CardSkeleton,
        Card,
        RecommendedPosts,
        Banner,
        RecommendedVacancies,
        Sidebar,
        IntersectionObserver,
    },
    data() {
        return {
            currentSlide: null,
            tab: 'news',
            loading: false,
            posts: [],
            cursor: null,
        }
    },
    head() {
        return {
            title: this.$root.meta.title,
            meta: [
                {name: 'og:type', content: 'website'},
                {name: 'og:title', content: this.$root.meta.title},
                {name: 'og:site_name', content: 'Newshub.kz'},
                {name: 'og:image', content: this.$base('/android-chrome-512x512.png')},
                {name: 'og:image:width', content: '512px'},
                {name: 'og:image:height', content: '512px'},
                {name: 'og:image:type', content: 'image/png'},
                {name: 'description', content: 'Информационный хаб NewsHub.kz  —  это интернет-площадка для эффективного взаимодействия пресс-служб организаций со средствами массовой информации.'},
                {name: 'og:description', content: 'Информационный хаб NewsHub.kz  —  это интернет-площадка для эффективного взаимодействия пресс-служб организаций со средствами массовой информации.'},
            ]
        }
    },

    async serverPrefetch() {
        this.loading = false

        await this.$api(`category`, false, {
            method: 'post',
            data: {
                slug: this.tab,
                from: 'index',
                cursor: this.cursor
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
        if (!import.meta.env.SSR) {
            this.fetchData()
        }
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api(`category`, false, {
                method: 'post',
                data: {
                    slug: this.tab,
                    from: 'index',
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
