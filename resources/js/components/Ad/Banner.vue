<template>
    <div v-if="item" class="card card-flush overflow-hidden">
        <app-link :to="item.url" class="w-100" @click="onClicked">
            <intersection-observer
                v-if="!isViewed"
                :sentinal-name="'banner-' + id"
                @on-intersection-element="onViewed"
            ></intersection-observer>
            <img :src="$storage(item.image)" class="object-fit-contain bg-light w-100 min-h-50px max-h-200px" :alt="item.url" loading="lazy"/>
        </app-link>
    </div>
</template>
<script>
import { defineComponent } from "vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"

export default defineComponent({
    name: 'Banner',
    components: {
        IntersectionObserver,
    },
    data() {
        return {
            item: false,
            id: this.$root.getRandom(),
            isViewed: false,
        }
    },
    props: {
        location: {
            type: String,
            required: true,
        }
    },
    created() {
        try {
            if (!this.$root.config.banners || !Object.keys(this.$root.config.banners).length || !this.$root.config.banners[this.location] || !this.$root.config.banners[this.location].length) return

            let key = Math.floor(Math.random() * this.$root.config.banners[this.location].length)
            this.item = this.$root.config.banners[this.location][key]
        } catch (e) {

        }
    },
    methods: {
        onViewed() {
            if (this.isViewed) return

            this.isViewed = true

            this.$post('viewed', {uuid: this.item.uuid})
        },
        onClicked() {
            this.$post('clicked', {uuid: this.item.uuid})
        },
    }
})
</script>