<template>
    <div>
        <div class="row">
            <div class="col-lg-7">
                <div class="card mb-7" itemscope itemtype="http://schema.org/Article">
                    <intersection-observer
                        v-if="!loading && post.next && !slugs.includes(post.next)"
                        :sentinal-name="'top' + post.slug"
                        @on-intersection-element="fetchNext(post.next)"
                    ></intersection-observer>

                    <div class="card-body p-5 p-lg-10 pb-lg-0">
                        <ViewSkeleton v-if="loading"/>
                        <div v-else class="mb-17">
                            <div class="mb-8">
                                <h1 class="text-dark fs-1 fw-bold" itemprop="headline">
                                    {{ post.title }}

                                    <span class="fw-bold text-muted fs-5 ps-1">{{ post.read_mins }} {{ $t('mins read') }}</span>
                                </h1>
                                <div class="d-flex flex-wrap">
                                    <div class="me-5 my-1 d-flex align-items-center">
                                        <i class="ki-duotone ki-element-11 fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>

                                        <span class="fw-bold text-gray-400" itemprop="datePublished" datetime="2019-04-22"><VDate :datetime="new Date(post.created_at)"/></span>
                                    </div>
                                    <div v-if="post.categories.length" class="me-5 my-1 d-flex align-items-center">
                                        <i class="ki-duotone ki-briefcase fs-2 me-2"><span
                                                class="path1"></span><span class="path2"></span></i>

                                        <span class="fw-bold text-gray-400"><span v-for="(category, index) in post.categories"><app-link :to="{name: 'category', params: {slug: category.slug}}">{{ category.name }}</app-link><span v-if="index + 1 < post.categories.length" class="me-1">,</span></span></span>
                                    </div>
                                    <div v-if="post.categories.length && post.rubrics.length" class="me-5 my-1 d-flex align-items-center">
                                        <i class="ki-duotone ki-price-tag fs-2 me-2"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                        <span class="fw-bold text-gray-400"><span v-for="(rubric, index) in post.rubrics"><app-link :to="{name: 'category', params: {slug: post.categories[0].slug, rubric: rubric.slug}}">{{ rubric.name }}</app-link><span v-if="index + 1 < post.rubrics.length" class="me-1">,</span></span></span>
                                    </div>
                                    <!-- <div class="my-1 d-flex align-items-center">
                                        <i class="ki-duotone ki-message-text-2 text-primary fs-2 me-1"><span
                                                class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <span class="fw-bold text-gray-400">24 Comments</span>
                                    </div> -->
                                </div>

                                <intersection-observer
                                    :sentinal-name="'head' + post.slug"
                                    @on-intersection-element="updatePage(post)"
                                ></intersection-observer>

                                <div v-if="post.image" class="d-block position-relative overflow-hidden rounded-3 mt-6 cursor-zoom-in" @click="$root.fullscreenImage = $storage(post.image), $root.fullscreen = true">
                                    <img :src="$storage(post.image)" :alt="post.title" class="object-fit-contain z-index-1 position-relative mh-450px min-h-250px w-100" itemprop="image" loading="lazy"/>
                                    <div :style="{backgroundImage: 'url(' + $storage(encodeURIComponent(post.image)) + ')'}" class="bg-blur"></div>
                                </div>
                                <div v-if="post.image_caption" class="fw-semibold mt-1 text-gray-700 fs-6">{{ post.image_caption }}</div>
                            </div>

                            <div class="fs-5 fw-medium text-gray-900 mb-10 article" itemprop="articleBody" v-html="post.content"></div>

                            <intersection-observer
                                :sentinal-name="'footer' + post.slug"
                                @on-intersection-element="updatePage(post)"
                            ></intersection-observer>

                            <div class="card card-dashed border-hover-primary mb-6">
                                <div class="card-body p-5">
                                    <div class="d-flex overflow-hidden">
                                        <app-link :to="{name: 'user', params: {slug: post.user_id}}" class="me-6 d-flex flex-fill flex-nowrap">
                                            <div class="me-6 flex-shrink-0">
                                                <div class="symbol symbol-50px w-50px bg-light my-1">
                                                    <img :src="$url('/storage/' + post.avatar)" class="object-fit-cover" alt=""> 
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div class="fs-3 fw-bold text-dark me-5" itemprop="author">{{ post.name }}</div>
                                                </div>
                                                <div class="text-gray-400 fw-semibold fs-5 mt-1 mb-0">{{ post.description }}</div>
                                            </div>
                                        </app-link>
                                        <div v-if="$root.user && $root.user.id != post.user_id">
                                            <button @click="$root.follow($root.feeds.includes(post.user_id)? 0: 1, post.user_id)" class="d-none d-xl-inline btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(post.user_id), 'btn-active-light-danger': $root.feeds.includes(post.user_id)}">
                                                {{ !$root.feeds.includes(post.user_id)? $t('Follow'): $t('Unfollow') }}
                                            </button>
                                            <button @click="$root.follow($root.feeds.includes(post.user_id)? 0: 1, post.user_id)" class="btn-icon d-inline d-xl-none btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(post.user_id), 'btn-active-light-danger': $root.feeds.includes(post.user_id)}">
                                                <i class="ki-duotone" :class="{'ki-user-tick': !$root.feeds.includes(post.user_id), 'ki-user-edit': $root.feeds.includes(post.user_id)}"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-center mb-5">
                                <a :href="$root.shareWith('tg', post.url)" class="mx-4">
                                    <img :src="$media('svg/brand-logos/telegram.svg')" class="h-20px my-2" alt="">
                                </a>
                                <a :href="$root.shareWith('vk', post.url)" class="mx-4">
                                    <img :src="$media('svg/brand-logos/vk.svg')" class="h-20px my-2" alt="">
                                </a>
                                <a :href="$root.shareWith('tw', post.url)" class="mx-4">
                                    <img :src="$media('svg/brand-logos/twitter.svg')" class="h-20px my-2" alt="">
                                </a>
                                <a :href="$root.shareWith('fb', post.url)" class="mx-4">
                                    <img :src="$media('svg/brand-logos/facebook-4.svg')" class="h-20px my-2" alt="">
                                </a>
                            </div>

                            <div class="fs-6 fw-semibold text-center text-muted">
                                {{ $t('Error in the text? Please let us know. Highlight the error and press Ctrl + Enter') }}
                            </div>
                        </div>
                    </div>

                    <intersection-observer
                        v-if="!loading && post.next && !slugs.includes(post.next)"
                        :sentinal-name="'bottom' + post.slug"
                        @on-intersection-element="fetchNext(post.next)"
                    ></intersection-observer>
                </div>

                <div v-for="item in posts" class="card mb-7">
                    <intersection-observer
                        v-if="!loading && item.next && !slugs.includes(item.next)"
                        :sentinal-name="'top' + item.slug"
                        @on-intersection-element="fetchNext(item.next)"
                    ></intersection-observer>

                    <div class="card-body p-5 p-lg-10 pb-lg-0">
                        <ViewSkeleton v-if="loading"/>
                        <div v-else class="mb-17">
                            <div class="mb-8" itemprop="headline">
                                <h1 class="text-dark fs-1 fw-bold">
                                    {{ item.title }}

                                    <span class="fw-bold text-muted fs-5 ps-1">{{ item.read_mins }} {{ $t('mins read') }}</span>
                                </h1>
                                <div class="d-flex flex-wrap">
                                    <div class="me-5 my-1 d-flex align-items-center">
                                        <i class="ki-duotone ki-element-11 fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>

                                        <span class="fw-bold text-gray-400" itemprop="datePublished" datetime="2019-04-22"><VDate :datetime="new Date(item.created_at)"/></span>
                                    </div>
                                    <div v-if="item.categories.length" class="me-5 my-1 d-flex align-items-center">
                                        <i class="ki-duotone ki-briefcase fs-2 me-2"><span
                                                class="path1"></span><span class="path2"></span></i>

                                        <span class="fw-bold text-gray-400"><span v-for="(category, index) in item.categories"><app-link :to="{name: 'category', params: {slug: category.slug}}">{{ category.name }}</app-link><span v-if="index + 1 < item.categories.length" class="me-1">,</span></span></span>
                                    </div>
                                    <div v-if="item.categories.length && item.rubrics.length" class="me-5 my-1 d-flex align-items-center">
                                        <i class="ki-duotone ki-price-tag fs-2 me-2"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                        <span class="fw-bold text-gray-400"><span v-for="(rubric, index) in item.rubrics"><app-link :to="{name: 'category', params: {slug: item.categories[0].slug, rubric: rubric.slug}}">{{ rubric.name }}</app-link><span v-if="index + 1 < item.rubrics.length" class="me-1">,</span></span></span>
                                    </div>
                                    <!-- <div class="my-1 d-flex align-items-center">
                                        <i class="ki-duotone ki-message-text-2 text-primary fs-2 me-1"><span
                                                class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <span class="fw-bold text-gray-400">24 Comments</span>
                                    </div> -->
                                </div>

                                <intersection-observer
                                    :sentinal-name="'head' + item.slug"
                                    @on-intersection-element="updatePage(item)"
                                ></intersection-observer>

                                <div v-if="item.image" class="d-block position-relative overflow-hidden rounded-3 mt-6 cursor-zoom-in" @click="$root.fullscreenImage = $storage(item.image), $root.fullscreen = true">
                                    <img :src="$storage(item.image)" :alt="item.title" class="object-fit-contain z-index-1 position-relative mh-450px min-h-250px w-100" loading="lazy"/>
                                    <div :style="{backgroundImage: 'url(' + $storage(encodeURIComponent(post.image)) + ')'}" class="bg-blur"></div>
                                </div>
                                <div v-if="item.image_caption" class="fw-semibold mt-1 text-gray-700 fs-6">{{ item.image_caption }}</div>
                            </div>

                            <div class="fs-5 fw-medium text-gray-900 mb-10 article" itemprop="articleBody" v-html="item.content"></div>

                            <intersection-observer
                                :sentinal-name="'footer' + item.slug"
                                @on-intersection-element="updatePage(item)"
                            ></intersection-observer>

                            <div class="card card-dashed border-hover-primary mb-6">
                                <div class="card-body p-5">
                                    <div class="d-flex overflow-hidden">
                                        <app-link :to="{name: 'user', params: {slug: item.user_id}}" class="me-6 d-flex flex-fill flex-nowrap">
                                            <div class="me-6 flex-shrink-0">
                                                <div class="symbol symbol-50px w-50px bg-light my-1">
                                                    <img :src="$url('/storage/' + item.avatar)" class="object-fit-cover" alt=""> 
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div class="fs-3 fw-bold text-dark me-5" itemprop="author">{{ item.name }}</div>
                                                </div>
                                                <div class="text-gray-400 fw-semibold fs-5 mt-1 mb-0">{{ item.description }}</div>
                                            </div>
                                        </app-link>
                                        <div v-if="$root.user && $root.user.id != item.user_id">
                                            <button @click="$root.follow($root.feeds.includes(item.user_id)? 0: 1, item.user_id)" class="d-none d-xl-inline btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(item.user_id), 'btn-active-light-danger': $root.feeds.includes(item.user_id)}">
                                                {{ !$root.feeds.includes(item.user_id)? $t('Follow'): $t('Unfollow') }}
                                            </button>
                                            <button @click="$root.follow($root.feeds.includes(item.user_id)? 0: 1, item.user_id)" class="btn-icon d-inline d-xl-none btn btn-sm btn-light" :class="{'btn-active-light-success': !$root.feeds.includes(item.user_id), 'btn-active-light-danger': $root.feeds.includes(item.user_id)}">
                                                <i class="ki-duotone" :class="{'ki-user-tick': !$root.feeds.includes(item.user_id), 'ki-user-edit': $root.feeds.includes(item.user_id)}"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-center mb-5">
                                <a :href="$root.shareWith('tg', item.url)" class="mx-4">
                                    <img :src="$media('svg/brand-logos/telegram.svg')" class="h-20px my-2" alt="">
                                </a>
                                <a :href="$root.shareWith('vk', item.url)" class="mx-4">
                                    <img :src="$media('svg/brand-logos/vk.svg')" class="h-20px my-2" alt="">
                                </a>
                                <a :href="$root.shareWith('tw', item.url)" class="mx-4">
                                    <img :src="$media('svg/brand-logos/twitter.svg')" class="h-20px my-2" alt="">
                                </a>
                                <a :href="$root.shareWith('fb', item.url)" class="mx-4">
                                    <img :src="$media('svg/brand-logos/facebook-4.svg')" class="h-20px my-2" alt="">
                                </a>
                            </div>

                            <div class="fs-6 fw-semibold text-center text-muted">
                                {{ $t('Error in the text? Please let us know. Highlight the error and press Ctrl + Enter') }}
                            </div>
                        </div>
                    </div>

                    <intersection-observer
                        v-if="!loading && item.next && !slugs.includes(item.next)"
                        :sentinal-name="'bottom' + item.slug"
                        @on-intersection-element="fetchNext(item.next)"
                    ></intersection-observer>
                </div>
            </div>
            <div class="col-lg-5">
                <Sidebar/>
            </div>
        </div>

        <Modal v-if="$root.modalType == 'report'" name="report" :fsMode="$root.isMobile">
            <template #title>
                {{ $t('Submit a grammar error') }}
            </template>
            
            <div v-if="!reported" class="">
                <div class="alert alert-warning fs-5 mb-7">{{ grammar }}</div>

                <div class="form-floating mb-7">
                    <textarea id="grammar-fix" class="form-control min-h-100px" v-model="suggest" :placeholder="$t('Enter text here')"></textarea>
                    <label for="grammar-fix" class="form-label required">{{ $t('Suggest a fix') }}</label>
                </div>
            </div>
            <div v-else class="fs-5 fw-bold">
                {{ $t('Thank you for your attention, the error will be corrected in the near future.') }}
            </div>

            <template #footer>
                <div class="m-0 d-flex align-items-center">
                </div>
                <div>
                    <button type="button" class="btn rounded-2 btn-light me-2" @click="$root.closeModal('report')">{{ $t('Close') }}</button>
                    <button :disabled="isSend || reported" type="button" class="btn rounded-2 btn-light-success" @click="sendReport">{{ $t('Send') }}</button>
                </div>
            </template>
        </Modal>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import Sidebar from "@/components/Sidebar.vue"
import Modal from "@/components/Modal.vue"
import ViewSkeleton from "@/components/Post/ViewSkeleton.vue"
import IntersectionObserver from "@/components/IntersectionObserver.vue"
import { ElNotification } from 'element-plus'

export default defineComponent({
    name: "Post",
    components: {
        ViewSkeleton,
        IntersectionObserver,
        Sidebar,
        Modal,
    },
    data() {
        return {
            slug: this.$route.params.slug,
            loading: false,
            grammar: '',
            suggest: '',
            isSend: false,
            reported: false,
            posts: [],
            slugs: [],
        }
    },
    computed: {
        post() {
            return this.$store.state.post
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
        await this.$api(`post/${this.slug}`, false).then(({data}) => {
            this.loading = false

            if (!data.ok) return

            let post = data.post
            post.url = this.$base(this.$router.resolve({name: 'post', params: {slug: post.slug, locale: this.$root.locale != 'ru'? this.$root.locale: ''}}).fullPath)

            this.$store.commit('setPost', post)

            this.$store.commit('setMeta', {
                title: this.post.title,
                description: this.post.summary,
                ogDescription: this.post.summary,
                ogTitle: this.post.title,
                ogImage: this.post.image? this.$storage(this.post.image): '',
                twitterCard: 'summary_large_image',
            })
        })
    },
    created() {
        if (!import.meta.env.SSR) {
            this.fetchData()

            if (this.$root.user && this.$route.params.action && this.$route.params.action == 'resolve') {
                this.$api(`resolve/post/${this.slug}`, true).then(({data}) => {
                    if (!data.ok) {
                        ElNotification({
                            type: 'error',
                            title: this.$t('Notification'),
                            message: data.message,
                            duration: 2000,
                        })
                    } else {
                        ElNotification({
                            type: 'success',
                            title: this.$t('Notification'),
                            message: data.message,
                            duration: 2000,
                        })
                    }

                    this.$router.replace({name: 'post', params: {locale: (this.$root.locale != 'ru'? this.$root.locale: ''), slug: this.slug}})
                }).catch((e) => {
                    ElNotification({
                        type: 'error',
                        title: this.$t('Notification'),
                        message: data.message,
                        duration: 2000,
                    })

                    this.$router.replace({name: 'post', params: {locale: (this.$root.locale != 'ru'? this.$root.locale: ''), slug: this.slug}})
                })
            }

            window.addEventListener('keyup', this.onKeyUp)
        }
    },
    beforeUnmount() {
        window.removeEventListener('keyup', this.onKeyUp);
    },
    watch: {
        $route(to, from) {
            if (to.params.action === 'scroll') return

            if (from.name == 'post' && to.name == from.name && from.params.slug != to.params.slug) {
                this.reset()
            }
        }
    },
    methods: {
        fetchData() {
            if (this.post.slug && this.post.slug === this.slug) {
                this.loading = false
                return
            }

            this.loading = true

            this.$api(`post/${this.slug}`, false).then(({data}) => {
                this.loading = false

                if (!data.ok) return

                let post = data.post
                post.url = this.$base(this.$router.resolve({name: 'post', params: {slug: post.slug, locale: this.$root.locale != 'ru'? this.$root.locale: ''}}).fullPath)

                this.$store.commit('setPost', post)

                this.$store.commit('setMeta', {
                    title: this.post.title,
                    description: this.post.summary,
                    ogDescription: this.post.summary,
                    ogTitle: this.post.title,
                    ogImage: this.post.image? this.$storage(this.post.image): '',
                    twitterCard: 'summary_large_image',
                })
            })
        },
        async updatePage(item) {
            if (this.$route.params.slug == item.slug) return

            this.$store.commit('setMeta', {
                title: item.title,
            })

            await this.$router.replace({
                name: 'post',
                params: {
                    slug: item.slug,
                    action: 'scroll'
                }
            })
        },
        fetchNext(next) {
            this.slugs.push(next)

            this.$api(`post/${next}`, false).then(({data}) => {
                if (!data.ok) return

                data.post.url = this.$base(this.$router.resolve({name: 'post', params: {slug: data.post.slug, locale: this.$root.locale != 'ru'? this.$root.locale: ''}}).fullPath)
                this.posts.push(data.post)
            })
        },
        reset() {
            this.loading = true
            this.slug = this.$route.params.slug
            this.$store.commit('setPost', {})
            this.fetchData()
        },
        sendReport() {
            this.isSend = true

            this.$post('post/grammar', {
                slug: this.slug,
                error: this.grammar,
                suggestion: this.suggest,
            }, !!this.$root.token).then(({data}) => {
                this.isSend = false
                if (!data.ok) return

                this.reported = true
            }).catch((e) => {
                this.isSend = false
            })
        },
        onKeyUp(e) {
            if (e.ctrlKey && [10, 13].includes(e.keyCode)) {
                let selection = window.getSelection()
                this.grammar = selection.toString()
                this.suggest = ''
                this.reported = false
                this.isSend = false
                this.$root.openModal('report')
            }
        }
    },
});
</script>