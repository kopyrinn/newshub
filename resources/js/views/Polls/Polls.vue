<template>
    <div class="row g-6 g-xl-9">
        <div class="col-lg-7">
            <div v-if="loading && !polls.length">
                <div v-for="n in 4">
                    <CardSkeleton/>
                </div>
            </div>  
            <div v-else>
                <div v-for="(item, index) in polls" :key="item.uuid">
                    <intersection-observer
                        v-if="index == polls.length - 9 && cursor && !loading"
                        :sentinal-name="'polls' + cursor"
                        @on-intersection-element="fetchData"
                    ></intersection-observer>

                    <Card :item="item" :is="item.uuid"/>
                </div>

                <intersection-observer
                    v-if="cursor && !loading"
                    :sentinal-name="'polls' + cursor"
                    @on-intersection-element="fetchData"
                ></intersection-observer>
            </div>
        </div>
        <div class="col-lg-5">
            <Sidebar/>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import Card from "@/components/Poll/Card.vue"
import CardSkeleton from "@/components/Poll/CardSkeleton.vue"
import Sidebar from "@/components/Sidebar.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"

export default defineComponent({
    name: "Polls",
    components: {
        Card,
        CardSkeleton,
        Sidebar,
        IntersectionObserver,
    },
    data() {
        return {
            loading: false,
            polls: [],
            cursor: null,
        }
    },
    head() {
        return {
            title: this.$root.meta.title
        }
    },
    async serverPrefetch() {
        this.loading = false

        await this.$api('polls', false, {
            params: {
                cursor: this.cursor
            }
        })
        .then(({data}) => {
            if (!data.ok) return

            if (!this.polls.length) {
                this.polls = data.polls.data
            } else {
                data.polls.data.map((item) => this.polls.push(item))
            }

            this.cursor = data.polls.next_cursor
        })
        .catch((e) => {})
    },
    created() {
        if (!import.meta.env.SSR) {
            this.fetchData()
        }
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api('polls', false, {
                params: {
                    cursor: this.cursor
                }
            })
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                if (!this.polls.length) {
                    this.polls = data.polls.data
                } else {
                    data.polls.data.map((item) => this.polls.push(item))
                }

                this.cursor = data.polls.next_cursor
            })
        },
        reset() {
            this.polls = []
            this.cursor = null

            this.fetchData()
        },
    },
});
</script>