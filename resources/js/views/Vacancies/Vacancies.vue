<template>
    <div class="row">
        <div class="col-lg-7">
            <PullRefresh v-model="loadingPull" @refresh="pullRefresh" :pulling-text="$t('Pull down to refresh...')" :loosing-text="$t('Release to refresh...')" :loading-text="$t('Loading...')" success-text="">
                <div v-if="loading && !vacancies.length">
                    <div v-for="n in 4">
                        <CardSkeleton/>
                    </div>
                </div>
                <div v-else>
                    <div v-for="(item, index) in vacancies" :key="item.uuid">
                        <intersection-observer
                            v-if="index == vacancies.length - 9 && cursor && !loading"
                            :sentinal-name="'vacancies' + cursor"
                            @on-intersection-element="fetchData"
                        ></intersection-observer>

                        <Card :item="item" :is="item.uuid"/>
                    </div>

                    <intersection-observer
                        v-if="cursor && !loading"
                        :sentinal-name="'vacancies' + cursor"
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
import Card from "@/components/Vacancy/Card.vue"
import CardSkeleton from "@/components/Vacancy/CardSkeleton.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"
import Sidebar from "@/components/Sidebar.vue"
import PullRefresh from '@/components/PullRefresh.vue'

export default defineComponent({
    name: "Vacancies",
    components: {
        Card,
        CardSkeleton,
        IntersectionObserver,
        Sidebar,
        PullRefresh,
    },
    data() {
        return {
            loading: false,
            loadingPull: false,
            vacancies: [],
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

        await this.$api('vacancies', false, {
            params: {
                cursor: this.cursor
            }
        })
        .then(({data}) => {
            if (!data.ok) return

            if (!this.vacancies.length) {
                this.vacancies = data.vacancies.data
            } else {
                data.vacancies.data.map((item) => this.vacancies.push(item))
            }

            this.cursor = data.vacancies.next_cursor
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
            this.vacancies = []
            this.cursor = null
            await this.fetchData()
            this.loadingPull = false
        },
        fetchData() {
            this.loading = true

            return this.$api('vacancies', false, {
                params: {
                    cursor: this.cursor
                }
            })
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                if (!this.vacancies.length) {
                    this.vacancies = data.vacancies.data
                } else {
                    data.vacancies.data.map((item) => this.vacancies.push(item))
                }

                this.cursor = data.vacancies.next_cursor
            })
        },
        reset() {
            this.vacancies = []
            this.cursor = null

            this.fetchData()
        },
    },
});
</script>