<template>
    <div v-if="eventLayout" class="card card-flush overflow-hidden mb-6" data-testid="event-card">
        <div class="card-body p-5 p-md-7">
            <app-link :to="{name: 'post', params: {slug: item.slug}}" class="fs-2 fs-md-1 fw-bold text-gray-900 text-hover-primary d-block mb-5">
                {{ item.title }}
                <span v-if="item.article_type" class="badge badge-primary align-middle">{{ $t(`article_type_${item.article_type}`) }}</span>
            </app-link>

            <EventMeta :item="item" class="mb-6"/>

            <div v-if="item.summary" class="fs-6 fw-normal text-gray-800 mb-5" v-html="item.summary"></div>

            <app-link v-if="item.image" :to="{name: 'post', params: {slug: item.slug}}" class="event-cover d-block position-relative overflow-hidden rounded-3" :class="{'event-cover--default': $isDefaultEventImage(item.image)}">
                <picture class="event-cover__picture">
                    <source media="(max-width: 500px)" :srcset="eventCoverImage(item.image_sm)" />
                    <source media="(min-width: 501px)" :srcset="eventCoverImage(item.image_md)" />
                    <img :src="eventCoverImage(item.image_md)" :alt="item.title" class="event-cover__image object-fit-contain z-index-1 position-relative mh-500px min-h-250px w-100" loading="lazy"/>
                </picture>
                <img v-if="!$isDefaultEventImage(item.image)" :src="$storage(item.image_blur)" class="blurry" loading="lazy"/>
            </app-link>
        </div>
    </div>
    <div v-else class="card card-flush overflow-hidden mb-6">
        <div class="d-none d-sm-block">
            <div class="card-header px-6 pt-0 pb-0 ribbon ribbon-end">
                <div v-if="$route.name !== 'category' && item.categoriesSlugs?.length && item.categoriesSlugs.includes('sobitiya')" class="ribbon-label bg-primary fw-bold">
                    <i class="ki-duotone ki-calendar fs-1 text-white me-1"><span class="path1"></span><span class="path2"></span></i>
                    <VDate :datetime="new Date(item.event_date)" :dateOnly="true"/>
                </div>
                <div class="d-flex align-items-center text-truncate">
                    <div class="symbol symbol-40px me-5">
                        <img :src="$storage(item.avatar_sm)" class="object-fit-cover rounded-3" loading="lazy"/>
                    </div>
                    <div class="flex-grow-1 text-truncate">
                        <app-link :to="{name: 'user', params: {slug: item.user_id}}" class="text-gray-800 text-hover-primary fs-4 fw-bold text-truncate">{{ item.name }}</app-link>
                        <span class="text-gray-600 fw-semibold d-block"><VDate :datetime="new Date(item.created_at)"/></span>
                    </div>
                </div>
            </div>

            <div class="card-body px-0 py-0">
                <app-link :to="{name: 'post', params: {slug: item.slug}}" class="fs-3 fw-semibold text-gray-900 text-hover-primary d-block px-6 mb-3">{{ item.title }} <span v-if="item.article_type" class="badge badge-primary align-middle">{{ $t(`article_type_${item.article_type}`) }}</span></app-link>
                <div v-if="item.summary" class="fs-6 fw-normal text-gray-800 px-6 mb-4" v-html="item.summary"></div>
                <app-link v-if="item.image" :to="{name: 'post', params: {slug: item.slug}}" class="d-block position-relative overflow-hidden">
                    <picture>
                        <source media="(max-width: 500px)" :srcset="$storage(item.image_sm)" />
                        <source media="(min-width: 501px)" :srcset="$storage(item.image_md)" />
                        <img :src="$storage(item.image_md)" :alt="item.title" class="object-fit-contain z-index-1 position-relative mh-500px min-h-250px w-100" loading="lazy"/>
                    </picture>
                    <img :src="$storage(item.image_blur)" class="blurry" loading="lazy"/>
                </app-link>
            </div>

            <div class="card-footer px-6 py-0">
                <ul class="nav py-1">
                    <li class="nav-item">
                        <!-- <a class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary btn-active-light-primary fw-bold px-4 me-1">
                            <i class="ki-duotone ki-message-text-2 fs-2 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> 3 Comments
                        </a> -->
                    </li>
                    <li class="nav-item">
                        <span class="py-3 fw-bold px-0 me-1 d-flex flex-center text-muted">
                            <i class="ki-duotone ki-eye fs-2 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> {{ item.pageviews ?? 0 }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="d-block d-sm-none">
            <div class="card-body px-0 py-0 relative">
                <div class="d-flex flex-row mw-100">
                    <app-link v-if="item.image" :to="{name: 'post', params: {slug: item.slug}}" class="d-block position-relative overflow-hidden max-w-125px w-125px flex-shrink-0">
                        <img :src="$storage(item.image_sm)" :alt="item.title" class="object-fit-cover z-index-1 position-relative mh-300px min-h-75px h-100 max-h-300px w-100" loading="lazy"/>
                    </app-link>
                    
                    <div class="py-4 flex-shrink-1 d-flex flex-column justify-content-between">
                        <div v-if="$route.name !== 'category' && item.categoriesSlugs?.length && item.categoriesSlugs.includes('sobitiya')" class="ribbon-label bg-primary fw-bold">
                            <i class="ki-duotone ki-calendar fs-1 text-white me-1"><span class="path1"></span><span class="path2"></span></i>
                            <VDate :datetime="new Date(item.event_date)" :dateOnly="true"/>
                        </div>
                        <app-link :to="{name: 'post', params: {slug: item.slug}}" class="fs-5 fw-semibold text-gray-900 text-hover-primary d-block px-4 mb-3">{{ item.title }} <span v-if="item.article_type" class="badge badge-primary align-middle">{{ $t(`article_type_${item.article_type}`) }}</span></app-link>

                        <div class="px-4 d-flex align-items-center flex-wrap">   
                            <app-link :to="{name: 'user', params: {slug: item.user_id}}" class="text-gray-800 text-hover-primary fs-6 fw-bold me-2">
                                {{ item.name }}
                            </app-link>
                            <VDate class="text-gray-600 fw-semibold d-block" :datetime="new Date(item.created_at)"/>
                        </div>
                    </div>
                    <app-link v-if="$route.name === 'category' && item.categoriesSlugs?.length && item.categoriesSlugs.includes('sobitiya')" :to="{name: 'post', params: {slug: item.slug}}" class="w-100px flex-shrink-0 d-flex flex-column justify-content-center align-items-center bg-light-primary text-primary fs-1 fw-bolder text-capitalize">
                        <div>{{ $dayjs(item.event_date).format('DD') }}</div>
                        <div>{{ $dayjs(item.event_date).format('MMM') }}</div>
                        <div v-if="parseInt($dayjs(item.event_date).format('YYYY')) !== (new Date).getFullYear()">{{ $dayjs(item.event_date).format('YYYY') }}</div>
                    </app-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"
import EventMeta from "@/components/Post/EventMeta.vue"

export default defineComponent({
    name: 'Card',
    components: {
        EventMeta,
    },
    props: {
        item: {
            type: Object,
            required: true,
        },
        eventLayout: {
            type: Boolean,
            default: false,
        },
    },
    created() {
        // console.log(this.$route.name)
        // console.log(this.item)
    },
    data() {
        return {
            
        }
    },
    methods: {
        eventCoverImage(path) {
            return this.$isDefaultEventImage(this.item.image)
                ? this.$eventImage(this.item.image)
                : this.$eventImage(path)
        },
    }
})
</script>

<style scoped>
.event-cover--default {
    min-height: 120px;
    padding: 26px;
    background: #fff;
}

.event-cover--default .event-cover__picture {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.event-cover--default .event-cover__image {
    width: 100%;
    height: 68px;
    min-height: 0 !important;
    max-height: 68px;
}

@media (max-width: 575.98px) {
    .event-cover--default {
        min-height: 100px;
        padding: 22px;
    }

    .event-cover--default .event-cover__image {
        height: 56px;
        max-height: 56px;
    }
}
</style>
