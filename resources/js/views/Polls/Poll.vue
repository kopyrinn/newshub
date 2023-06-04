<template>
    <div>
        <div class="row g-6 g-xl-9">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body p-5 p-lg-10 pb-lg-0">
                        <ViewSkeleton v-if="loading"/>
                        <div v-else class="mb-17">
                            <div class="mb-8">
                                <div class="d-flex flex-wrap mb-6">
                                    <div class="me-9 my-1 d-flex align-items-center">
                                        <i class="ki-duotone ki-element-11 text-primary fs-2 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>

                                        <span class="fw-bold text-gray-400"><VDate :datetime="new Date(poll.created_at)"/></span>
                                    </div>
                                </div>
                                <h1 class="text-dark fs-1 fw-bold">
                                    {{ poll.question }}

                                    <span class="fw-bold text-muted fs-5 ps-1">{{ poll.read_mins }} {{ $t('mins read') }}</span>
                                </h1>
                                <div v-if="poll.photo" class="mt-8">
                                    <img :src="$url('/storage/' + poll.photo)" class="object-fit-cover h-350px w-100 rounded"/>
                                </div>
                            </div>

                            <div class="fs-5 fw-medium text-gray-900 mb-10 article" v-html="poll.description"></div>

                            <div class="mb-10 d-flex align-items-center justify-content-between">
                                <h2 class="mb-0 d-flex align-items-center">{{ $t('Participant(s)') }} <span class="badge bg-secondary rounded-3 ms-2">{{ poll.participants.length }}</span></h2>
                                <button v-if="poll.can_participate" type="button" class="btn btn-sm fs-xs btn-primary" @click="$root.openModal('poll-request')">{{ $t('Request for participation') }}</button>
                            </div>

                            <div v-if="poll.participants.length" class="mb-5">
                                <div v-if="poll.can_vote">
                                    <div v-for="participant in poll.participants">
                                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-stack text-start p-6 mb-5">
                                            <div class="d-flex align-items-center me-2">
                                                <div class="form-check form-check-custom form-check-solid form-check-primary me-6">
                                                    <input class="form-check-input" type="radio" v-model="participantVote" :value="participant.id" />
                                                </div>

                                                <div class="me-6 flex-shrink-0">
                                                    <div class="symbol symbol-50px w-50px symbol-sm-100px w-sm-100px bg-light rounded-3">
                                                        <img :src="$url('/storage/' + participant.photo)" class="object-fit-cover rounded-3" alt=""> 
                                                    </div>
                                                </div>
                                                
                                                <div class="flex-grow-1">
                                                    <h2 class="fs-3 fw-bold" v-snip="{lines: $root.isMobile? 1: 2}">
                                                        {{ participant.name }}
                                                    </h2>
                                                    <div class="fw-semibold opacity-50" v-snip="{lines: $root.isMobile? 2: 3}">
                                                        {{ participant.position }}
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="mb-10 d-flex flex-end">
                                        <button type="button" class="btn btn-success w-100" @click="vote" :disabled="!participantVote || isSend">{{ $t('Vote') }}</button>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-for="(participant, index) in poll.participants">
                                        <div class="border border-dashed position-relative overflow-hidden rounded-3 p-6 mb-5">

                                            <div v-if="participant.votes_count && !index" class="ribbon ribbon-triangle ribbon-top-end border-light">
                                                <div class="ribbon-icon mt-n5 me-n6">
                                                    <i class="ki-duotone ki-award fs-2 text-warning"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-stack text-start mb-5">
                                                <div class="me-6 flex-shrink-0">
                                                    <app-link :to="{name: 'user', params: {slug: participant.uid}}" class="symbol symbol-50px w-50px symbol-sm-100px w-sm-100px bg-light rounded-3">
                                                        <img :src="$url('/storage/' + participant.photo)" class="object-fit-cover rounded-3" alt=""> 
                                                    </app-link>
                                                </div>
                                                
                                                <div class="flex-grow-1">
                                                    <app-link :to="{name: 'user', params: {slug: participant.uid}}" class="fs-3 fw-bold" v-snip="{lines: $root.isMobile? 1: 2}">
                                                        {{ participant.name }}
                                                    </app-link>
                                                    <div class="fw-semibold text-gray-800" v-snip="{lines: $root.isMobile? 2: 3}">
                                                        {{ participant.position }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center flex-column mt-3 w-100">
                                                <div class="d-flex justify-content-between fw-bold fs-6 opacity-75 w-100 mt-auto mb-2">
                                                    <span>{{ participant.votes_count }} {{ $t('Vote(s)') }}</span>
                                                    <span>{{ $decimal(poll.max_votes? 100 / poll.max_votes * participant.votes_count: 0) }}%</span>
                                                </div>

                                                <div class="h-8px w-100 bg-light rounded">
                                                    <div class="bg-success rounded h-10px" role="progressbar" :style="{width: (poll.max_votes? 100 / poll.max_votes * participant.votes_count: 0) + '%'}"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-center">
                                <a :href="$root.shareWith('tg', $base($route.fullPath))" class="mx-4">
                                    <img :src="$media('svg/brand-logos/telegram.svg')" class="h-20px my-2" alt="">
                                </a>
                                <a :href="$root.shareWith('vk', $base($route.fullPath))" class="mx-4">
                                    <img :src="$media('svg/brand-logos/vk.svg')" class="h-20px my-2" alt="">
                                </a>
                                <a :href="$root.shareWith('tw', $base($route.fullPath))" class="mx-4">
                                    <img :src="$media('svg/brand-logos/twitter.svg')" class="h-20px my-2" alt="">
                                </a>
                                <a :href="$root.shareWith('fb', $base($route.fullPath))" class="mx-4">
                                    <img :src="$media('svg/brand-logos/facebook-4.svg')" class="h-20px my-2" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <Sidebar/>
            </div>
        </div>

        <Modal v-if="$root.modalType == 'poll-request'" name="poll-request" :fsMode="$root.isMobile">
            <template #title>
                {{ $t('Submit a request for participation') }}
            </template>

            <div class="d-flex align-items-center justify-content-between flex-column mb-7">
                <VImageUpload class="h-175px w-175px" :image="form.photo" format="rectangle" :circle="true" @uploaded="setImages"/>
            </div>

            <div class="form-floating mb-7">
                <input type="text" class="form-control" id="req-name" v-model="form.name" :placeholder="$t('Full name')"/>
                <label for="req-name" class="form-label required">{{ $t('Full name') }}</label>
            </div>

            <div class="form-floating mb-7">
                <input type="text" class="form-control" id="req-position" v-model="form.position" :placeholder="$t('Position')"/>
                <label for="req-position" class="form-label required">{{ $t('Position') }}</label>
            </div>

            <div class="form-floating mb-7">
                <input type="text" class="form-control" id="req-phone" v-model="form.phone" :placeholder="$t('Phone')"/>
                <label for="req-phone" class="form-label required">{{ $t('Phone') }}</label>
            </div>

            <div class="form-floating mb-7">
                <input type="text" class="form-control" id="req-email" v-model="form.email" :placeholder="$t('Email')"/>
                <label for="req-email" class="form-label required">{{ $t('Email') }}</label>
            </div>

            <template #footer>
                <div class="m-0 d-flex align-items-center">
                </div>
                <div>
                    <button :disabled="isSend" type="button" class="btn rounded-2 btn-light me-2" @click="$root.closeModal('poll-request')">{{ $t('Close') }}</button>
                    <button :disabled="isSend" type="button" class="btn rounded-2 btn-light-success" @click="save">{{ $t('Send request') }}</button>
                </div>
            </template>
        </Modal>
    </div>
</template>
<script>
import { defineComponent, defineAsyncComponent } from "vue";
import Sidebar from "@/components/Sidebar.vue"
import ViewSkeleton from "@/components/Post/ViewSkeleton.vue"
import VImageUpload from '@/components/VImageUpload.vue'
import showErrors from "@/helpers/notify"
import { ElNotification } from 'element-plus'

export default defineComponent({
    name: "Poll",
    components: {
        Sidebar,
        ViewSkeleton,
        VImageUpload,
        Modal: defineAsyncComponent(() =>
            import('@/components/Modal.vue')
        ),
    },
    data() {
        return {
            slug: this.$route.params.slug,
            loading: true,
            poll: {},
            participantVote: false,
            isSend: false,
            form: {
                name: '',
                position: '',
                phone: '',
                email: '',
                photo: '',
            }
        }
    },
    head() {
        return {
            title: this.$root.meta.title,
            meta: [
                {
                    name: 'description',
                    content: this.$root.meta.description,
                },
                {
                    name: 'og:title',
                    content: this.$root.meta.title,
                },
                {
                    name: 'og:description',
                    content: this.$root.meta.ogDescription,
                },
                {
                    name: 'og:image',
                    content: this.$root.meta.ogImage,
                },
                {
                    name: 'twitterCard',
                    content: this.$root.meta.twitterCard,
                },
            ]
        }
    },
    async serverPrefetch() {
        this.loading = false

        await this.$api(`polls/${this.slug}`, !!this.$root.user).then(({data}) => {
            if (!data.ok) return

            this.poll = data.poll

            this.$store.commit('setMeta', {
                title: this.poll.question,
                ogTitle: this.poll.question,
                description: this.poll.summary,
                ogDescription: this.poll.summary,
                ogImage: this.poll.image? this.$url('/storage/' + this.poll.image): '',
                twitterCard: 'summary_large_image',
            })
        })
    },
    created() {
        this.fetchData()
    },
    watch: {
        $route(from, to) {
            if (from.name == 'poll' && to.name == from.name && from.params.slug != to.params.slug) {
                this.reset()
            }
        }
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api(`polls/${this.slug}`, false)
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                this.poll = data.poll

                this.$store.commit('setMeta', {
                    title: this.poll.question,
                    ogTitle: this.poll.question,
                    description: this.poll.summary,
                    ogDescription: this.poll.summary,
                    ogImage: this.poll.image? this.$url('/storage/' + this.poll.image): '',
                    twitterCard: 'summary_large_image',
                })
            })
        },
        reset() {
            this.slug = this.$route.params.slug
            this.loading = true
            this.poll = {}

            this.fetchData()
        },
        vote() {
            this.isSend = true

            this.$api(`polls/${this.slug}/vote`, true, {
                method: 'post',
                data: {
                    participant: this.participantVote
                }
            })
            .then(({data}) => {
                this.isSend = false

                if (data.ok) {
                    this.fetchData()

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
                showErrors(e)
            })
        },
        save() {
            this.isSend = true

            this.$api(`polls/${this.slug}/request`, true, {
                method: 'post',
                data: { ...this.form }
            })
            .then(({data}) => {
                this.isSend = false

                if (data.ok) {
                    ElNotification({
                        type: 'success',
                        title: this.$t('Notification'),
                        message: data.message,
                        duration: 5000,
                    })

                    this.$root.closeModal('poll-request')
                } else {
                    this.saveError = this.$t(data.message)
                }
            })
            .catch((e) => {
                this.isSend = false
                showErrors(e)
            })
        },
        setImages(urls) {
            this.form.photo = urls.lg
        },
    },
});
</script>