<template>
    <div class="card card-flush mb-6">
        <div class="card-body p-0">
            <div class=" d-flex flex-column flex-lg-row flex-column-fluid">

                <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
                    <div class="d-flex justify-content-center flex-column-fluid flex-column w-100 mw-450px">
                        <div class="py-20">
                            <div class="w-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center flex-column mb-6">
                                        <button v-if="!done" class="btn d-block btn-lg btn-light-primary" :disabled="loading" @click="unsubscribe">{{ $t('Unsubscribe Email') }}</button>
                                        <app-link v-else to="/" class="d-block  btn btn-lg btn-light-success">{{ $t('Go home') }}</app-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"
import { ElNotification } from 'element-plus'

export default defineComponent({
    name: 'Unsubscribe',
    data() {
        return {
            token: this.$route.params.slug,
            loading: false,
            done: false,
        }
    },
    methods: {
        unsubscribe() {
            this.loading = true

            this.$get(`unsubscribe/${this.token}`).then(({data}) => {
                this.done = true

                ElNotification({
                    type: 'success',
                    title: this.$t('Notification'),
                    message: this.$t('Your Email has been successfully removed from the mailing list.'),
                    duration: 2000,
                })
            }).catch((e) => {})
        },
    },
})
</script>