<template>
    <div class="card card-flush mb-6 overflow-hidden">
        <div class="card-body px-6">
            <div class="fw-semibold">
                <h3 class="fs-5 mb-6">{{ title }}</h3>
                <div v-for="(item, index) in items" class="d-flex align-items-center text-truncate" :class="{'mb-5': index + 1 < items.length}">
                    <img :src="$storage(item.image_blur)" class="min-w-60px w-60px min-h-40px h-40px me-5 object-fit-cover h-100 rounded" loading="lazy"/>
                    <div class="text-truncate">
                        <app-link :to="{name: 'post', params: {slug: item.slug}}" class="text-gray-800 text-hover-primary fs-4 fw-bold text-truncate">{{ item.title }}</app-link>
                        <span class="text-gray-600 fw-semibold d-block"><VDate :datetime="new Date(item.created_at)"/></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"

export default defineComponent({
    name: 'RecommendedPosts',
    props: {
        items: {
            type: Array,
            required: true,
        },
        title: {
            type: String,
            required: true
        },
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