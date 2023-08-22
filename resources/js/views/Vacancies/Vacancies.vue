<template>
    <div class="row">
        <div class="col-lg-7">
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
        </div>
        <div class="col-lg-5">
            <Sidebar/>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import Card from "@/components/Vacancy/Card.vue"
import CardSkeleton from "@/components/Vacancy/CardSkeleton.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"
import Sidebar from "@/components/Sidebar.vue"

export default defineComponent({
    name: "Vacancies",
    components: {
        Card,
        CardSkeleton,
        IntersectionObserver,
        Sidebar,
    },
    data() {
        return {
            loading: false,
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
        this.fetchData()
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api('vacancies', false, {
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