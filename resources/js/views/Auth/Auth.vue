<template>
    <div class="card card-flush mb-6">
        <div class="card-body p-0">
            <div class=" d-flex flex-column flex-lg-row flex-column-fluid">
                <!--begin::Logo-->
                <!-- <a href="/" class="text-reset d-block d-lg-none mx-auto pt-10">
                    <img src="/assets/logo.png" class="w-200px"/>
                </a> -->
                <!--end::Logo-->
                <!--begin::Aside-->
                <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
                    <!--begin::Wrapper-->
                    <div v-if="action == 'login'" class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                        <!--begin::Header-->
                        <div class="d-flex flex-stack py-2">
                            <!--begin::Back link-->
                            <div class="me-2"></div>
                            <!--end::Back link-->
                            <!--begin::Sign Up link-->
                            <div class="m-0">
                                <span class="text-gray-400 fw-bold fs-5 me-2">{{ $t('Not registered yet') }}?</span>
                                <a href="" @click.prevent="setAction('register')" class="link-primary fw-bold fs-5">{{ $t('Register') }}</a>
                            </div>
                            <!--end::Sign Up link=-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="py-20">
                            <!--begin::Form-->
                            <div class=" w-100">
                                <!--begin::Body-->
                                <div class="card-body">
                                    <!--begin::Heading-->
                                    <div class="text-start mb-6">
                                        <!--begin::Title-->
                                        <h1 class="text-dark mb-3 fs-3x" data-kt-translate="sign-in-title">{{ $t('Login') }}</h1>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <div class="text-gray-400 fw-semibold fs-6" data-kt-translate="general-desc"></div>
                                        <!--end::Link-->
                                    </div>
                                    <!--begin::Heading-->
                                    <!--begin::Input group=-->
                                    <div class="form-floating mb-6">
                                        <!--begin::Email-->
                                        <input type="text" placeholder="Email" v-model="form.email" @keyup.enter="login" autocomplete="off" data-kt-translate="sign-in-input-email" class="form-control form-control-solid" />
                                        <label class="form-label required">Email</label>
                                        <!--end::Email-->
                                        <div v-if="errors.email && errors.email.length" class="fv-plugins-message-container invalid-feedback d-block">
                                            <span v-for="(error, index) in errors.email" v-bind:key="index">{{ error }}</span>
                                        </div>
                                    </div>
                                    <!--end::Input group=-->
                                    <div class="form-floating mb-6">
                                        <!--begin::Password-->
                                        <input type="password" :placeholder="$t('Password')" v-model="form.password" @keyup.enter="login" autocomplete="off" data-kt-translate="sign-in-input-password" class="form-control form-control-solid" />
                                        <label class="form-label required">{{ $t('Password') }}</label>
                                        <!--end::Password-->
                                        <div v-if="errors.password && errors.password.length" class="fv-plugins-message-container invalid-feedback d-block">
                                            <span v-for="(error, index) in errors.password" v-bind:key="index">{{ error }}</span>
                                        </div>
                                    </div>
                                    <!--end::Input group=-->
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-6">
                                        <div></div>
                                        <!--begin::Link-->
                                        <a href="" @click.prevent="setAction('reset')" class="link-primary">{{ $t('Forgot Your Password?') }}</a>
                                        <!--end::Link-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Actions-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Submit-->
                                        <button @click="login" type="button" class="btn btn-primary me-2 flex-shrink-0 rounded-4" :data-kt-indicator="loading" :disabled="loading">
                                            <!--begin::Indicator label-->
                                            <span class="indicator-label" data-kt-translate="sign-in-submit">{{ $t('Login') }}</span>
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
                                <!--begin::Body-->
                            </div>
                            <!--end::Form-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Wrapper-->
                    <div v-else-if="action == 'register'" class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                        <!--begin::Header-->
                        <div class="d-flex flex-stack py-2">
                            <!--begin::Back link-->
                            <div class="me-2">
                                <a href="" @click.prevent="setAction('login')" class="btn btn-icon bg-light rounded-circle">
                                    <!--begin::Svg Icon | path: -->
                                    <span class="svg-icon svg-icon-2 svg-icon-gray-800">
                                        <inline-svg width="24" height="24" src="/assets/media/icons/duotune/arrows/arr002.svg"/>
                                    </span>
                                    <!--end::Svg Icon-->
                                </a>
                            </div>
                            <!--end::Back link-->
                            <!--begin::Sign Up link-->
                            <div class="m-0">
                                <span class="text-gray-400 fw-bold fs-5 me-2" data-kt-translate="sign-up-head-desc">{{ $t('Already registered') }} ?</span>
                                <a href="" @click.prevent="setAction('login')" class="link-primary fw-bold fs-5" data-kt-translate="sign-up-head-link">{{ $t('Login') }}</a>
                            </div>
                            <!--end::Sign Up link=-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="py-0">
                            <!--begin::Form-->
                            <div class="form w-100">
                                <!--begin::Heading-->
                                <div class="text-start mb-6">
                                    <!--begin::Title-->
                                    <h1 class="text-dark mb-3 fs-3x" data-kt-translate="sign-up-title">{{ $t('Create an account') }}</h1>
                                    <!--end::Title-->
                                    <!--begin::Text-->
                                    <div class="text-gray-400 fw-semibold fs-6" data-kt-translate="general-desc"></div>
                                    <!--end::Link-->
                                </div>
                                <div class="form-floating mb-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" :placeholder="$t('Name')" v-model="form.name" @keyup.enter="register" autocomplete="off" />
                                    <label class="form-label required">{{ $t('Name') }}</label>
                                    <div v-if="errors.name && errors.name.length" class="fv-plugins-message-container invalid-feedback d-block">
                                        <span v-for="(error, index) in errors.name" v-bind:key="index">{{ error }}</span>
                                    </div>
                                </div>
                                <div class="form-floating mb-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" :placeholder="$t('Phone')" v-model="form.phone" @keyup.enter="register" autocomplete="off" />
                                    <label class="form-label required">{{ $t('Phone') }}</label>
                                    <div v-if="errors.phone && errors.phone.length" class="fv-plugins-message-container invalid-feedback d-block">
                                        <span v-for="(error, index) in errors.phone" v-bind:key="index">{{ error }}</span>
                                    </div>
                                </div>
                                <div class="form-floating mb-6">
                                    <input class="form-control form-control-lg form-control-solid" type="email" placeholder="Email" v-model="form.email" @keyup.enter="register" autocomplete="off" data-kt-translate="sign-up-input-email" />
                                    <label class="form-label required">Email</label>
                                    <div v-if="errors.email && errors.email.length" class="fv-plugins-message-container invalid-feedback d-block">
                                        <span v-for="(error, index) in errors.email" v-bind:key="index">{{ error }}</span>
                                    </div>
                                </div>
                                <div class="fv-row mb-6" data-kt-password-meter="true">
                                    <div class="mb-1">
                                        <div class="form-floating mb-3">
                                            <input class="form-control form-control-lg form-control-solid" type="password" :placeholder="$t('Password')"  v-model="form.password" @keyup.enter="register"/>
                                            <label class="form-label required">{{ $t('Password') }}</label>
                                            <div v-if="errors.password && errors.password.length" class="fv-plugins-message-container invalid-feedback d-block">
                                                <span v-for="(error, index) in errors.password" v-bind:key="index">{{ error }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2" :class="{'active': strength > 0}"></div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2" :class="{'active': strength > 1}"></div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2" :class="{'active': strength > 2}"></div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px" :class="{'active': strength > 3}"></div>
                                        </div>
                                    </div>
                                    <div class="text-muted" data-kt-translate="sign-up-hint">{{ $t('Use 8 or more characters with letters, numbers, and symbols') }}</div>
                                </div>
                                
                                <div class="form-floating mb-6">
                                    <input class="form-control form-control-lg form-control-solid" type="password" :placeholder="$t('Password confirmation')" v-model="form.password_confirmation" @keyup.enter="register" autocomplete="off" />
                                    <label class="form-label required">{{ $t('Password confirmation') }}</label>
                                </div>

                                <div class="mb-6">
                                    <label class="form-label">{{ $t('Account type') }}</label>

                                    <input type="radio" class="btn-check" v-model="form.role" value="journalist" checked="checked"  id="role-journalist"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-5" for="role-journalist">
                                        <i class="ki-duotone ki-user-tick fs-4x me-4"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>

                                        <span class="d-block fw-semibold text-start">
                                            <span class="text-dark fw-bold d-block fs-3">{{ $t('Journalist') }}</span>
                                            <span class="text-muted fw-semibold fs-6">
                                                {{ $t('Journalist role.') }}
                                            </span>
                                        </span>
                                    </label>

                                    <input type="radio" class="btn-check" v-model="form.role" value="press" id="role-press"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center" for="role-press">
                                        <i class="ki-duotone ki-security-user fs-4x me-4"><i class="path1"></i><i class="path2"></i></i>

                                        <span class="d-block fw-semibold text-start">
                                            <span class="text-dark fw-bold d-block fs-3">{{ $t('Press Center') }}</span>
                                            <span class="text-muted fw-semibold fs-6">{{ $t('Press center role.') }}</span>
                                        </span>
                                    </label>
                                </div>
                                <div class="d-flex flex-stack">
                                    <button @click="register" type="button" class="btn btn-primary rounded-4" :data-kt-indicator="loading" :disabled="loading">
                                        <span class="indicator-label">{{ $t('Send') }}</span>
                                        <span class="indicator-progress">{{ $t('Please, wait') }}...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else-if="action == 'reset'" class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
                        <div class="d-flex flex-stack py-2">
                            <div class="me-2">
                                <a href="" @click.prevent="setAction('login')" class="btn btn-icon bg-light rounded-circle">
                                    <span class="svg-icon svg-icon-2 svg-icon-gray-800">
                                        <inline-svg width="24" height="24" src="/assets/media/icons/duotune/arrows/arr002.svg"/>
                                    </span>
                                </a>
                            </div>
                            <div class="m-0">
                                <span class="text-gray-400 fw-bold fs-5 me-2" data-kt-translate="password-reset-head-desc">
                                    {{ $t('Already registered') }} ?
                                </span>

                                <a href="" @click.prevent="setAction('login')" class="link-primary fw-bold fs-5" data-kt-translate="password-reset-head-link">
                                    {{ $t('Login') }}
                                </a>
                            </div>
                        </div>
                        <div class="py-20 mb-20">
                            <div class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="text-start mb-6">
                                    <!--begin::Title-->
                                    <h1 class="text-dark mb-3 fs-3x" data-kt-translate="password-reset-title">
                                        {{ $t('Reset Password') }}
                                    </h1>
                                    <!--end::Title-->

                                    <!--begin::Text-->
                                    <div class="text-gray-400 fw-semibold fs-6" data-kt-translate="password-reset-desc">
                                        {{ $t('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                                    </div>
                                    <!--end::Link-->
                                </div>
                                <!--begin::Heading-->

                                <!--begin::Input group-->
                                <div class="form-floating mb-6 fv-plugins-icon-container">
                                    <!--begin::Email-->
                                    <input type="text" placeholder="Email" v-model="form.email" @keyup.enter="reset" autocomplete="off" data-kt-translate="sign-in-input-email" class="form-control form-control-solid" />
                                    <label class="form-label required">Email</label>
                                    <!--end::Email-->
                                    <div v-if="errors.email && errors.email.length" class="fv-plugins-message-container invalid-feedback d-block">
                                        <span v-for="(error, index) in errors.email" v-bind:key="index">{{ error }}</span>
                                    </div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Actions-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Link-->
                                    <div class="m-0">
                                        <button type="button" @click="reset" class="btn btn-primary rounded-4 me-2" :data-kt-indicator="loading" :disabled="loading">
                                            <!--begin::Indicator label-->
                                            <span class="indicator-label">{{ $t('Send') }}</span>
                                            <!--end::Indicator label-->

                                            <!--begin::Indicator progress-->
                                            <span class="indicator-progress">{{ $t('Please, wait') }}... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            <!--end::Indicator progress-->
                                        </button>

                                        <a href="" @click.prevent="setAction('login')" class="btn btn-lg btn-light-primary rounded-4 fw-bold">{{ $t('Cancel') }}</a>
                                    </div>
                                    <!--end::Link-->
                                </div>
                                <!--end::Actions-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Aside-->
                <!--begin::Body-->
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100"> 
                    <!--begin::Image-->                
                    <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-6 mb-lg-20" src="/assets/media/illustrations/sketchy-1/1.png" alt="">    
                    <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-6 mb-lg-20" src="/assets/media/illustrations/sketchy-1/1.png" alt="">                 
                    <!--end::Image-->

                    <!--begin::Title-->
                    <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-6"> 
                        {{ $t('Fast and efficient') }}
                    </h1>  
                    <!--end::Title-->

                    <!--begin::Text-->
                    <div class="text-gray-600 fs-base text-center fw-semibold mb-20 mb-lg-0">
                        {{ $t('NewsHub.kz allows you to use media resources quickly and efficiently') }}
                    </div>
                    <!--end::Text-->
                </div>
                <!-- <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat" style="background-image: url(/assets/media/illustrations/sketchy-1/1.png)"></div> -->
                <!--begin::Body-->
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"

export default defineComponent({
    name: 'Auth',
    data() {
        return {
            action: 'login',
            loading: false,
            form: {
                name: '',
                email: '',
                phone: '',
                role: 'journalist',
                password: '',
                password_confirmation: '',
            },
            errors: {
                name: [],
                phone: [],
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
        // document.body.classList.remove('app-default')
        // document.body.classList.add('app-blank')
    },
    mounted() {
        // this.telegramAuth()
    },
    beforeUnmount() {
        // document.body.classList.remove('app-blank')
        // document.body.classList.add('app-default')
    },
    watch: {
        'form.name': function() {
            if (this.errors.name && this.errors.name.length) {
                this.errors.name = []
            }
        },
        'form.email': function() {
            if (this.errors.email && this.errors.email.length) {
                this.errors.email = []
            }
        },
        'form.phone': function() {
            if (this.errors.phone && this.errors.phone.length) {
                this.errors.phone = []
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
        setAction(action) {
            this.action = action
            
            // this.$nextTick(() => {
            //     if (action == 'login') {
            //         this.telegramAuth()
            //     } else {
            //         this.telegramRegister()
            //     }
            // })
        },
        // telegramAuth() {
        //     const script = document.createElement('script')
        //     script.async = true
        //     script.src = 'https://telegram.org/js/telegram-widget.js?21'
        //     script.setAttribute('data-size', 'large')
        //     script.setAttribute('data-userpic', false)
        //     script.setAttribute('data-telegram-login', import.meta.env.VITE_TELEGRAM)
        //     script.setAttribute('data-radius', 10)
        //     script.setAttribute('data-request-access', 'write')
        //     window.onTelegramAuth = this.onTelegramAuth
        //     script.setAttribute('data-onauth', 'window.onTelegramAuth(user)')
        //     this.$refs.telegram.appendChild(script)
        // },
        // telegramRegister() {
        //     const script = document.createElement('script')
        //     script.async = true
        //     script.src = 'https://telegram.org/js/telegram-widget.js?21'
        //     script.setAttribute('data-size', 'large')
        //     script.setAttribute('data-userpic', false)
        //     script.setAttribute('data-telegram-login', import.meta.env.VITE_TELEGRAM)
        //     script.setAttribute('data-radius', 10)
        //     script.setAttribute('data-request-access', 'write')
        //     window.onTelegramAuth = this.onTelegramAuth
        //     script.setAttribute('data-onauth', 'window.onTelegramAuth(user)')
        //     this.$refs.telegramreg.appendChild(script)
        // },
        // onTelegramAuth(user) {
        //     this.$api('telegram', false, {
        //         method: 'post',
        //         data: user
        //     }).then(({data}) => {
        //         if (data.ok) {
        //             this.$store.commit('setToken', data.token)
        //             this.$store.commit('setUser', data.user)
        //             this.$nextTick(this.$root.initUser)
        //         }
        //     }).catch((e) => {})
        // },
        login() {
            this.loading = true
            this.$api('login', false, {
                method: 'post',
                data: this.form
            })
            .then(({data}) => {
                this.loading = false

                if (data.ok) {
                    this.$store.commit('setToken', data.token)
                    this.$store.commit('setUser', data.user)
                    this.$root.initUser()
                    this.$router.push({name: 'index'})
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
        register() {
            this.$api('register', false, {
                method: 'post',
                data: this.form
            })
            .then(({data}) => {
                if (data.ok) {
                    this.$store.commit('setToken', data.token)
                    this.$store.commit('setUser', data.user)
                    this.$root.initUser()
                    this.$router.push({name: 'index'})
                } else {
                    this.errors = {...data.errors}
                }
            })
            .catch(({response}) => {
                // if (response.status === 200) {
                    this.errors = {...response.data.errors}
                // }
            })
        },
        reset() {
            this.$api('reset', false, {
                method: 'post',
                data: this.form
            })
            .then(({data}) => {
                if (data.ok) {
                    this.message = "Мы отправили на ваш Email новые доступы к аккаунту"
                } else {
                    this.errors = {...data.errors}
                }
            })
            .catch(({response}) => {
                // if (response.status === 200) {
                    this.errors = {...response.data.errors}
                // }
            })
        },
    },
})
</script>