<template>
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-5 p-lg-10 pb-lg-0">
                    <ViewSkeleton v-if="loading"/>
                    <div v-else class="mb-17">
                        <div class="mb-8">
                            <h1 class="text-dark fs-1 fw-bold">
                                {{ post.title }}

                                <span class="fw-bold text-muted fs-5 ps-1">{{ post.read_mins }} {{ $t('mins read') }}</span>
                            </h1>
                            <div class="d-flex flex-wrap">
                                <div class="me-5 my-1 d-flex align-items-center">
                                    <i class="ki-duotone ki-element-11 fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>

                                    <span class="fw-bold text-gray-400"><VDate :datetime="new Date(post.created_at)"/></span>
                                </div>
                                <div v-if="post.categories.length" class="me-5 my-1 d-flex align-items-center">
                                    <i class="ki-duotone ki-briefcase fs-2 me-2"><span
                                            class="path1"></span><span class="path2"></span></i>

                                    <span class="fw-bold text-gray-400"><span v-for="(category, index) in post.categories"><app-link :to="{name: 'category', params: {slug: category.slug}}">{{ category.name }}</app-link><span v-if="index + 1 < post.categories.length" class="me-1">,</span></span></span>
                                </div>
                                <div v-if="post.categories.length && post.rubrics.length" class="me-5 my-1 d-flex align-items-center">
                                    <i class="ki-duotone ki-price-tag fs-2 me-2"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                    <span class="fw-bold text-gray-400"><span v-for="(rubric, index) in post.rubrics"><app-link :to="{name: 'category', params: {slug: post.categories[0].slug, rubric: rubric.slug}}">{{ rubric.name }}</app-link><span v-if="index + 1 < post.rubrics.length" class="me-1">,</span></span></span>
                                </div>
                                <!-- <div class="my-1 d-flex align-items-center">
                                    <i class="ki-duotone ki-message-text-2 text-primary fs-2 me-1"><span
                                            class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    <span class="fw-bold text-gray-400">24 Comments</span>
                                </div> -->
                            </div>
                            <div v-if="post.image" class="mt-6">
                                <img :src="$url('/storage/' + post.image)" class="object-fit-cover h-350px w-100 rounded"/>
                            </div>
                        </div>

                        <div class="fs-5 fw-medium text-gray-900 mb-10 article" v-html="post.content"></div>

                        <div class="card card-dashed border-hover-primary mb-6">
                            <div class="card-body p-5">
                                <div class="d-flex overflow-hidden">
                                    <app-link :to="{name: 'user', params: {slug: post.user_id}}" class="me-6 d-flex flex-fill flex-nowrap">
                                        <div class="me-6 flex-shrink-0">
                                            <div class="symbol symbol-50px w-50px bg-light my-1">
                                                <img :src="$url('/storage/' + post.avatar)" class="object-fit-cover" alt=""> 
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="fs-3 fw-bold text-dark me-5">{{ post.name }}</div>
                                            </div>
                                            <div class="text-gray-400 fw-semibold fs-5 mt-1 mb-0">{{ post.description }}</div>
                                        </div>
                                    </app-link>
                                    <div v-if="$root.user && $root.user.id != post.user_id">
                                        <button @click="$root.follow($root.feeds.includes(post.user_id)? 0: 1, post.user_id)" class="d-none d-xl-inline btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(post.user_id), 'btn-active-light-danger': $root.feeds.includes(post.user_id)}">
                                            {{ !$root.feeds.includes(post.user_id)? $t('Follow'): $t('Unfollow') }}
                                        </button>
                                        <button @click="$root.follow($root.feeds.includes(post.user_id)? 0: 1, post.user_id)" class="btn-icon d-inline d-xl-none btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(post.user_id), 'btn-active-light-danger': $root.feeds.includes(post.user_id)}">
                                            <i class="ki-duotone" :class="{'ki-user-tick': !$root.feeds.includes(post.user_id), 'ki-user-edit': $root.feeds.includes(post.user_id)}"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-center">
                            <!--begin::Icon-->
                            <a href="#" class="mx-4">
                                <img src="/assets/media/svg/brand-logos/facebook-4.svg" class="h-20px my-2"
                                    alt="">
                            </a>
                            <!--end::Icon-->

                            <!--begin::Icon-->
                            <a href="#" class="mx-4">
                                <img src="/assets/media/svg/brand-logos/instagram-2-1.svg"
                                    class="h-20px my-2" alt="">
                            </a>
                            <!--end::Icon-->

                            <!--begin::Icon-->
                            <a href="#" class="mx-4">
                                <img src="/assets/media/svg/brand-logos/github.svg" class="h-20px my-2"
                                    alt="">
                            </a>
                            <!--end::Icon-->

                            <!--begin::Icon-->
                            <a href="#" class="mx-4">
                                <img src="/assets/media/svg/brand-logos/behance.svg" class="h-20px my-2"
                                    alt="">
                            </a>
                            <!--end::Icon-->

                            <!--begin::Icon-->
                            <a href="#" class="mx-4">
                                <img src="/assets/media/svg/brand-logos/pinterest-p.svg" class="h-20px my-2"
                                    alt="">
                            </a>
                            <!--end::Icon-->

                            <!--begin::Icon-->
                            <a href="#" class="mx-4">
                                <img src="/assets/media/svg/brand-logos/twitter.svg" class="h-20px my-2"
                                    alt="">
                            </a>
                            <!--end::Icon-->

                            <!--begin::Icon-->
                            <a href="#" class="mx-4">
                                <img src="/assets/media/svg/brand-logos/dribbble-icon-1.svg"
                                    class="h-20px my-2" alt="">
                            </a>
                            <!--end::Icon-->
                        </div>
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
import Sidebar from "@/components/Sidebar.vue"
import ViewSkeleton from "@/components/Post/ViewSkeleton.vue"

export default defineComponent({
    name: "Post",
    components: {
        ViewSkeleton,
        Sidebar
    },
    data() {
        return {
            slug: this.$route.params.slug,
            loading: true,
            post: {}
        }
    },
    head() {
        return {
            title: this.$root.meta.title
        }
    },
    async serverPrefetch() {
        await this.$api(`post/${this.slug}`, false).then(({data}) => {
            this.loading = false

            if (!data.ok) return

            this.post = data.post
            this.$store.commit('setTitle', this.post.title)
        })
    },
    created() {
        this.fetchData()
    },
    watch: {
        $route(from, to) {
            if (from.name == 'post' && to.name == from.name && from.params.slug != to.params.slug) {
                this.reset()
            }
        }
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api(`post/${this.slug}`, false)
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                this.post = data.post
                this.$store.commit('setTitle', this.post.title)
            })
        },
        reset() {
            this.slug = this.$route.params.slug
            this.loading = true
            this.post = {}

            this.fetchData()
        },
    },
});
</script>