<template>
    <div v-if="$route.name === 'category' && item.categoriesSlugs?.length && item.categoriesSlugs.includes('sobitiya')" class="card card-flush overflow-hidden mb-6">
        <div class="card-body px-0 py-0">
            <div class="d-block d-xl-none pt-5">
                <app-link :to="{name: 'post', params: {slug: item.slug}}" class="fs-1 fw-semibold text-gray-900 text-hover-primary d-block px-6 mb-3">{{ item.title }} <span v-if="item.article_type" class="badge badge-primary align-middle">{{ $t(`article_type_${item.article_type}`) }}</span></app-link>

                <div class="alert bg-light-primary d-flex flex-row align-items-center mx-6 p-4 mb-4 rounded-3">
                    <i class="ki-duotone ki-notification-bing fs-2hx text-primary me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="fs-3 fw-bold text-primary">{{ $dayjs(item.event_date).format('DD MMMM YYYY') }}</div>
                </div>

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
            <div class="d-none d-xl-flex flex-row">
                <app-link v-if="item.image" :to="{name: 'post', params: {slug: item.slug}}" class="d-block position-relative overflow-hidden max-w-175px w-175px flex-shrink-0">
                    <picture>
                        <img :src="$storage(item.image_sm)" :alt="item.title" class="object-fit-contain z-index-1 position-relative mh-175px min-h-175px h-175px max-h-175px w-100" loading="lazy"/>
                    </picture>
                    <img :src="$storage(item.image_blur)" class="blurry" loading="lazy"/>
                </app-link>
                
                <div class="py-4">
                    <app-link :to="{name: 'post', params: {slug: item.slug}}" class="fs-3 fw-semibold text-gray-900 text-hover-primary d-block px-4 mb-3">{{ item.title }} <span v-if="item.article_type" class="badge badge-primary align-middle">{{ $t(`article_type_${item.article_type}`) }}</span></app-link>
                    <div v-if="item.summary" class="fs-6 fw-normal text-gray-800 px-4 mb-4" v-html="item.summary"></div>
                </div>
                <app-link :to="{name: 'post', params: {slug: item.slug}}" class="w-100px flex-shrink-0 d-flex flex-column justify-content-center align-items-center bg-light-primary text-primary fs-1 fw-bolder text-capitalize">
                    <div>{{ $dayjs(item.event_date).format('DD') }}</div>
                    <div>{{ $dayjs(item.event_date).format('MMM') }}</div>
                    <div v-if="parseInt($dayjs(item.event_date).format('YYYY')) !== (new Date).getFullYear()">{{ $dayjs(item.event_date).format('YYYY') }}</div>
                </app-link>
            </div>
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

export default defineComponent({
    name: 'Card',
    props: {
        item: {
            type: Object,
            required: true,
        }
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
        
    }
})
</script>