<template>
    <Popper placement="bottom-start" :class="{'popper-w-sm-100': $root.isMobile}">
        <div class="w-100 mw-350px">
            <form method="GET" action="/search/" class="w-100 d-none d-lg-block position-relative">
                <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y"><span class="path1"></span><span class="path2"></span></i>
                <input name="q" type="text" class="search-input form-control form-control-flush ps-8 pe-9 w-100" v-model="q" :placeholder="$t('Search') + '...'"/>
                <!-- <span v-if="loading" class="search-spinner position-absolute top-50 end-0 translate-middle-y lh-0 me-3">
                    <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                </span> -->
                <!-- <span v-if="!loading && q" class="search-reset btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0" @click="reset">
                    <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0"><span class="path1"></span><span class="path2"></span></i>
                </span> -->
            </form>

            <button type="button" class="btn d-inline d-lg-none btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px">
                <i class="ki-duotone ki-magnifier fs-2 fs-lg-1"><span class="path1"></span><span class="path2"></span></i>
            </button>
        </div>

        <template #content="{ close }">
            <div class="d-flex d-md-none menu menu-sub menu-sub-dropdown menu-column mw-sm-550px w-sm-550px min-w-sm-350px min-w-100 w-100 py-7 px-7 overflow-hidden show">
                <!--begin::Wrapper-->
                <div class="">
                    <form method="GET" action="/search/" class="w-100 d-block d-lg-none position-relative">
                        <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-5"><span class="path1"></span><span class="path2"></span></i>
                        <input name="q" type="text" class="search-input form-control ps-13 w-100" v-model="q" :placeholder="$t('Search') + '...'" />
                        <!-- <span v-if="loading" class="search-spinner position-absolute top-50 end-0 translate-middle-y lh-0 me-5">
                            <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                        </span>
                        <span v-if="!loading && q" class="search-reset btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-4" @click="q = ''">
                            <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0"><span class="path1"></span><span class="path2"></span></i>
                        </span> -->
                    </form>
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
    // props: {
    //     searchClass: {
    //         type: String,
    //         required: false,
    //         default: 'mw-225px min-w-225px'
    //     }
    // },
    data() {
        return {
            q: this.$route.query?.q ?? null,
            // loading: false,
            // posts: [],
            // vacancies: [],
            // polls: [],
        }
    },
    // created() {
        
    // },
    // computed: {
    //     hasResult() {
    //         return this.posts.length || this.polls.length || this.vacancies.length
    //     }
    // },
    // methods: {
    //     search() {
    //         if (!this.q.trim()) return

    //         this.loading = true

    //         this.$api('search', true, {
    //             params: {
    //                 q: this.q,
    //             }
    //         }).then(({data}) => {
    //             if (data.ok) {
    //                 this.posts = data.posts
    //                 this.vacancies = data.vacancies
    //                 this.polls = data.polls
    //             }
    //             this.loading = false
    //         }).catch((e) => {
    //             this.loading = false
    //         })
    //     },
    //     reset() {
    //         this.q = ''
    //         this.posts = []
    //         this.vacancies = []
    //         this.polls = []
    //     },
    // },
});
</script>