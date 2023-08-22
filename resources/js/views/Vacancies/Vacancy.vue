<template>
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-5 p-lg-10 pb-lg-0">
                    <div class="d-flex flex-column flex-xl-row">
                        <div class="flex-lg-row-fluid">
                            <ViewSkeleton v-if="loading"/>
                            <div v-else class="mb-17">
                                <div class="mb-8">
                                    <div class="d-flex flex-wrap mb-6">
                                        <div class="me-9 my-1 d-flex align-items-center">
                                            <i class="ki-duotone ki-element-11 text-primary fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>

                                            <span class="fw-bold text-gray-400"><VDate :datetime="new Date(vacancy.created_at)"/></span>
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
                                    <a :href="$root.shareWith('tg', $base($route.fullPath))" class="mx-4">
                                        <img :src="$media('svg/brand-logos/telegram.svg')" class="h-20px my-2" alt="">
                                    </a>
                                    <a :href="$root.shareWith('vk', $base($route.fullPath))" class="mx-4">
                                        <img :src="$media('svg/brand-logos/vk.svg')" class="h-20px my-2" alt="">
                                    </a>
                                    <a :href="$root.shareWith('tw', $base($route.fullPath))" class="mx-4">
                                        <img :src="$media('svg/brand-logos/twitter.svg')" class="h-20px my-2" alt="">
                                    </a>
                                    <a :href="$root.shareWith('fb', $base($route.fullPath))" class="mx-4">
                                        <img :src="$media('svg/brand-logos/facebook-4.svg')" class="h-20px my-2" alt="">
                                    </a>
                                </div>
                            </div>
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
    name: "Vacancy",
    components: {
        ViewSkeleton,
        Sidebar,
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
            title: this.$root.meta.title,
            meta: [
                {
                    name: 'description',
                    content: this.$root.meta.description,
                },
                {
                    name: 'og:title',
                    content: this.$root.meta.title,
                },
                {
                    name: 'og:description',
                    content: this.$root.meta.ogDescription,
                },
            ]
        }
    },
    async serverPrefetch() {
        this.loading = false

        await this.$api(`vacancy/${this.slug}`, false).then(({data}) => {
            if (!data.ok) return

            this.vacancy = data.vacancy

            this.$store.commit('setMeta', {
                description: this.vacancy.task,
                ogDescription: this.vacancy.task,
                title: this.vacancy.job_title,
                ogTitle: this.vacancy.job_title,
            })
        })
        .catch((e) => {})
    },
    created() {
        if (!import.meta.env.SSR) {
            this.fetchData()
        }
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

                this.$store.commit('setMeta', {
                    description: this.vacancy.task,
                    ogDescription: this.vacancy.task,
                    title: this.vacancy.job_title,
                    ogTitle: this.vacancy.job_title,
                })
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