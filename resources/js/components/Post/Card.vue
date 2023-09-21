<template>
    <div class="card card-flush mb-6 overflow-hidden">
        <div class="card-header px-6 pt-0 pb-0">
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
            <app-link :to="{name: 'post', params: {slug: item.slug}}" class="fs-3 fw-semibold text-gray-900 text-hover-primary d-block px-6 mb-3">{{ item.title }}</app-link>
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
    components: {
        
    },
    data() {
        return {
            
        }
    },
    methods: {
        
    }
})
</script>