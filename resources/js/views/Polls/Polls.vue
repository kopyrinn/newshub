<template>
    <div class="row g-6 g-xl-9">
        <div class="col-lg-7">
            <PullRefresh v-model="loadingPull" @refresh="pullRefresh" :pulling-text="$t('Pull down to refresh...')" :loosing-text="$t('Release to refresh...')" :loading-text="$t('Loading...')" success-text="">
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
            </PullRefresh>
        </div>
        <div class="col-lg-5">
            <Sidebar/>
            <SchemaOrgWebPage type="CollectionPage" :name="$root.meta.title" />
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import Card from "@/components/Poll/Card.vue"
import CardSkeleton from "@/components/Poll/CardSkeleton.vue"
import Sidebar from "@/components/Sidebar.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"
import PullRefresh from '@/components/PullRefresh.vue'

export default defineComponent({
    name: "Polls",
    components: {
        Card,
        CardSkeleton,
        Sidebar,
        IntersectionObserver,
        PullRefresh,
    },
    data() {
        return {
            loading: false,
            loadingPull: false,
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
        async pullRefresh() {
            this.loadingPull = true
            this.polls = []
            this.cursor = null
            await this.fetchData()
            this.loadingPull = false
        },
        fetchData() {
            this.loading = true

            return this.$api('polls', false, {
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