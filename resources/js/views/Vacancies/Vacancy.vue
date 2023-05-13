<template>
    <div class="card">
        <div class="card-body p-5 p-lg-10 pb-lg-0">
            <div class="d-flex flex-column flex-xl-row">
                <div class="flex-lg-row-fluid me-xl-15">
                    <ViewSkeleton v-if="loading"/>
                    <div v-else class="mb-17">
                        <div class="mb-8">
                            <div class="d-flex flex-wrap mb-6">
                                <div class="me-9 my-1 d-flex align-items-center">
                                    <i class="ki-duotone ki-element-11 text-primary fs-2 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>

                                    <span class="fw-bold text-gray-400"><VDate :datetime="new Date(vacancy.created_at)"/></span>
                                </div>
                                <div class="me-9 my-1 d-flex align-items-center">
                                    <i class="ki-duotone ki-briefcase text-primary fs-2 me-1"><span
                                            class="path1"></span><span class="path2"></span></i>

                                    <span class="fw-bold text-gray-400">Announcements</span>
                                </div>
                                <div class="my-1 d-flex align-items-center">
                                    <i class="ki-duotone ki-message-text-2 text-primary fs-2 me-1"><span
                                            class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    <span class="fw-bold text-gray-400">24 Comments</span>
                                </div>
                            </div>
                            <h1 class="text-dark fs-1 fw-bold">
                                {{ vacancy.job_title }}
                            </h1>
                            <div v-if="vacancy.image" class="mt-8">
                                <img :src="$url('/storage/' + vacancy.image)" class="object-fit-cover h-350px w-100 rounded"/>
                            </div>
                        </div>

                        <div class="fs-5 fw-medium text-gray-900 mb-5 article" v-html="vacancy.requiremets"></div>
                        <div class="fs-5 fw-medium text-gray-900 mb-5 article" v-html="vacancy.task"></div>
                        <div class="fs-5 fw-medium text-gray-900 mb-5 article" v-html="vacancy.conditionsm"></div>
                        
                        <div class="fs-5 fw-medium text-gray-900 mb-10">
                            Email: <a :href="'mailto:' + vacancy.email_jobseeker">{{ vacancy.email_jobseeker }}</a>
                        </div>

                        <div class="card card-dashed border-hover-primary mb-6">
                            <div class="card-body p-5">
                                <div class="d-flex overflow-hidden">
                                    <app-link :to="{name: 'user', params: {slug: vacancy.user_id}}" class="me-6 d-flex flex-fill flex-nowrap">
                                        <div class="me-6 flex-shrink-0">
                                            <div class="symbol symbol-50px w-50px bg-light my-1">
                                                <img :src="$url('/storage/' + vacancy.avatar)" class="object-fit-cover" alt=""> 
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="fs-3 fw-bold text-dark me-5">{{ vacancy.name }}</div>
                                            </div>
                                            <div class="text-gray-400 fw-semibold fs-5 mt-1 mb-0">{{ vacancy.description }}</div>
                                        </div>
                                    </app-link>
                                    <div v-if="$root.user && $root.user.id != vacancy.user_id">
                                        <button @click="$root.follow($root.feeds.includes(vacancy.user_id)? 0: 1, vacancy.user_id)" class="d-none d-xl-inline btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(vacancy.user_id), 'btn-active-light-danger': $root.feeds.includes(vacancy.user_id)}">
                                            {{ !$root.feeds.includes(vacancy.user_id)? $t('Follow'): $t('Unfollow') }}
                                        </button>
                                        <button @click="$root.follow($root.feeds.includes(vacancy.user_id)? 0: 1, vacancy.user_id)" class="btn-icon d-inline d-xl-none btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(vacancy.user_id), 'btn-active-light-danger': $root.feeds.includes(vacancy.user_id)}">
                                            <i class="ki-duotone" :class="{'ki-user-tick': !$root.feeds.includes(vacancy.user_id), 'ki-user-edit': $root.feeds.includes(vacancy.user_id)}"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
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

                <div class="flex-column flex-lg-row-auto w-100 w-xl-300px mb-10">
                    <!--begin::Search blog-->
                    <div class="mb-16">
                        <h4 class="text-dark mb-7">Search Blog</h4>

                        <!--begin::Input group-->
                        <div class="position-relative">
                            <i
                                class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6"><span
                                    class="path1"></span><span class="path2"></span></i>
                            <input type="text" class="form-control form-control-solid ps-10" name="search" value=""
                                placeholder="Search">
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Search blog-->


                    <!--begin::Catigories-->
                    <div class="mb-16">
                        <h4 class="text-dark mb-7">Categories</h4>

                        <!--begin::Item-->
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <!--begin::Text-->
                            <a href="#" class="text-muted text-hover-primary pe-2">
                                SaaS Solutions </a>
                            <!--end::Text-->

                            <!--begin::Number-->
                            <div class="m-0">
                                24 </div>
                            <!--end::Number-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <!--begin::Text-->
                            <a href="#" class="text-muted text-hover-primary pe-2">
                                Company News </a>
                            <!--end::Text-->

                            <!--begin::Number-->
                            <div class="m-0">
                                152 </div>
                            <!--end::Number-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <!--begin::Text-->
                            <a href="#" class="text-muted text-hover-primary pe-2">
                                Events &amp; Activities </a>
                            <!--end::Text-->

                            <!--begin::Number-->
                            <div class="m-0">
                                52 </div>
                            <!--end::Number-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <!--begin::Text-->
                            <a href="#" class="text-muted text-hover-primary pe-2">
                                Support Related </a>
                            <!--end::Text-->

                            <!--begin::Number-->
                            <div class="m-0">
                                305 </div>
                            <!--end::Number-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <!--begin::Text-->
                            <a href="#" class="text-muted text-hover-primary pe-2">
                                Innovations </a>
                            <!--end::Text-->

                            <!--begin::Number-->
                            <div class="m-0">
                                70 </div>
                            <!--end::Number-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted ">
                            <!--begin::Text-->
                            <a href="#" class="text-muted text-hover-primary pe-2">
                                Product Updates </a>
                            <!--end::Text-->

                            <!--begin::Number-->
                            <div class="m-0">
                                585 </div>
                            <!--end::Number-->
                        </div>
                        <!--end::Item-->

                    </div>
                    <!--end::Catigories-->


                    <!--begin::Recent posts-->
                    <div class="m-0">
                        <h4 class="text-dark mb-7">Recent Posts</h4>

                        <!--begin::Item-->
                        <div class="d-flex flex-stack mb-7">
                            <!--begin::Symbol-->

                            <div class="symbol symbol-60px symbol-2by3 me-4">
                                <div class="symbol-label"
                                    style="background-image: url('/metronic8/demo1/assets/media/stock/600x400/img-1.jpg')">
                                </div>
                            </div>
                            <!--end::Symbol-->

                            <!--begin::Title-->
                            <div class="m-0">
                                <a href="#" class="text-dark fw-bold text-hover-primary fs-6">About Bootstrap Admin</a>

                                <span class="text-gray-600 fw-semibold d-block pt-1 fs-7">We’ve been a focused on making a
                                    the sky</span>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-stack mb-7">
                            <!--begin::Symbol-->

                            <div class="symbol symbol-60px symbol-2by3 me-4">
                                <div class="symbol-label"
                                    style="background-image: url('/metronic8/demo1/assets/media/stock/600x400/img-2.jpg')">
                                </div>
                            </div>
                            <!--end::Symbol-->

                            <!--begin::Title-->
                            <div class="m-0">
                                <a href="#" class="text-dark fw-bold text-hover-primary fs-6">A yellow sofa</a>

                                <span class="text-gray-600 fw-semibold d-block pt-1 fs-7">We’ve been a focused on making a
                                    the sky</span>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-stack mb-7">
                            <!--begin::Symbol-->

                            <div class="symbol symbol-60px symbol-2by3 me-4">
                                <div class="symbol-label"
                                    style="background-image: url('/metronic8/demo1/assets/media/stock/600x400/img-3.jpg')">
                                </div>
                            </div>
                            <!--end::Symbol-->

                            <!--begin::Title-->
                            <div class="m-0">
                                <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Our Camra Mega Set</a>

                                <span class="text-gray-600 fw-semibold d-block pt-1 fs-7">We’ve been a focused on making a
                                    the sky</span>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-stack ">
                            <!--begin::Symbol-->

                            <div class="symbol symbol-60px symbol-2by3 me-4">
                                <div class="symbol-label"
                                    style="background-image: url('/metronic8/demo1/assets/media/stock/600x400/img-4.jpg')">
                                </div>
                            </div>
                            <!--end::Symbol-->

                            <!--begin::Title-->
                            <div class="m-0">
                                <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Time to cook and eat?</a>

                                <span class="text-gray-600 fw-semibold d-block pt-1 fs-7">We’ve been a focused on making a
                                    the sky</span>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Item-->

                    </div>
                    <!--end::Recent posts-->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import Sidebar from "@/components/Sidebar.vue"
import ViewSkeleton from "@/components/Post/ViewSkeleton.vue"

export default defineComponent({
    name: "Vacancy",
    components: {
        ViewSkeleton
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
        this.loading = false

        await this.$api(`vacancy/${this.slug}`, false).then(({data}) => {
            if (!data.ok) return

            this.vacancy = data.vacancy
            this.$store.commit('setTitle', this.vacancy.job_title)
        })
    },
    created() {
        this.fetchData()
    },
    watch: {
        $route(from, to) {
            if (from.name == 'vacancy' && to.name == from.name && from.params.slug != to.params.slug) {
                this.reset()
            }
        }
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api(`vacancy/${this.slug}`, false)
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                this.vacancy = data.vacancy
                this.$store.commit('setTitle', this.vacancy.job_title)
            })
        },
        reset() {
            this.slug = this.$route.params.slug
            this.loading = true
            this.vacancy = {}

            this.fetchData()
        },
    },
});
</script>