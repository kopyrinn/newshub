<template>
    <div class="card card-flush mb-6 overflow-hidden">
        <div class="card-header px-6 pt-0 pb-0">
            <div class="d-flex align-items-center text-truncate">
                <div class="symbol symbol-40px me-5">
                    <img :src="$url('/storage/' + item.avatar)" class="object-fit-cover rounded-3"/>
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
            <app-link :to="{name: 'post', params: {slug: item.slug}}">
                <img v-if="item.image" :src="$url('/storage/' + item.image)" class="object-fit-cover h-100 min-h-250px w-100" loading="lazy"/>
            </app-link>
        </div>

        <!-- <div class="card-footer px-6 py-0">
            <ul class="nav py-3">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary btn-active-light-primary fw-bold px-4 me-1">
                        <i class="ki-duotone ki-message-text-2 fs-2 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> 3 Comments
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary fw-bold px-4 me-1">
                        <i class="ki-duotone ki-heart fs-2 me-1"><span class="path1"></span><span class="path2"></span></i> 8 Likes
                    </a>
                </li>
            </ul>
        </div> -->
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