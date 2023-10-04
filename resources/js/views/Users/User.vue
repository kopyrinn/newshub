<template>
    <div>
        <ViewSkeleton v-if="loading"/>
        <div v-else class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap">
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-150px symbol-fixed position-relative">
                            <img :src="$storage(user.avatar)" class="object-fit-cover" alt="image">
                            <div
                                class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                            </div>
                        </div>
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ user.name }}</span>
                                    <i class="ki-duotone ki-verify fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                </div>

                                <div class="d-flex flex-wrap fw-semibold fs-6 pe-2">
                                    <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-3">
                                        <i class="ki-duotone ki-profile-circle fs-4 me-1"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span></i>
                                                <span v-for="(role, index) in user.role_names">{{ role }}<span v-if="index + 1 < user.role_names.length" class="me-1">,</span></span>
                                    </a>
                                    <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-3">
                                        <i class="ki-duotone ki-call fs-4 me-1"><i class="path1"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i><i class="path5"></i><i class="path6"></i><i class="path7"></i><i class="path8"></i></i> {{ user.phone }}
                                    </a>
                                    <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-3">
                                        <i class="ki-duotone ki-sms fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> {{ user.email }}
                                    </a>
                                </div>
                            </div>

                            <div v-if="!isOwner && $root.user" class="d-flex my-4">
                                <button @click="$root.follow($root.feeds.includes(user.id)? 0: 1, user.id)" class="btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(user.id), 'btn-active-light-danger': $root.feeds.includes(user.id)}">
                                    <i class="ki-duotone ki-check fs-4" :class="{'d-none': !$root.feeds.includes(user.id)}"></i>{{ !$root.feeds.includes(user.id)? $t('Follow'): $t('Unfollow') }}
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap flex-stack mb-5">
                            <div class="text-gray-800 fs-6 fw-semibold">{{ user.description }}</div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-invisible mt-2">
                    <ul class="nav nav-stretch flex-nowrap nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                        <li class="nav-item">
                            <app-link :to="{name: 'user', params: {slug}}" class="nav-link text-active-primary ms-0 me-10 py-5" :class="{'active': $route.name == 'user'}" href="">{{ $t('Overview') }}</app-link>
                        </li>
                        <li v-if="isOwner" class="nav-item">
                            <app-link :to="{name: 'user-settings', params: {slug}}" class="nav-link text-active-primary ms-0 me-10 py-5" :class="{'active': $route.name == 'user-settings'}" href="">{{ $t('Settings') }}</app-link>
                        </li>
                        <li v-if="isOwner && !$root.user.is_journalist" class="nav-item">
                            <app-link :to="{name: 'user-workspace', params: {slug}}" class="nav-link text-active-primary ms-0 me-10 py-5" :class="{'active': $route.name == 'user-workspace'}" href="">{{ $t('Workspace') }}</app-link>
                        </li>
                        <li v-if="isOwner" class="nav-item">
                            <app-link :to="{name: 'user-actions', params: {slug}}" class="nav-link text-active-primary ms-0 me-10 py-5" :class="{'active': $route.name == 'user-actions'}" href="">{{ $t('Actions') }}</app-link>
                        </li>
                        <li v-if="isOwner" class="nav-item">
                            <app-link :to="{name: 'user-notifications', params: {slug}}" class="nav-link text-active-primary ms-0 me-10 py-5" :class="{'active': $route.name == 'user-notifications'}" href="">{{ $t('Notifications') }}</app-link>
                        </li>
                        <li v-if="isOwner && !$root.user.is_journalist" class="nav-item">
                            <app-link :to="{name: 'user-package', params: {slug}}" class="nav-link text-active-primary ms-0 me-10 py-5" :class="{'active': $route.name == 'user-package'}" href="">{{ $t('Package') }}</app-link>
                        </li>
                        <li class="nav-item">
                            <app-link :to="{name: 'user-followers', params: {slug}}" class="nav-link text-active-primary ms-0 me-10 py-5" :class="{'active': $route.name == 'user-followers'}" href="">{{ $t('Followers') }} <span class="badge badge-light ms-2">{{ user.followers_count }}</span></app-link>
                        </li>
                        <li class="nav-item">
                            <app-link :to="{name: 'user-subscriptions', params: {slug}}" class="nav-link text-active-primary ms-0 me-10 py-5" :class="{'active': $route.name == 'user-subscriptions'}" href="">{{ $t('Subscriptions') }} <span class="badge badge-light ms-2">{{ user.feeds_count }}</span></app-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <router-view/>
        <SchemaOrgWebPage :name="$root.meta.title" />
    </div>
</template>
<script>
import { defineComponent } from "vue"
import ViewSkeleton from "@/components/User/ViewSkeleton.vue"

export default defineComponent({
    name: "User",
    components: {
        ViewSkeleton,
    },
    data() {
        return {
            isOwner: this.$root.token && this.$root.user.id == this.$route.params.slug,
            slug: this.$route.params.slug,
            loading: true,
            user: {}
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
                {
                    name: 'og:image',
                    content: this.$root.meta.ogImage,
                },
                {
                    name: 'twitterCard',
                    content: this.$root.meta.twitterCard,
                },
            ]
        }
    },
    async serverPrefetch() {
        this.loading = false

        await this.$api(`user/${this.slug}`, false).then(({data}) => {
            if (!data.ok) return

            this.user = data.user

            this.$store.commit('setMeta', {
                title: this.user.name,
                ogTitle: this.user.name,
                description: this.user.description,
                ogDescription: this.user.description,
                ogImage: this.user.avatar? this.$storage(this.user.avatar): '',
                twitterCard: 'summary_large_image',
            })
        })
        .catch((e) => {})
    },
    created() {
        if (!import.meta.env.SSR) {
            this.fetchData()

            this.$bus.on('refresh-user', this.fetchData)
        }
    },
    beforeUnmount() {
        if (!import.meta.env.SSR) {
            this.$bus.off('refresh-user')
        }
    },
    watch: {
        $route(from, to) {
            if (from.name.startsWith('user') && to.name.startsWith('user') && from.params.slug != to.params.slug) {
                this.reset()
            }
        }
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api(`user/${this.slug}`, false)
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                this.user = data.user

                this.$store.commit('setMeta', {
                    title: this.user.name,
                    ogTitle: this.user.name,
                    description: this.user.description,
                    ogDescription: this.user.description,
                    ogImage: this.user.avatar? this.$storage(this.user.avatar): '',
                    twitterCard: 'summary_large_image',
                })
            })
        },
        reset() {
            this.slug = this.$route.params.slug
            this.loading = true
            this.user = {}

            this.fetchData()
        },
    },
});
</script>