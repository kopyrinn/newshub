<template>
    <div class="card card-flush mb-6">
        <div class="card-body">
            <div class=" d-flex flex-column flex-lg-row flex-column-fluid">
                <!--begin::Aside-->
                <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50">
                    <div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                        <!--begin::Body-->
                        <div class="py-lg-20">
                            <!--begin::Form-->
                            <div class=" w-100">
                                <!--begin::Heading-->
                                <div class="text-start mb-6">
                                    <!--begin::Title-->
                                    <h1 class="text-dark mb-3 fs-3x" data-kt-translate="sign-in-title">{{ $t('Password reset') }}</h1>
                                    <!--end::Title-->
                                    <!--begin::Text-->
                                    <div class="text-gray-400 fw-semibold fs-6" data-kt-translate="general-desc"></div>
                                    <!--end::Link-->
                                </div>
                                <!--begin::Heading-->
                                <!--begin::Input group=-->
                                <div class="form-floating mb-6">
                                    <!--begin::Email-->
                                    <input type="text" placeholder="Email" v-model="form.email" @keyup.enter="reset" autocomplete="off" data-kt-translate="sign-in-input-email" class="form-control" readonly="" />
                                    <label class="form-label required">Email</label>
                                    <!--end::Email-->
                                    <div v-if="errors.email && errors.email.length" class="fv-plugins-message-container invalid-feedback d-block">
                                        <span v-for="(error, index) in errors.email" v-bind:key="index">{{ error }}</span>
                                    </div>
                                </div>
                                <!--end::Input group=-->
                                <div class="form-floating mb-6">
                                    <!--begin::Password-->
                                    <input type="password" :placeholder="$t('New password')" v-model="form.password" @keyup.enter="reset" autocomplete="off" data-kt-translate="sign-in-input-password" class="form-control" />
                                    <label class="form-label required">{{ $t('New password') }}</label>
                                    <!--end::Password-->
                                    <div v-if="errors.password && errors.password.length" class="fv-plugins-message-container invalid-feedback d-block">
                                        <span v-for="(error, index) in errors.password" v-bind:key="index">{{ error }}</span>
                                    </div>
                                </div>
                                <!--end::Input group=-->
                                <!--end::Input group=-->
                                <div class="form-floating mb-6">
                                    <input class="form-control form-control-lg" type="password" :placeholder="$t('Password confirmation')" v-model="form.password_confirmation" @keyup.enter="reset" autocomplete="off" />
                                    <label class="form-label required">{{ $t('Password confirmation') }}</label>
                                </div>
                                <div class="mb-6">
                                    <div class="text-gray-600 fw-bold fs-lg mb-2">{{ $t('Password strength') }}</div>
                                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                        <div
                                            class="flex-grow-1 bg-secondary rounded h-5px me-2"
                                            :class="{'active': strength > 0, 'bg-active-success': strength > 2, 'bg-active-warning': strength <= 2}"
                                        >
                                        </div>
                                        <div
                                            class="flex-grow-1 bg-secondary rounded h-5px me-2"
                                            :class="{'active': strength > 1, 'bg-active-success': strength > 2, 'bg-active-warning': strength <= 2}"
                                        >
                                        </div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2" :class="{'active': strength > 2}"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px" :class="{'active': strength > 3}"></div>
                                    </div>
                                    <div class="text-muted" data-kt-translate="sign-up-hint">{{ $t('Use 8 or more characters with letters, numbers, and symbols') }}</div>
                                </div>
                                <div class="d-flex flex-stack">
                                    <!--begin::Submit-->
                                    <button @click="reset" type="button" class="btn btn-lg w-100 w-lg-auto fw-bold btn-light-primary me-2 flex-shrink-0" :data-kt-indicator="loading" :disabled="loading">
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label" data-kt-translate="sign-in-submit">{{ $t('Reset') }}</span>
                                        <!--end::Indicator label-->
                                        <!--begin::Indicator progress-->
                                        <span class="indicator-progress">
                                            <span data-kt-translate="general-progress">{{ $t('Please, wait') }}...</span>
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                        <!--end::Indicator progress-->
                                    </button>
                                    <!--end::Submit-->
                                    <!--begin::Social-->
                                    <!-- <div class="d-flex align-items-center">
                                        <div class="text-gray-400 fw-semibold fs-6 me-3 me-md-6" data-kt-translate="general-or">Или</div>
                                        <div ref="telegram" class="d-flex align-items-center"></div>
                                    </div> -->
                                    <!--end::Social-->
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Form-->
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <!--end::Aside-->

                <div class="d-flex flex-column flex-center pt-5 p-lg-10 w-100"> 
                    <img class="mx-auto mw-100 w-225px w-lg-300px mb-6 mb-lg-20" :src="$media('illustrations/sketchy-1/1.png')" alt="">

                    <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-6"> 
                        {{ $t('Fast and efficient') }}
                    </h1>
                    <div class="text-gray-600 fs-base text-center fw-semibold mb-lg-0">
                        {{ $t('NewsHub.kz allows you to use media resources quickly and efficiently') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"
import Modal from '@/components/Modal.vue';
import { ElNotification } from "element-plus"

export default defineComponent({
    name: 'Auth',
    components: {
        Modal,
    },
    data() {
        return {
            loading: false,
            form: {
                email: '',
                password: '',
                password_confirmation: '',
            },
            errors: {
                email: [],
                password: [],
            },
            strength: 0,
        }
    },
    created() {
        if (this.$root.token) {
            this.$router.push({name: 'index'})
            return
        }

        this.form.email = this.$route.query.email
    },
    watch: {
        'form.email': function() {
            if (this.errors.email && this.errors.email.length) {
                this.errors.email = []
            }
        },
        'form.password': function(value) {
            let strength = 0
            if (value.length >= 8) {
                strength++

                if (value.match(/[\d]/g)) strength++
                if (value.match(/[A-Z]/g)) strength++
                if (value.match(/[^a-zA-Z0-9]/g)) strength++
            }

            this.strength = strength

            if (this.errors.password && this.errors.password.length) {
                this.errors.password = []
            }
        },
        'form.password_confirmation': function() {
            if (this.errors.password && this.errors.password.length) {
                this.errors.password = []
            }
        },
    },
    methods: {
        reset() {
            this.loading = true

            this.$store.commit('updateCacheKey')
            this.$api('reset', false, {
                method: 'post',
                data: {...this.form, token: this.$route.params.token}
            })
            .then(({data}) => {
                this.loading = false

                if (data.ok) {
                    ElNotification({
                        type: 'success',
                        title: this.$t('Notification'),
                        message: data.message,
                        duration: 2000,
                    })

                    this.$router.push({name: 'login'})
                } else {
                    this.errors = {...data.errors}
                }
            })
            .catch(({response}) => {
                this.loading = false

                // if (response.status === 200) {
                    this.errors = {...response.data.errors}
                // }
            })
        },
    },
})
</script>