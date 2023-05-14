<template>
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-10 pb-lg-0">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid me-xl-15">
                    <!--begin::Post content-->
                    <div class="mb-17">
                        <!--begin::Wrapper-->
                        <div class="mb-8">
                            <!--begin::Info-->
                            <div class="d-flex flex-wrap mb-6">
                                <!--begin::Item-->
                                <div class="me-9 my-1">
                                    <!--begin::Icon-->
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-primary svg-icon-2 me-1"><svg width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor"></rect>
                                            <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2"
                                                fill="currentColor"></rect>
                                            <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
                                                fill="currentColor"></rect>
                                            <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
                                                fill="currentColor"></rect>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon--> <!--end::Icon-->

                                    <!--begin::Label-->
                                    <span v-if="updated_at" class="fw-bold text-gray-400"><VDate :datetime="new Date(updated_at)"/></span>
                                    <!--end::Label-->
                                </div>
                                <!--end::Item-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <div class="fs-5 fw-semibold article">
                            <p class="mb-8" v-html="content"></p>
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Post content-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Layout-->

        </div>
        <!--end::Body-->
    </div>
</template>
<script>
import { defineComponent } from "vue";
import VDate from "@/components/VDate.vue"

export default defineComponent({
    name: 'Page',
    components: {
        VDate,
    },
    data() {
        return {
            loading: false,
            title: null,
            content: null,
            updated_at: null,
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
        await this.$api(`page/${this.$route.params.slug}`, false).then(({data}) => {
            if (!data.ok) return

            this.title = data.page.title
            this.content = data.page.page_content
            this.updated_at = data.page.updated_at

            this.$store.commit('setMeta', {
                description: data.page.description,
                ogDescription: data.page.task,
                title: data.page.title,
                ogTitle: data.page.title,
            })
        }).catch((e) => {})
    },
    created() {
        if (!import.meta.env.SSR) {
            this.getPage()
        }
    },
    watch:{
        $route (to, from) {
            if (
                to.name == 'page' &&
                from.name == 'page'
            ) {
                this.getPage()
            }
        }
    },
    methods: {
        getPage() {
            this.loading = true
            this.$api(`page/${this.$route.params.slug}`, false)
            .then(({data}) => {
                if (!data.ok) return

                this.title = data.page.title
                this.content = data.page.page_content
                this.updated_at = data.page.updated_at

                this.$store.commit('setMeta', {
                    description: data.page.description,
                    ogDescription: data.page.task,
                    title: data.page.title,
                    ogTitle: data.page.title,
                })

                this.loading = false
            }).catch((e) => {})
        }
    }
});
</script>