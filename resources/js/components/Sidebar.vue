<template>
    <div class="d-none d-sm-flex flex-column-reverse h-100">
        <div class="pb-10 position-sticky bottom-0">
            <div class="card card-flush mb-6">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">{{ $t('Last News') }}</span>
                    </h3>
                </div>

                <div class="card-body pt-0">
                    <template v-for="(posts, date) in postLatestChunk1">
                        <div class="text-gray-400 fw-semibold fs-6 mb-4"><VDate :datetime="new Date(date)" :dateOnly="true"/></div>

                        <div class="timeline-label mb-6">
                            <div v-for="item in posts" class="timeline-item d-flex align-items-center">
                                <div class="timeline-label fw-bold text-gray-800 fs-6">{{ $dayjs(new Date(item.created_at)).format('HH:mm') }}</div>
                                <div class="timeline-badge">
                                    <!-- <i v-if="item.is_featured" class="ki-duotone ki-abstract-22 fs-3" :class="{'text-warning': item.pageviews < 100, 'text-success': item.pageviews >= 100}"><i class="path1"></i><i class="path2"></i></i> -->
                                    <!-- <i v-if="item.is_featured" class="ki-duotone ki-information-5 fs-3 text-danger"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i> -->
                                    <i class="ki-duotone ki-abstract-8 fs-3" :class="{'text-gray-600': item.pageviews < 100, 'text-success': item.pageviews >= 100}"><i class="path1"></i><i class="path2"></i></i>
                                </div>
                                <div class="d-flex align-items-center">
                                    <app-link :to="{name: 'post', params: {slug: item.slug}}" class="fw-bold text-gray-800 px-3">{{ item.title }}</app-link>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <Banner location="sidebar.view" class="mb-6"/>

            <div class="card card-flush mb-6">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">{{ $t('Last News') }}</span>
                    </h3>
                </div>

                <div class="card-body pt-0">
                    <template v-for="(posts, date) in postLatestChunk2">
                        <div class="text-gray-400 fw-semibold fs-6 mb-4"><VDate :datetime="new Date(date)" :dateOnly="true"/></div>

                        <div class="timeline-label mb-6">
                            <div v-for="item in posts" class="timeline-item d-flex align-items-center">
                                <div class="timeline-label fw-bold text-gray-800 fs-6">{{ $dayjs(new Date(item.created_at)).format('HH:mm') }}</div>
                                <div class="timeline-badge">
                                    <!-- <i v-if="item.is_featured" class="ki-duotone ki-abstract-22 fs-3" :class="{'text-warning': item.pageviews < 100, 'text-success': item.pageviews >= 100}"><i class="path1"></i><i class="path2"></i></i> -->
                                    <!-- <i v-if="item.is_featured" class="ki-duotone ki-information-5 fs-3 text-danger"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i> -->
                                    <i class="ki-duotone ki-abstract-8 fs-3" :class="{'text-gray-600': item.pageviews < 100, 'text-success': item.pageviews >= 100}"><i class="path1"></i><i class="path2"></i></i>
                                </div>
                                <div class="d-flex align-items-center">
                                    <app-link :to="{name: 'post', params: {slug: item.slug}}" class="fw-bold text-gray-800 px-3">{{ item.title }}</app-link>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <Banner location="sidebar2.view"/>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"
import Banner from "@/components/Ad/Banner.vue"

export default defineComponent({
    name: 'Sidebar',
    components: {
        Banner,
    },
    computed: {
        firstDay() {
            if (this.$root.config?.postLatest) {
                let firstDay = Object.keys(this.$root.config.postLatest)[0]

                return firstDay
            } else {
                return null
            }
        },
        postLatestChunk1() {
            if (this.$root.config?.postLatest) {
                const chunk = {}
                chunk[this.firstDay] = this.$root.config.postLatest[this.firstDay]

                return chunk
            } else {
                return {}
            }
        },
        postLatestChunk2() {
            if (this.$root.config?.postLatest) {
                const chunk2 = {...this.$root.config.postLatest}
                delete chunk2[this.firstDay]
                return chunk2
            } else {
                return {}
            }
        }
    },
})
</script>