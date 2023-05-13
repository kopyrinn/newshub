<template>
    <div class="row">
        <div class="col-lg-7">
            <div v-if="loading && !posts.length">
                <CardSkeleton v-for="n in 4"/>
            </div>  
            <div v-else-if="posts.length">
                <div v-for="(item, index) in posts" :key="item.uuid">
                    <Card :item="item" :is="item.uuid"/>

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
        </div>
        <div class="col-lg-5">
            <Sidebar/>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import VLazyImage from "v-lazy-image"
import { Carousel, Slide } from 'vue3-carousel'
import Card from "@/components/Post/Card.vue"
import CardSkeleton from "@/components/Post/CardSkeleton.vue"
import Sidebar from "@/components/Sidebar.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"

export default defineComponent({
    name: "Index",
    components: {
        VLazyImage,
        Carousel,
        Slide,
        CardSkeleton,
        Card,
        Sidebar,
        IntersectionObserver,
    },
    data() {
        return {
            currentSlide: null,
            loading: false,
            posts: [],
            cursor: null,
        }
    },
    created() {
        if (this.$route.meta.noSsr && import.meta.env.SSR) return false

        this.fetchData()
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api('feed', true, {
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