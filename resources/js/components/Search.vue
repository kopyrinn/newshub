<template>
    <Popper placement="bottom-start" :class="{'popper-w-sm-100': $root.isMobile}">
        <div class="w-100 mw-350px">
            <div class="w-100 d-none d-lg-block position-relative">
                <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" class="search-input form-control form-control-flush ps-8 pe-9 w-100" v-model="q" :placeholder="$t('Search') + '...'" v-debounce="search"/>
                <span v-if="loading" class="search-spinner position-absolute top-50 end-0 translate-middle-y lh-0 me-3">
                    <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                </span>
                <span v-if="!loading && q" class="search-reset btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0" @click="reset">
                    <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0"><span class="path1"></span><span class="path2"></span></i>
                </span>
            </div>

            <button type="button" class="btn d-inline d-lg-none btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px">
                <i class="ki-duotone ki-magnifier fs-2 fs-lg-1"><span class="path1"></span><span class="path2"></span></i>
            </button>
        </div>

        <template #content="{ close }">
            <div class="menu menu-sub menu-sub-dropdown menu-column mw-sm-550px w-sm-550px min-w-sm-350px min-w-100 w-100 py-7 px-7 overflow-hidden show">
                <!--begin::Wrapper-->
                <div class="">
                    <div class="w-100 d-block d-lg-none position-relative mb-5">
                        <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-5"><span class="path1"></span><span class="path2"></span></i>
                        <input type="text" class="search-input form-control ps-13 w-100" v-model="q" :placeholder="$t('Search') + '...'" v-debounce="search"/>
                        <span v-if="loading" class="search-spinner position-absolute top-50 end-0 translate-middle-y lh-0 me-5">
                            <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                        </span>
                        <span v-if="!loading && q" class="search-reset btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-4" @click="q = ''">
                            <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                    </div>

                    <div v-if="!loading && hasResult">
                        <div class="overflow-y-auto mh-450px mh-lg-450px">
                            <div v-if="posts.length" class="mb-7">
                                <h3 class="fs-5 text-muted m-0 pb-5">{{ $t('News') }}</h3>
                                <app-link v-for="item in posts" :key="item.slug" :to="{name: 'post', params: {slug: item.slug}}" class="d-flex text-dark text-hover-primary align-items-start mb-5" @click="close()">
                                    <div v-if="item.image" class="symbol symbol-40px me-4 mt-1">
                                        <img :src="$url('/storage/' + item.image)" alt="" class="object-fit-cover" />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fs-6 fw-semibold">{{ item.title }}</span>
                                        <span class="fs-7 fw-semibold text-muted">{{ item.summary }}</span>
                                    </div>
                                </app-link>
                            </div>
                            <div v-if="polls.length" class="mb-7">
                                <h3 class="fs-5 text-muted m-0 pb-5">{{ $t('Polls') }}</h3>
                                <app-link v-for="item in polls" :key="item.slug" :to="{name: 'poll', params: {slug: item.slug}}" class="d-flex text-dark text-hover-primary align-items-start mb-5" @click="close()">
                                    <div v-if="item.image" class="symbol symbol-40px me-4 mt-1">
                                        <img :src="$url('/storage/' + item.image)" alt="" class="object-fit-cover" />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fs-6 fw-semibold">{{ item.question }}</span>
                                    </div>
                                </app-link>
                            </div>
                            <div v-if="vacancies.length" class="">
                                <h3 class="fs-5 text-muted m-0 pb-5">{{ $t('Vacancies') }}</h3>
                                <app-link v-for="item in vacancies" :key="item.id" :to="{name: 'vacancy', params: {slug: item.id}}" class="d-flex text-dark text-hover-primary align-items-start mb-5" @click="close()">
                                    <div v-if="item.image" class="symbol symbol-40px me-4 mt-1">
                                        <img :src="$url('/storage/' + item.image)" alt="" class="object-fit-cover" />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fs-6 fw-semibold">{{ item.job_title }}</span>
                                    </div>
                                </app-link>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center">
                        <div class="pt-10 pb-10">
                            <span class="spinner-border spinner-border-md opacity-50"></span>
                        </div>
                    </div>
                    <div v-else class="text-center">
                        <div class="py-10">
                            <i class="ki-duotone ki-search-list opacity-50 fs-4x"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                        </div>
                        <div class="pb-15 fw-semibold">
                            <h3 class="text-gray-600 fs-5 mb-2">{{ $t('Not found posts') }}</h3>
                            <div class="text-muted fs-7">{{ $t('Please try again with a different query') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </Popper>
</template>
<script>
import { defineComponent } from "vue";
import Popper from "vue3-popper"

export default defineComponent({
    name: "Search",
    components: {
        Popper
    },
    props: {
        searchClass: {
            type: String,
            required: false,
            default: 'mw-225px min-w-225px'
        }
    },
    data() {
        return {
            q: '',
            loading: false,
            posts: [],
            vacancies: [],
            polls: [],
        }
    },
    created() {
        
    },
    computed: {
        hasResult() {
            return this.posts.length || this.polls.length || this.vacancies.length
        }
    },
    methods: {
        search() {
            if (!this.q.trim()) return

            this.loading = true

            this.$api('search', true, {
                params: {
                    q: this.q,
                }
            }).then(({data}) => {
                if (data.ok) {
                    this.posts = data.posts
                    this.vacancies = data.vacancies
                    this.polls = data.polls
                }
                this.loading = false
            }).catch((e) => {
                this.loading = false
            })
        },
        reset() {
            this.q = ''
            this.posts = []
            this.vacancies = []
            this.polls = []
        },
    },
});
</script>