<template>
    <div class="row">
        <div class="col-lg-7">
            <div v-if="loading && !users.length">
                <CardSkeleton v-for="n in 8"/>
            </div>
            <div v-else-if="users.length">
                <div v-for="item in users" class="card border-hover-primary mb-6">
                    <div class="card-body p-5">
                        <div class="d-flex overflow-hidden">
                            <app-link :to="{name: 'user', params: {slug: item.id}}" class="me-6 d-flex flex-fill flex-nowrap">
                                <div class="me-6 flex-shrink-0">
                                    <div class="symbol symbol-50px w-50px bg-light rounded-3 my-1">
                                        <img :src="$storage(item.avatar_sm)" class="object-fit-cover rounded-3" alt="" loading="lazy"> 
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div class="fs-3 fw-bold text-dark me-5">{{ item.name }}</div>
                                    </div>
                                    <div class="text-gray-400 fw-semibold fs-5 mt-1 mb-0">{{ item.description }}</div>
                                </div>
                            </app-link>
                            <div v-if="$root.user && $root.user.id != item.id">
                                <button @click="$root.follow($root.feeds.includes(item.id)? 0: 1, item.id)" class="d-none d-xl-inline btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(item.id), 'btn-active-light-danger': $root.feeds.includes(item.id)}">
                                    {{ !$root.feeds.includes(item.id)? $t('Follow'): $t('Unfollow') }}
                                </button>
                                <button @click="$root.follow($root.feeds.includes(item.id)? 0: 1, item.id)" class="btn-icon d-inline d-xl-none btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(item.id), 'btn-active-light-danger': $root.feeds.includes(item.id)}">
                                    <i class="ki-duotone" :class="{'ki-user-tick': !$root.feeds.includes(item.id), 'ki-user-edit': $root.feeds.includes(item.id)}"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="card mb-5">
                <div class="card-body text-center">
                    <!--begin::Icon-->
                    <div class="pt-10 pb-10">
                        <i class="ki-duotone ki-search-list fs-4x opacity-50"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    </div>
                    <div class="pb-15 fw-semibold">
                        <h3 class="text-gray-600 fs-5 mb-2">{{ $t('Not found results')}}</h3>
                        <div class="text-muted fs-7">{{ $t('Please try again with a different query') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <Sidebar/>
            <SchemaOrgWebPage type="CollectionPage" :name="$root.meta.title" />
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import Sidebar from "@/components/Sidebar.vue"
import CardSkeleton from "@/components/User/CardSkeleton.vue"

export default defineComponent({
    name: "Category",
    components: {
        Sidebar,
        CardSkeleton,
    },
    data() {
        return {
            slug: this.$route.params.slug,
            loading: false,
            users: [],
        }
    },
    head() {
        return {
            title: this.$root.meta.title
        }
    },
    created() {
        this.init()
        this.fetchData()
    },
    watch: {
        $route(from, to) {
            if (from.name == 'user-category' && to.name == from.name && from.params.slug != to.params.slug) {
                this.reset()
            }
        }
    },
    methods: {
        init() {
            const category = this.$root.config.users.find((item) => item.slug == this.slug)
            this.$store.commit('setTitle', category.name)
        },
        fetchData() {
            this.loading = true

            this.$api(`users/${this.slug}`)
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                this.users = data.users
            })
        },
        reset() {
            this.slug = this.$route.params.slug
            this.users = []

            this.init()
            this.fetchData()
        },
    },
});
</script>