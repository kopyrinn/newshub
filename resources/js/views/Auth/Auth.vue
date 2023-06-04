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
                                        <input type="text" placeholder="Email" v-model="form.email" @keyup.enter="login" autocomplete="off" data-kt-translate="sign-in-input-email" class="form-control" />
                                        <label class="form-label required">Email</label>
                                        <!--end::Email-->
                                        <div v-if="errors.email && errors.email.length" class="fv-plugins-message-container invalid-feedback d-block">
                                            <span v-for="(error, index) in errors.email" v-bind:key="index">{{ error }}</span>
                                        </div>
                                    </div>
                                    <!--end::Input group=-->
                                    <div class="form-floating mb-6">
                                        <!--begin::Password-->
                                        <input type="password" :placeholder="$t('Password')" v-model="form.password" @keyup.enter="login" autocomplete="off" data-kt-translate="sign-in-input-password" class="form-control" />
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
                        <div class="d-flex flex-stack py-2 mb-4">
                            <!--begin::Back link-->
                            <div class="me-2">
                                <a href="" @click.prevent="setAction('login')" class="btn btn-icon bg-light rounded-circle">
                                    <i class="ki-duotone ki-arrow-left fs-2x"><i class="path1"></i><i class="path2"></i></i>
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
                                <div class="mb-6">
                                    <label class="form-label">{{ $t('Account type') }}</label>

                                    <div class="row g-5">
                                        <div class="col-12 col-sm-6 col-lg-12 col-xxl-6">
                                            <input type="radio" class="btn-check" v-model="form.role" value="journalist" id="role-journalist"/>
                                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary px-4 py-3 d-flex align-items-center mb-0" for="role-journalist">
                                                <i class="ki-duotone ki-user-tick fs-3x me-1"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>

                                                <span class="d-block fw-semibold text-start">
                                                    <span class="text-dark fw-bold d-block fs-3">{{ $t('Journalist') }}</span>
                                                    <span class="text-muted fw-semibold fs-6">
                                                        {{ $t('Journalist role') }}
                                                    </span>
                                                </span>
                                            </label>
                                        </div>

                                        <div class="col-12 col-sm-6 col-lg-12 col-xxl-6">
                                            <input type="radio" class="btn-check" v-model="form.role" value="press" id="role-press"/>
                                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary px-4 py-3 d-flex align-items-center mb-0" for="role-press">
                                                <i class="ki-duotone ki-security-user fs-3x me-1"><i class="path1"></i><i class="path2"></i></i>

                                                <span class="d-block fw-semibold text-start">
                                                    <span class="text-dark fw-bold d-block fs-3">{{ $t('Press Center') }}</span>
                                                    <span class="text-muted fw-semibold fs-6">{{ $t('Press center role') }}</span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating mb-6">
                                    <input class="form-control form-control-lg" type="text" :placeholder="form.role == 'journalist'? $t('Name'): $t('Organization name')" v-model="form.name" @keyup.enter="register" autocomplete="off" />
                                    <label class="form-label required">{{ form.role == 'journalist'? $t('Name'): $t('Organization name') }}</label>
                                    <div v-if="errors.name && errors.name.length" class="fv-plugins-message-container invalid-feedback d-block">
                                        <span v-for="(error, index) in errors.name" v-bind:key="index">{{ error }}</span>
                                    </div>
                                </div>
                                <div v-if="form.role == 'journalist'" class="form-floating mb-6">
                                    <input class="form-control form-control-lg" type="text" :placeholder="$t('Last Name')" v-model="form.lastname" @keyup.enter="register" autocomplete="off" />
                                    <label class="form-label required">{{ $t('Last Name') }}</label>
                                    <div v-if="errors.lastname && errors.lastname.length" class="fv-plugins-message-container invalid-feedback d-block">
                                        <span v-for="(error, index) in errors.lastname" v-bind:key="index">{{ error }}</span>
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-12 col-sm-6 col-lg-12 col-xxl-6">
                                        <div class="form-floating mb-6">
                                            <input class="form-control form-control-lg" type="text" :placeholder="$t('Phone')" v-model="form.phone" @keyup.enter="register" autocomplete="off" v-maska:[phoneMask]/>
                                            <label class="form-label required">{{ $t('Phone') }}</label>
                                            <div v-if="errors.phone && errors.phone.length" class="fv-plugins-message-container invalid-feedback d-block">
                                                <span v-for="(error, index) in errors.phone" v-bind:key="index">{{ error }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-12 col-xxl-6">
                                        <div class="form-floating mb-6">
                                            <input class="form-control form-control-lg" type="email" placeholder="Email" v-model="form.email" @keyup.enter="register" autocomplete="off" data-kt-translate="sign-up-input-email" />
                                            <label class="form-label required">Email</label>
                                            <div v-if="errors.email && errors.email.length" class="fv-plugins-message-container invalid-feedback d-block">
                                                <span v-for="(error, index) in errors.email" v-bind:key="index">{{ error }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fv-row mb-6" data-kt-password-meter="true">
                                    <div class="mb-1">
                                        <div class="row gx-3">
                                            <div class="col-12 col-sm-6 col-lg-12 col-xxl-6">
                                                <div class="form-floating mb-3">
                                                    <input class="form-control form-control-lg" type="password" :placeholder="$t('Password')"  v-model="form.password" @keyup.enter="register"/>
                                                    <label class="form-label required">{{ $t('Password') }}</label>
                                                    <div v-if="errors.password && errors.password.length" class="fv-plugins-message-container invalid-feedback d-block">
                                                        <span v-for="(error, index) in errors.password" v-bind:key="index">{{ error }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 col-lg-12 col-xxl-6">
                                                <div class="form-floating mb-3">
                                                    <input class="form-control form-control-lg" type="password" :placeholder="$t('Password confirmation')" v-model="form.password_confirmation" @keyup.enter="register" autocomplete="off" />
                                                    <label class="form-label required">{{ $t('Password confirmation') }}</label>
                                                </div>
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
                                
                                <div v-if="form.role == 'journalist'">
                                    <div class="form-floating mb-6">
                                        <select class="form-control form-control-lg" :placeholder="$t('Region')" v-model="form.region_id" @change="form.city_id = ''">
                                            <option value="">{{ $t('Select region') }}</option>
                                            <option v-for="region in regions" :value="region.id">{{ region['region_name_' + $root.locale] }}</option>
                                        </select>
                                        <label class="form-label required">{{ $t('Region') }}</label>
                                        <div v-if="errors.region_id && errors.region_id.length" class="fv-plugins-message-container invalid-feedback d-block">
                                            <span v-for="(error, index) in errors.region_id" v-bind:key="index">{{ error }}</span>
                                        </div>
                                    </div>
                                    <div v-if="form.region_id" class="form-floating mb-6">
                                        <select class="form-control form-control-lg" :placeholder="$t('City')" v-model="form.city_id">
                                            <option value="">{{ $t('Select city') }}</option>
                                            <option v-for="city in citiesByRegion" :value="city.id">{{ city['city_name_' + $root.locale] }}</option>
                                        </select>
                                        <label class="form-label required">{{ $t('City') }}</label>
                                        <div v-if="errors.city_id && errors.city_id.length" class="fv-plugins-message-container invalid-feedback d-block">
                                            <span v-for="(error, index) in errors.city_id" v-bind:key="index">{{ error }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="form-floating mb-6">
                                    <select class="form-control form-control-lg" :placeholder="$t('Category')" v-model="form.user_category_id">
                                        <option value="">{{ $t('Select category') }}</option>
                                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                                    </select>
                                    <label class="form-label required">{{ $t('Category') }}</label>
                                    <div v-if="errors.user_category_id && errors.user_category_id.length" class="fv-plugins-message-container invalid-feedback d-block">
                                        <span v-for="(error, index) in errors.user_category_id" v-bind:key="index">{{ error }}</span>
                                    </div>
                                </div>

                                <div class="fv-row mb-8 fv-plugins-icon-container">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" v-model="agree" :value="true">
                                        <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">
                                            {{ $t('I Accept the') }} <a href="" class="ms-1 link-primary" @click.prevent="$root.openModal('terms')">{{ $t('Terms') }}</a>
                                        </span>
                                    </label>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                                <div class="mb-6">
                                    <vue-recaptcha v-show="action == 'register'" sitekey="6LcHEUkmAAAAAEtBo4xx-MSNyfwc9L5qOkkdngsZ"
                                        size="normal" 
                                        :theme="$root.isDark? 'dark': 'light'"
                                        :hl="$root.locale"
                                        @verify="recaptchaVerified"
                                        @expire="recaptchaExpired"
                                        @fail="recaptchaFailed"
                                        ref="vueRecaptcha"/>
                                    <div v-if="errors.recaptcha && errors.recaptcha.length" class="fv-plugins-message-container invalid-feedback d-block">
                                        <span v-for="(error, index) in errors.recaptcha" v-bind:key="index">{{ error }}</span>
                                    </div>
                                </div>

                                <div class="d-flex flex-stack">
                                    <button @click="register" type="button" class="btn btn-primary rounded-4" :data-kt-indicator="loading" :disabled="loading || !form.recaptcha || !agree">
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
                                    <i class="ki-duotone ki-arrow-left fs-2x"><i class="path1"></i><i class="path2"></i></i>
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
                                    <input type="text" placeholder="Email" v-model="form.email" @keyup.enter="reset" autocomplete="off" data-kt-translate="sign-in-input-email" class="form-control" />
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

                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100"> 
                    <img class="mx-auto mw-100 w-150px w-lg-300px mb-6 mb-lg-20" :src="$media('illustrations/sketchy-1/1.png')" alt="">

                    <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-6"> 
                        {{ $t('Fast and efficient') }}
                    </h1>
                    <div class="text-gray-600 fs-base text-center fw-semibold mb-20 mb-lg-0">
                        {{ $t('NewsHub.kz allows you to use media resources quickly and efficiently') }}
                    </div>
                </div>
            </div>
        </div>

        <Modal v-if="$root.modalType == 'terms'" ref="modal" name="terms">
            <template #title>
                {{ $t('Consent to the processing of personal data') }}
            </template>

            <div class="fs-5" v-html="$root.config.terms"></div>

            <template #footer>
                <div class="m-0 d-flex align-items-center">
                </div>
                <div>
                    <button class="btn rounded-2 btn-light me-2" @click="$root.closeModal('terms')">{{ $t('Close') }}</button>
                    <button class="btn rounded-2 btn-light-success" @click="agree = true, $root.closeModal('terms')">{{ $t('Agree') }}</button>
                </div>
            </template>
        </Modal>
    </div>
</template>
<script>
import { defineComponent } from "vue"
import vueRecaptcha from 'vue3-recaptcha2';
import Modal from '@/components/Modal.vue';

export default defineComponent({
    name: 'Auth',
    components: {
        vueRecaptcha,
        Modal,
    },
    data() {
        return {
            action: 'login',
            loading: false,
            phoneMask: {
                mask: '+7-###-###-##-##',
                eager: true,
            },
            agree: false,
            form: {
                name: '',
                lastname: '',
                email: '',
                phone: '',
                role: 'journalist',
                city_id: '',
                region_id: '',
                user_category_id: '',
                password: '',
                password_confirmation: '',
                recaptcha: '',
            },
            errors: {
                name: [],
                lastname: [],
                city_id: [],
                region_id: [],
                user_category_id: [],
                phone: [],
                email: [],
                password: [],
                recaptcha: [],
            },
            strength: 0,
            categories: [],
            cities: [],
            regions: [],
        }
    },
    created() {
        if (this.$root.token) {
            this.$router.push({name: 'index'})
            return
        }

        this.fetchData()
    },
    mounted() {
        
    },
    computed: {
        citiesByRegion() {
            if (!this.form.region_id) return []

            return this.cities.filter((item) => {
                return item.region_id == this.form.region_id
            })
        }
    },
    watch: {
        'form.name': function() {
            if (this.errors.name && this.errors.name.length) {
                this.errors.name = []
            }
        },
        'form.lastname': function() {
            if (this.errors.lastname && this.errors.lastname.length) {
                this.errors.lastname = []
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
        'form.city_id': function() {
            if (this.errors.city_id && this.errors.city_id.length) {
                this.errors.city_id = []
            }
        },
        'form.user_category_id': function() {
            if (this.errors.user_category_id && this.errors.user_category_id.length) {
                this.errors.user_category_id = []
            }
        },
        'form.recaptcha': function() {
            if (this.errors.recaptcha && this.errors.recaptcha.length) {
                this.errors.recaptcha = []
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
            if (!this.form.recaptcha || !this.agree) return

            this.loading = true

            this.$api('register', false, {
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
                this.errors = {...response.data.errors}
            })
        },
        fetchData() {
            this.$get('fields').then(({data}) => {
                this.categories = data.categories
                this.cities = data.cities
                this.regions = data.regions
            }).catch((e) => {})
        },
        recaptchaVerified(response) {
            this.form.recaptcha = response
        },
        recaptchaExpired() {
            this.form.recaptcha = ''
            this.$refs.vueRecaptcha.reset();
        },
        recaptchaFailed(e) {
            this.form.recaptcha = ''
        },
        reset() {
            this.loading = true

            this.$api('reset', false, {
                method: 'post',
                data: this.form
            })
            .then(({data}) => {
                this.loading = false

                if (data.ok) {
                    this.message = "Мы отправили на ваш Email новые доступы к аккаунту"
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