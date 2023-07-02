<template>
    <div class="d-flex flex-column-reverse h-100">
        <div class="pb-10 position-sticky bottom-0">
            <div class="card card-flush mb-6">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">{{ $t('Last News') }}</span>
                    </h3>
                </div>

                <div class="card-body pt-0">
                    <template v-for="(posts, date) in $root.config.postLatest">
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

            <div class="card overflow-hidden">
                <div class="card-body p-0">
                    <app-link :to="banner.url" class="w-100">
                        <img :src="$storage(banner.image)" class="object-fit-contain bg-light w-100 min-h-275px max-h-500px" :alt="banner.url"/>
                    </app-link>
                    <!-- <div class="mb-2">
                        <h1 class="fw-semibold text-gray-800 text-center lh-lg">           
                            Have you tried <br> new
                            <span class="fw-bolder"> Mobile Application ?</span>
                        </h1>

                        <div class="py-10 text-center">
                            <img src="/assets/media/svg/illustrations/easy/1.svg" class="theme-light-show w-200px" alt="">
                            <img src="/assets/media/svg/illustrations/easy/1-dark.svg" class="theme-dark-show w-200px" alt="">
                        </div>
                    </div>
                    <div class="text-center mb-1"> 
                        <a class="btn btn-sm btn-primary me-2" data-bs-target="#kt_modal_new_card" data-bs-toggle="modal">Try now</a>
                        <a class="btn btn-sm btn-light" href="">Learn more</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"

export default defineComponent({
    name: 'Sidebar',
    components: {
        
    },
    props: {
    },
    data() {
        return {
            banner: {}
        }
    },
    created() {
        if (Object.keys(this.$root.config.banners).length && this.$root.config.banners['sidebar.view'] && this.$root.config.banners['sidebar.view'].length) {
            let key = Math.floor(Math.random() * this.$root.config.banners['sidebar.view'].length)
            this.banner = this.$root.config.banners['sidebar.view'][key]
            // console.log(this.banner)
        }
    },
    methods: {

    }
})
</script>