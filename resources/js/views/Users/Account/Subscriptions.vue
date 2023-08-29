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
                                <div class="flex-grow-1 me-5 fw-bold">
                                    <div class="text-dark fs-3" v-snip="{lines: 1}">{{ [item.name, item.lastname].join(' ') }}</div>
                                    <div class="text-gray-600 fw-semibold fs-6 mb-1" v-snip="{lines: 1}">{{ item.description }}</div>
                                    <div v-if="item.region_name" class="badge badge-light mb-1">
                                        {{ [item.region_name, item.region_name != item.city_name? item.city_name: '', item.media_name].filter(val => !!val).join(', ') }}
                                    </div>
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
                <intersection-observer
                    v-if="cursor && !loading"
                    :sentinal-name="'subscriptions'"
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
                        <h3 class="text-gray-600 fs-5 mb-2">{{ $t('Not found results')}}</h3>
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
import Sidebar from "@/components/Sidebar.vue"
import CardSkeleton from "@/components/User/CardSkeleton.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"

export default defineComponent({
    name: "Subscriptions",
    components: {
        Sidebar,
        CardSkeleton,
        IntersectionObserver,
    },
    data() {
        return {
            slug: this.$route.params.slug,
            loading: true,
            cursor: null,
            users: [],
        }
    },
    head() {
        return {
            title: this.$root.meta.title
        }
    },
    created() {
        this.fetchData()
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$get(`user/${this.slug}/subscriptions`, {cursor: this.cursor}).then(({data}) => {
                this.loading = false

                if (!data.ok) return

                if (this.users.length) {
                    data.users.data.forEach((item) => this.users.push(item))
                } else {
                    this.users = data.users.data
                }

                this.cursor = data.users.next_cursor
            })
        },
        reset() {
            this.loading = true
            this.cursor = null
            this.users = []

            this.fetchData()
        },
    },
});
</script>