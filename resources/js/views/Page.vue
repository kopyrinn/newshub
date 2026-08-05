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
                        <div v-if="$route.params.slug === 'ads'" class="mt-10">
                            <h2 class="fs-3 fw-bold text-gray-900 mb-5">Прайс-листы</h2>
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-5 rounded border border-dashed border-gray-300 bg-light-primary p-6">
                                <div>
                                    <div class="fs-5 fw-bold text-gray-900">Прайс-лист NewsHub.kz на 2026 год</div>
                                    <div class="fs-7 fw-semibold text-gray-600 mt-1">PDF · 938 КБ · 3 страницы</div>
                                </div>
                                <a
                                    href="/docs/price-list-newshub-2026.pdf"
                                    download="Прайс-лист NewsHub.kz 2026 г..pdf"
                                    class="btn btn-primary flex-shrink-0"
                                >
                                    <svg class="me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M12 3V15M12 15L8 11M12 15L16 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M5 20H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    Скачать PDF
                                </a>
                            </div>
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Post content-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Layout-->

            <SchemaOrgWebPage type="AboutPage" :name="$root.meta.title" />
        </div>
        <!--end::Body-->
    </div>
</template>
<script>
import { defineComponent } from "vue";
import VDate from "@/components/VDate.vue"
import { sanitizePageContent } from "@/app/pageContent.js"

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
            this.content = sanitizePageContent(this.$route.params.slug, data.page.page_content)
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
                this.content = sanitizePageContent(this.$route.params.slug, data.page.page_content)
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
