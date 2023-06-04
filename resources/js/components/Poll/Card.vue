<template>
    <div class="card card-flush mb-6">
        <div class="card-body px-0 pt-6 pb-0">
            <div class="px-6 mb-4">
                <app-link :to="{name: 'poll', params: {slug: item.slug}}" class="fs-3 fw-semibold text-gray-900 text-hover-primary d-block mb-4">{{ item.question }}</app-link>
                <div v-if="item.summary" class="fs-6 fw-normal text-gray-800" v-html="item.summary"></div>
            </div>
            <app-link v-if="item.image" :to="{name: 'poll', params: {slug: item.slug}}">
                <img :src="$url('/storage/' + item.image)" class="object-fit-cover h-100 min-h-250px w-100" loading="lazy"/>
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