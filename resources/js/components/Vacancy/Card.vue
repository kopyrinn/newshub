<template>
    <div class="card card-flush mb-6">
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

        <div class="card-body px-6 pt-0 pb-4">
            <app-link :to="{name: 'vacancy', params: {slug: item.id}}" class="fs-3 text-gray-900 text-hover-primary fw-semibold d-block mb-3">{{ item.job_title }}</app-link>
            <div v-if="item.task" class="fs-6 fw-normal text-gray-700" v-html="item.task"></div>
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