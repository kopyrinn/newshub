<template>
    <div class="card">
        <div class="card-body p-lg-17">
            <div v-if="!slug" class="d-flex flex-column">
                <!--begin::Heading-->
                <div class="mb-13">
                    <!-- <h1 class="fs-2hx fw-bold mb-5">Choose Your Plan</h1> -->

                    <div class="text-gray-800 fw-semibold fs-5">
                        <p>{{ $t('NewsHub.kz offers its users a wide range of tariffs and discounts. At the same time, we provide one month of free use of the "Standard" package.') }}</p>
                        <p>{{ $t('This will allow you to evaluate all the benefits of using NewsHub.kz and choose the appropriate set of services that meet the requirements and financial capabilities of a company or organization. With paid service, the client can choose a different package. Each package includes discounts when paying for several months.') }}</p>
                    </div>
                </div>
                <!--end::Heading-->

                <!--begin::Nav group-->
                <div class="nav-group nav-group-outline mx-auto mb-10">
                    <button class="btn btn-color-gray-400 btn-active btn-active-secondary px-6 py-3 me-2" @click="period = 1" :class="{'active': period === 1}">
                        {{ $t('Monthly') }}
                    </button>
                    <button class="btn btn-color-gray-400 btn-active btn-active-secondary px-6 py-3 me-2" @click="period = 3" :class="{'active': period === 3}">
                        {{ $t('3 Month') }}
                    </button>
                    <button class="btn btn-color-gray-400 btn-active btn-active-secondary px-6 py-3 me-2" @click="period = 6" :class="{'active': period === 6}">
                        {{ $t('6 Month') }}
                    </button>
                    <button class="btn btn-color-gray-400 btn-active btn-active-secondary px-6 py-3" @click="period = 12" :class="{'active': period === 12}">
                        {{ $t('Annual') }}
                    </button>
                </div>
                <!--end::Nav group-->

                <!--begin::Row-->
                <div class="row g-10 position-relative">
                    <!--begin::Col-->
                    <div v-for="item in items" :key="item.slug + period" class="col-xl-4 my-0">
                        <div class="sticky-top pt-10 pt-lg-20">
                            <!--begin::Option-->
                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10 my-0">
                                <!--begin::Heading-->
                                <div class="mb-7 text-center">
                                    <!--begin::Title-->
                                    <h1 class="text-dark mb-5 fw-bolder">{{ item.name }}</h1>
                                    <!--end::Title-->

                                    <!--begin::Description-->
                                    <div class="text-gray-400 fw-semibold mb-5">
                                        {{ item.description }}
                                    </div>
                                    <!--end::Description-->

                                    <!--begin::Price-->
                                    <div class="text-center">
                                        <span class="mb-2 text-primary fw-bold me-2">₸</span>
                                        <span class="fs-2x fw-bold text-primary">{{ $decimal(item[`price_${period}`], 0) }}</span>

                                        <span class="fs-7 ms-1 fw-semibold opacity-50">/
                                            <span v-if="period === 1">{{ $t('Month') }}</span>
                                            <span v-else-if="period === 3">{{ $t('3 Month') }}</span>
                                            <span v-else-if="period === 6">{{ $t('6 Month') }}</span>
                                            <span v-else-if="period === 12">{{ $t('Annual') }}</span>
                                        </span>
                                    </div>
                                    <!--end::Price-->
                                </div>
                                <!--end::Heading-->

                                <!--begin::Features-->
                                <div class="w-100 mb-10">
                                    <div class="fw-semibold fs-6 text-gray-800" v-html="item.content"></div>
                                    <div v-for="feature in item.features" class="d-flex align-items-start mb-5">
                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                            {{ feature }} </span>
                                        <i class="ki-duotone ki-check-circle fs-1 text-success"><span
                                                class="path1"></span><span class="path2"></span></i>
                                    </div>

                                    <div class="fw-bold fs-6 text-gray-900">{{ $t('Unused services are not carried over to the next month.') }}</div>
                                </div>
                                <!--end::Features-->

                                <!--begin::Select-->
                                <div v-if="isCurrentPackage(item)" class="fw-semibold text-success mb-3">
                                    {{ $t('This package is already active') }}
                                </div>
                                <button type="button" class="btn btn-primary" @click="choosePackage(item)">
                                    {{ isCurrentPackage(item) ? $t('Prolong package') : $t('Select') }}
                                </button>
                                <!--end::Select-->
                            </div>
                            <!--end::Option-->
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <div v-else-if="slug && plan.slug && plan.slug == slug">
                <div class="fs-3 fw-bold mb-7 d-flex align-items-center">
                    <button type="button" @click="back" class="btn btn-icon w-30px h-30px bg-light rounded-circle me-4"><i class="ki-duotone ki-black-left fs-2 text-gray-800"></i></button>
                    {{ plan.name }}
                </div>

                <div v-if="isCurrentPackage(plan)" class="notice d-flex bg-light-success rounded border-success border border-dashed rounded-3 p-6 mb-7">
                    <div class="fw-semibold fs-5 text-success">
                        <div>{{ $t('This package is already active') }}</div>
                        <div class="fs-6 mt-1">{{ $t('The selected period will be added to the current expiration date') }}</div>
                    </div>
                </div>

                <div class="form-floating mb-7">
                    <select class="form-select" id="period" aria-label="Period" v-model="period">
                        <option :value="1">{{ $t('Month') }}</option>
                        <option :value="3">{{ $t('3 Month') }}</option>
                        <option :value="6">{{ $t('6 Month') }}</option>
                        <option :value="12">{{ $t('Annual') }}</option>
                    </select>
                    <label for="period">{{ $t('Period') }}</label>
                </div>

                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed rounded-3 p-6 mb-7">
                    <div class="d-flex flex-stack flex-grow-1 ">
                        <div class=" fw-semibold">
                            <h4 class="text-gray-900 fw-bold">{{ $t('This is a very important notice!') }}</h4>
                            <div class="fs-6 text-gray-700 ">{{ $t('If you continue, the amount will be debited from your balance and an automatic subscription will be activated.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="mb-7 d-flex justify-content-between align-items-center w-100">
                    <div class="fw-semibold fs-3">
                        {{ $t('Total') }}
                    </div>
                    <div class="text-end">
                        <span class="mb-2 text-primary fw-bold me-2">₸</span>
                        <span class="fs-2x fw-bold text-primary">{{ $decimal(plan[`price_${period}`], 0) }}</span>

                        <span class="fs-7 ms-1 fw-semibold opacity-50">/
                            <span v-if="period === 1">{{ $t('Month') }}</span>
                            <span v-else-if="period === 3">{{ $t('3 Month') }}</span>
                            <span v-else-if="period === 6">{{ $t('6 Month') }}</span>
                            <span v-else-if="period === 12">{{ $t('Annual') }}</span>
                        </span>
                    </div>
                </div>
                <button type="button" @click="buy" class="btn btn-success w-100" :disabled="isSend">
                    {{ isCurrentPackage(plan) ? $t('Prolong package') : $t('Buy') }}
                </button>
            </div>
            <SchemaOrgWebPage :name="$root.meta.title" />
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"
import showErrors from "@/helpers/notify"
import { ElNotification } from 'element-plus'

export default defineComponent({
    name: "Packages",
    components: {
    },
    data() {
        return {
            slug: this.$route.params.slug,
            plan: {},
            loading: true,
            items: [],
            period: 1,
            isSend: false,
        }
    },
    head() {
        return {
            title: this.$root.meta.title
        }
    },
    created() {
        this.fetchData()
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api('packages')
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                this.items = data.packages

                if (this.slug) {
                    this.plan = this.items.find((item) => item.slug == this.slug)
                }
            })
        },
        buy() {
            if (this.isSend) return

            this.isSend = true

            this.$store.commit('updateCacheKey')

            this.$api(`package/${this.slug}`, true, {
                method: 'post',
                data: {
                    period: this.period,
                }
            })
            .then(({data}) => {
                if (data.ok) {
                    ElNotification({
                        type: 'success',
                        title: this.$t('Notification'),
                        message: data.message,
                        duration: 2000,
                    })

                    this.$root.getUser()
                    this.$router.push({name: 'index'})
                } else {
                    ElNotification({
                        type: 'error',
                        title: 'Ошибка',
                        message: data.message,
                        duration: 2000,
                    })
                }
            })
            .catch((e) => {
                showErrors(e.response)
            })
            .finally(() => {
                this.isSend = false
            })
        },
        choosePackage(item) {
            if (!this.$root.user) return this.$router.push({name: 'login', params: {locale: this.$root.locale == 'ru'? '': this.$root.locale}})

            this.plan = item
            this.slug = item.slug
            this.$router.push({name: 'packages', params: {slug: item.slug}})
        },
        back() {
            this.plan = {}
            this.slug = null
        },
        isCurrentPackage(item) {
            return Boolean(
                item &&
                this.$root.user &&
                this.$root.user.is_package_active &&
                this.$root.user.package_slug === item.slug
            )
        },
        reset() {
            this.loading = true
            this.items = []

            this.fetchData()
        },
    },
});
</script>
