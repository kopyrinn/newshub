<template>
    <div class="row">
        <div class="col-lg-7">
            <div class="w-100 mb-10 d-flex">
                <div class="d-none d-md-block position-relative w-50 me-5">
                    <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-5"><i class="path1"></i><i class="path2"></i></i>
                    <input type="text" class="search-input form-control ps-13 w-100" v-model="search" v-debounce="reset" placeholder="Поиск...">
                </div>
                <div class="d-flex">
                    <select class="form-control form-control-lg me-5" :placeholder="$t('Region')" v-model="region" @change="reset(), city = ''">
                        <option value="">{{ $t('Select region') }}</option>
                        <option v-for="item in regions" :value="item.id">{{ item['region_name_' + $root.locale] }}</option>
                    </select>
                    <select class="form-control form-control-lg" :placeholder="$t('City')" v-model="city" @change="reset">
                        <option value="">{{ $t('Select city') }}</option>
                        <option v-for="item in citiesByRegion" :value="item.id">{{ item['city_name_' + $root.locale] }}</option>
                    </select>
                </div>
            </div>

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
                                        <div class="fs-3 fw-bold text-dark me-5">
                                            <div>{{ [item.name, item.lastname].join(' ') }}</div>
                                            <div v-if="item.region_name" class="badge badge-light">
                                                {{ [item.region_name, item.region_name != item.city_name? item.city_name: '', item.media_name].filter(val => !!val).join(', ') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="item.description" class="text-gray-400 fw-semibold fs-5 mt-1 mb-0 text-truncate">{{ item.description }}</div>
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
                    :sentinal-name="'journalists'"
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
    name: "Journalists",
    components: {
        Sidebar,
        CardSkeleton,
        IntersectionObserver,
    },
    data() {
        return {
            loading: true,
            users: [],
            cities: [],
            regions: [],
            cursor: null,
            search: '',
            city: '',
            region: '',
        }
    },
    head() {
        return {
            title: this.$root.meta.title
        }
    },
    computed: {
        citiesByRegion() {
            if (!this.region || !this.cities.length) return []

            let cities = this.cities.filter((item) => {
                return item.region_id == this.region
            })

            return cities.sort((a, b) => (a['city_name_' + this.$root.locale] > b['city_name_' + this.$root.locale]) ? 1 : -1)
        },
    },
    async created() {
        await this.$get('fields').then(({data}) => {
            this.cities = data.cities
            this.regions = data.regions
        }).catch((e) => {})

        this.fetchData()
    },
    methods: {
        fetchData() {
            this.$get('journalists', {
                cursor: this.cursor,
                q: this.search.trim().toLowerCase(),
                region: this.region,
                city: this.city,
            }).then(({data}) => {
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