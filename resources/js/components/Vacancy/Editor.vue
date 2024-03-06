<template>
    <Modal ref="modal" name="vacancy-editor" :fsMode="$root.isMobile" bodyClass="" :loading="!ready" :outside="false">
        <template #title>
            {{ vacancy.uuid? $t('Modify vacancy'): $t('Create vacancy') }}
            <Popper placement="bottom" class="ms-2">
                <button type="button" class="btn btn-light-secondary btn-sm text-gray-700 fs-8 py-2 px-3 d-flex align-items-center">
                    {{ $root.languages[locale].name }} <!-- <img class="w-15px h-15px rounded-1 ms-2" :src="$root.icons[locale]" alt=""> -->
                </button>

                <template #content="{ close }">
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-2 fs-7 mw-125px min-w-125px w-100 show">
                        <div v-for="(lang, key) in $root.languages" class="menu-item py-0">
                            <a href="" @click.prevent="locale = key, close()" class="menu-link rounded-0 px-3">
                               <!-- <span class="menu-icon">
                                    <img class="w-15px h-15px rounded-1" :src="$root.icons[key]" alt="">
                                </span> -->
                                <span class="menu-title">{{ lang.name }}</span>
                            </a>
                        </div>
                    </div>
                </template>
            </Popper>
        </template>
        
        <div class="form-floating mb-5">
            <input type="text" class="form-control" id="job-title" v-model="vacancy.job_title[locale]" :placeholder="$t('Title')"/>
            <label for="job-title" class="form-label required">{{ $t('Title') }}</label>
        </div>

        <div class="form-floating mb-7">
            <textarea class="form-control" id="job-requiremets" v-model="vacancy.requiremets[locale]" :placeholder="$t('Requiremets')" style="height: 80px;"></textarea>
            <label for="job-requiremets" class="form-label required">{{ $t('Requiremets') }}</label>
        </div>

        <div class="form-floating mb-7">
            <textarea class="form-control" id="job-task" v-model="vacancy.task[locale]" :placeholder="$t('Task')" style="height: 80px;"></textarea>
            <label for="job-task" class="form-label required">{{ $t('Task') }}</label>
        </div>

        <div class="form-floating mb-7">
            <textarea class="form-control" id="job-conditionsm" v-model="vacancy.conditionsm[locale]" :placeholder="$t('Conditions')" style="height: 80px;"></textarea>
            <label for="job-conditionsm" class="form-label required">{{ $t('Conditions') }}</label>
        </div>

        <div class="form-floating mb-5">
            <input type="email" class="form-control" id="job-email" v-model="vacancy.email_jobseeker" placeholder="Email"/>
            <label for="job-email" class="form-label required">Email</label>
        </div>

        <div v-if="saveError" class="text-danger fs-sm fw-medium">{{ saveError }}</div>

        <template #footer>
            <div class="m-0 d-flex align-items-center">
                <!-- <div class="form-check form-switch form-check-custom form-check-solid me-4">
                    <input class="form-check-input h-25px w-40px" type="checkbox" value="" id="flexSwitch20x30"/>
                    <label class="form-check-label text-gray-700 fw-bold" for="flexSwitch20x30">
                        {{ $t('Save draft') }}
                    </label>
                </div> -->
                <!-- <button type="button" class="btn btn-sm rounded-2 btn-light-danger py-1 px-2 fs-8" @click="">{{ $t('Clear draft') }}</button> -->
            </div>
            <div>
                <button :disabled="isSend" type="button" class="btn rounded-2 btn-light me-2" @click="$root.closeModal('vacancy-editor')">{{ $t('Close') }}</button>
                <button :disabled="isSend" type="button" class="btn rounded-2 btn-light-success" @click="save">{{ vacancy.uuid? $t('Update'): $t('Publish') }}</button>
            </div>
        </template>

        <!-- <div class="d-flex flex-column justify-content-end flex-fill mt-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="fs-xs fw-medium text-muted d-flex flex-column me-2">
                    <span v-if="!post.uuid">{{ $t('Draft autosave active') }}</span>
                    <a href="" @click.prevent="$store.commit('setDraft', false)" v-if="!post.uuid && $root.draft">{{ $t('clear draft') }}</a>
                </div>
                <div class="text-nowrap">
                </div>
            </div>
        </div> -->
    </Modal>
</template>
<script>
import { defineComponent } from "vue"
import Popper from "vue3-popper"
import showErrors from "@/helpers/notify"
import Modal from "@/components/Modal.vue"
import { ElNotification } from 'element-plus'

export default defineComponent({
    name: 'Editor',
    props: [],
    components: {
        Modal,
        Popper,
    },
    data() {
        return {
            isSend: false,
            saveError: false,
            loading: true,
            locale: this.$root.locale,
            vacancy: {
                job_title: {
                    en: '',
                    ru: '',
                    kk: '',
                },
                requiremets: {
                    en: '',
                    ru: '',
                    kk: '',
                },
                task: {
                    en: '',
                    ru: '',
                    kk: '',
                },
                conditionsm: {
                    en: '',
                    ru: '',
                    kk: '',
                },
                email_jobseeker: '',
            },
        }
    },
    computed: {
        ready() {
            return !this.loading
        }
    },
    created() {
        if (this.$root.vacancy) {
            this.fetchData()
        } else {
            this.loading = false
        }
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api('vacancy/editor', true, {
                method: 'POST',
                data: {
                    slug: this.$root.vacancy
                }
            })
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                this.vacancy = data.vacancy
            })
        },
        save() {
            this.isSend = true

            this.$api("vacancy/save", true, {
                method: 'post',
                data: { ...this.vacancy }
            })
            .then(({data}) => {
                this.isSend = false

                if (data.ok) {
                    this.saveError = false

                    this.$root.closeModal('vacancy-editor')

                    ElNotification({
                        type: 'success',
                        title: this.$t('Notification'),
                        message: data.message,
                        duration: 5000,
                    })
                } else {
                    this.saveError = this.$t(data.message)
                }
            })
            .catch((e) => {
                this.isSend = false
                showErrors(e.response)
            })
        },
    },
});
</script>