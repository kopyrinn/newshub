<template>
    <div class="row">
        <div class="col-lg-7 order-lg-1 order-2">
            <div v-if="loading && !posts.length">
                <CardSkeleton v-for="n in 4"/>
            </div>
            <div v-else-if="posts.length">
                <div v-for="(item, index) in posts" :key="item.uuid">
                    <Card :item="item" :is="item.uuid"/>

                    <!-- <Banner v-if="index && (index === 2 || index % 6 === 0)" location="category.view" class="mb-6"/> -->
                </div>

                <div class="col-12 d-flex align-items-center justify-content-center justify-content-md-end">
                    <Paginate
                        v-model="page"
                        :page-count="lastPage"
                        :click-handler="currentPageChange"
                        :page-range="5"
                        :margin-pages="0"
                        :container-class="'pagination'"
                        :page-link-class="'page-link cursor-pointer'"
                        :prev-link-class="'page-link cursor-pointer'"
                        :next-link-class="'page-link cursor-pointer'"
                        :first-last-button="true"
                        :page-class="'page-item'"
                        :prev-class="'page-item previous'"
                        :next-class="'page-item next'"
                        :prev-text="`<i class='previous'></i>`"
                        :next-text="`<i class='next'></i>`"
                        :first-button-text="`<i class='fs-2 ki-duotone ki-double-left'><span class='path1'></span><span class='path2'></span></i>`"
                        :last-button-text="`<i class='fs-2 ki-duotone ki-double-right'><span class='path1'></span><span class='path2'></span></i>`"
                    >
                    </Paginate>
                </div>
            </div>
            <div v-else class="card mb-5">
                <div class="card-body text-center">
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
        <div class="col-lg-5 order-lg-2 order-1">
            <div class="card card-flush mb-6">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">{{ $t('News Archive') }}</span>
                    </h3>
                </div>

                <div class="card-body pt-0 p-3 pb-3">
                    <DatePicker v-model="date" :is-dark="$root.isDark" :locale="$root.locale" color="primary-blue" :min-date="new Date('2022-05-07')" :max-date="new Date" transparent borderless expanded/>
                </div>
            </div>
            <Sidebar class="d-none d-lg-flex"/>
            <SchemaOrgWebPage type="CollectionPage" :name="$root.meta.title" />
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import Card from "@/components/Post/Card.vue"
import Banner from "@/components/Ad/Banner.vue"
import CardSkeleton from "@/components/Post/CardSkeleton.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"
import Sidebar from "@/components/Sidebar.vue"
import Paginate from "@/components/Paginate.vue"
import { DatePicker } from 'v-calendar';

export default defineComponent({
    name: "Archive",
    components: {
        Card,
        Banner,
        CardSkeleton,
        IntersectionObserver,
        Sidebar,
        Paginate,
        DatePicker,
    },
    data() {
        return {
            page: parseInt(this.$route.query?.page) ?? null,
            lastPage: 0,
            loading: true,
            loading: true,
            posts: [],
            date: this.$route.query?.date ?? new Date,
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
    watch: {
        date(value) {
            if (value) {
                this.currentPageChange(1)
            }
        },
    },
    async serverPrefetch() {
        this.loading = false

        await this.$get('archive', {
            date: this.date,
            page: this.page ?? 0,
        })
        .then(({data}) => {
            if (!data.ok) return

            if (!this.posts.length) {
                this.posts = data.posts.data
            } else {
                data.posts.data.map((item) => this.posts.push(item))
            }

            this.lastPage = parseInt(data.posts?.last_page) ?? null
        })
        .catch((e) => {})
    },
    created() {
        if (!import.meta.env.SSR) {
            this.fetchData()
        }
    },
    methods: {
        currentPageChange(val) {
            this.page = val
            this.$router.replace({ query: { date: this.$dayjs(this.date).format('YYYY-MM-DD'), page: val } })
            this.loading = true
            this.posts = []

            this.fetchData()
        },
        fetchData() {
            this.loading = true

            this.$get('archive', {
                date: this.date,
                page: this.page ?? 0,
            })
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                if (!this.posts.length) {
                    this.posts = data.posts.data
                } else {
                    data.posts.data.map((item) => this.posts.push(item))
                }

                this.lastPage = parseInt(data.posts?.last_page) ?? null
            })
            .catch((e) => {
                this.loading = false
            })
        },
        reset() {
            this.page = 0
            this.loading = true
            this.posts = []

            this.fetchData()
        },
        setDay(e) {
            console.log(e)
        }
    },
});
</script>