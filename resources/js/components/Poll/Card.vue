<template>
    <div class="card card-flush mb-6">
        <div class="card-body px-0 pt-6 pb-0">
            <div class="px-6 mb-4">
                <app-link :to="{name: 'poll', params: {slug: item.slug}}" class="fs-3 fw-semibold text-gray-900 text-hover-primary d-block mb-4">{{ item.question }}</app-link>
                <div v-if="item.summary" class="fs-6 fw-normal text-gray-800" v-html="item.summary"></div>
            </div>

            <app-link v-if="item.image" :to="{name: 'poll', params: {slug: item.slug}}" class="d-block position-relative overflow-hidden">
                <picture>
                    <source media="(max-width: 500px)" :srcset="$storage(item.image_sm)" />
                    <source media="(min-width: 501px)" :srcset="$storage(item.image_md)" />
                    <img :src="$storage(item.image_md)" :alt="item.title" class="object-fit-contain z-index-1 position-relative mh-500px min-h-250px w-100" loading="lazy"/>
                </picture>
                <img :src="$storage(item.image_blur)" class="blurry" loading="lazy"/>
            </app-link>
        </div>

        <div class="card-footer px-6 py-0">
            <ul class="nav py-3">
                <li class="nav-item">
                    <app-link :to="{name: 'poll', params: {slug: item.slug}}" class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary btn-active-light-primary fw-bold px-4 me-1">
                        {{ item.requests_count }} {{ $t('Participant(s)')}}
                    </app-link>
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