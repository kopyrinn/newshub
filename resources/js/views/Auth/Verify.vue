<template>
    <div class="card card-flush mb-6">
        <div class="card-body p-0">
            <div class=" d-flex flex-column flex-lg-row flex-column-fluid">

                <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
                    <div class="d-flex justify-content-center flex-column-fluid flex-column w-100 mw-450px">
                        <div class="py-20">
                            <div class="w-100">
                                <div class="card-body">
                                    <div class="text-center mb-6">
                                        <h1 class="text-dark mb-5 fs-3x position-relative">{{ $t('Email Verification') }}</h1>
                                        <div class="fw-semibold fs-6">
                                            <span v-if="loading" class="text-gray-400 spinner-border spinner-border-lg align-middle"></span>
                                        </div>
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
    name: 'Verify',
    data() {
        return {
            token: this.$route.params.slug,
            loading: true,
        }
    },
    created() {
        this.fetchData()
    },
    methods: {
        fetchData() {
            this.$post('account/verify', {token: this.token}).then(({data}) => {
                if (data.ok) {
                    ElNotification({
                        type: 'success',
                        title: this.$t('Notification'),
                        message: data.message,
                        duration: 2000,
                    })
                } else {
                    ElNotification({
                        type: 'error',
                        title: this.$t('Notification'),
                        message: data.message,
                        duration: 2000,
                    })
                }

                if (this.$root.token) {
                    this.$router.push({name: 'index'})
                } else {
                    this.$router.push({name: 'login'})
                }
            }).catch((e) => {})
        },
    },
})
</script>