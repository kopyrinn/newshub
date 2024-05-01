<template>
    <Modal ref="modal" name="post-editor" :fsMode="$root.isMobile" bodyClass="modal-xl" :loading="!ready" :outside="false">
        <template #title>
            {{ post.uuid? $t('Modify post'): $t('Create post') }}
            <Popper placement="bottom" class="ms-2">
                <button type="button" class="btn btn-light-secondary btn-sm text-gray-700 fs-8 py-2 px-3 d-flex align-items-center">
                    {{ $root.languages[locale].name }} <!-- <img class="w-15px h-15px rounded-1 ms-2" :src="$root.icons[locale]" alt=""> -->
                </button>

                <template #content="{ close }">
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-2 fs-7 mw-125px min-w-125px w-100 show">
                        <div v-for="(lang, key) in $root.languages" class="menu-item py-0">
                            <a href="" @click.prevent="setLocale(key), close()" class="menu-link rounded-0 px-3">
                                <!--<span class="menu-icon">
                                    <img class="w-15px h-15px rounded-1" :src="$root.icons[key]" alt="">
                                </span> -->
                                <span class="menu-title">{{ lang.name }}</span>
                            </a>
                        </div>
                    </div>
                </template>
            </Popper>
        </template>

        <div class="row g-9">
            <div class="col-xl-8 mb-xl-0">
                <div v-if="!$root.user.is_package_active" class="alert bg-light-primary border-dashed border-primary d-flex flex-column flex-sm-row p-5 mb-5">
                    <i class="ki-duotone ki-notification-bing fs-2hx text-primary me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="d-flex flex-fill flex-column pe-0 pe-sm-10">
                        <h5 class="mb-1">{{ $t('Select package') }}</h5>
                        <span class="fw-semibold">{{ $t('To be able to publish the material, you must purchase a package of services.') }}</span>
                    </div>

                    <div class="d-flex align-items-center">
                        <app-link :to="{name: 'packages'}" class="position-absolute position-sm-relative mt-3 me-3 mt-sm-0 me-sm-0 top-0 end-0 btn btn-sm btn-light-primary ms-sm-auto">
                            {{ $t('Select') }}
                        </app-link>
                    </div>
                </div>

                <div class="form-floating mb-5">
                    <input type="text" class="form-control" id="post-title" v-model="post.title[locale]" :placeholder="$t('Title')"/>
                    <label for="post-title" class="form-label required">{{ $t('Title') }}</label>
                </div>
                
                <div class="form-floating mb-7">
                    <textarea class="form-control" id="post-summary" v-model="post.summary[locale]" :placeholder="$t('Summary')" style="height: 88px;" maxlength="250"></textarea>
                    <label for="post-summary" class="form-label">{{ $t('Summary') }} <span class="text-muted fw-medium fs-8 ms-1">250 {{ $t('symbols') }}</span></label>
                </div>
                

                <div class="mb-5">
                    <div class="">
                        <div class="form-control position-relative border border-1 border-gray-300 p-0 rounded-3 mb-2">
                            <div v-if="editor" class="w-100 border-2 border-gray-300 border-bottom-dashed rounded-top-3 bg-body" :class="{'sticky-top': ready}">
                                <div class="btn-group w-auto ms-2 py-1">
                                    <button @click="editor.chain().focus().toggleBold().run()" type="button"  class="btn btn-color-gray-600 btn-icon btn-active-color-primary" :class="{ 'active': editor.isActive('bold') }">
                                        <i class="ki-duotone ki-text-bold fs-2x"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                    </button>
                                    <button @click="editor.chain().focus().toggleItalic().run()" type="button" class="btn btn-color-gray-600 btn-icon  btn-active-color-primary" :class="{ 'active': editor.isActive('italic') }">
                                        <i class="ki-duotone ki-text-italic fs-2x"><i class="path1"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i></i>
                                    </button>
                                    <button @click="editor.chain().focus().toggleStrike().run()" type="button" class="btn btn-color-gray-600 btn-icon  btn-active-color-primary" :class="{ 'active': editor.isActive('strike') }">
                                        <i class="ki-duotone ki-text-strikethrough fs-2x"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                    </button>
                                </div>
                                <div class="btn-group w-auto ms-3 py-1">
                                    <button @click="editor.chain().focus().toggleBulletList().run()" type="button" class="btn btn-color-gray-600 btn-icon  btn-active-color-primary" :class="{ 'active': editor.isActive('bulletList') }">
                                        <i class="ki-duotone ki-text-circle fs-1"><i class="path1"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i><i class="path5"></i><i class="path6"></i></i>
                                    </button>
                                    <button @click="editor.chain().focus().toggleOrderedList().run()" type="button" class="btn btn-color-gray-600 btn-icon  btn-active-color-primary" :class="{ 'active': editor.isActive('orderedList') }">
                                        <i class="ki-duotone ki-text-number fs-1"><i class="path1"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i><i class="path5"></i><i class="path6"></i></i>
                                    </button>
                                </div>
                                <div class="btn-group w-auto ms-3 py-1">
                                    <label for="editorImage" role="button" class="btn btn-color-gray-600 btn-icon  btn-active-color-primary">
                                        <i class="ki-duotone ki-picture fs-1"><i class="path1"></i><i class="path2"></i></i>
                                    </label>
                                </div>
                            </div>
                            <editor-content id="post-editor" class="editor p-4 text-gray-900" :editor="editor"/>
                            <label for="editorImage" role="button" class="position-absolute bottom-0 fs-8 fw-medium mb-3 ms-3 text-muted d-inline-flex align-items-center"><svg class="me-1" style="margin-bottom: 2px;" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.02 16.82L18.89 9.5c-.57-1.34-1.42-2.1-2.39-2.15-.96-.05-1.89.62-2.6 1.9L12 12.66c-.4.72-.97 1.15-1.59 1.2-.63.06-1.26-.27-1.77-.92l-.22-.28c-.71-.89-1.59-1.32-2.49-1.23-.9.09-1.67.71-2.18 1.72L2.02 16.6c-.62 1.25-.56 2.7.17 3.88.73 1.18 2 1.89 3.39 1.89h12.76c1.34 0 2.59-.67 3.33-1.79.76-1.12.88-2.53.35-3.76z" opacity=".4"></path><path d="M6.97 8.38a3.38 3.38 0 100-6.76 3.38 3.38 0 000 6.76z"></path></svg>{{ $t('Upload or drop images here') }}<span v-if="photoEditorUploading" class="ms-2 fa-spin d-inline-flex align-items-center"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="currentColor"/><path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="currentColor"/></svg></span></label>
                        </div>
                    </div>
                    <div v-if="editorUploadError" class="text-danger fs-sm fw-medium">{{ editorUploadError }}</div>
                    <input class="d-none" type="file" id="editorImage" @input="insertImage" accept="image/png, image/gif, image/jpeg, image/webp" />
                </div>

                <div class="form-floating">
                    <VImageUpload class="h-175px w-100" :image="post.image" @uploaded="setImages"/>
                    <label class="form-label">{{ $t('Photo') }}</label>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="form-floating mb-5">
                    <select class="form-select" id="post-category" v-model="post.category_id" @change="updateCategory">
                        <option v-for="(name, key) in $root.user.allowed_categories" :value="key">{{ name }}</option>
                    </select>
                    <label for="post-category" class="form-label required">{{ $t('Category') }}</label>
                </div>

                <div v-if="post.category_id == 8" class="form-floating mb-7">
                    <input type="datetime-local" class="form-control" id="post-event" v-model="post.event_date" :placeholder="$t('Scheduled Post')"/>
                    <label for="post-event" class="form-label required">{{ $t('Event Date') }}</label>
                </div>

                <div v-if="post.image" class="form-floating mb-5">
                    <input type="text" class="form-control" id="post-source" v-model="post.image_caption" :placeholder="$t('Image Caption')"/>
                    <label for="post-source" class="form-label required">{{ $t('Image Caption') }}</label>
                </div>

                <div class="form-floating mb-5">
                    <input type="text" class="form-control" v-model="post.keywords" id="post-keywords" :placeholder="$t('Keywords')"/>
                    <label for="post-keywords" class="form-label">{{ $t('Keywords') }}</label>
                </div>

                <div class="form-floating mb-7">
                    <input type="datetime-local" class="form-control" id="post-schedule" v-model="post.created_at" :placeholder="$t('Scheduled Post')"/>
                    <label for="post-schedule" class="form-label">{{ $t('Scheduled Post') }}</label>
                </div>

                <div class="mb-7" data-kt-buttons="true">
                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-stack text-start px-5 py-3 mb-3">
                        <div class="d-flex align-items-center me-2">
                            <div class="form-check form-check-custom form-check-solid form-check-primary me-6">
                                <input class="form-check-input" type="radio" name="add_to_slider" :value="0"/>
                            </div>

                            <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                                <div class="d-flex align-items-center fs-6 mb-0 fw-medium flex-wrap">
                                    {{ $t('No publication in the slider') }}
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-stack text-start px-5 py-3 mb-3">
                        <div class="d-flex align-items-center me-2">
                            <div class="form-check form-check-custom form-check-solid form-check-primary me-6">
                                <input class="form-check-input" type="radio" name="add_to_slider" value="big"/>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center fs-6 mb-0 fw-medium flex-wrap">
                                    {{ $t('Add To Big Slider') }}
                                </div>
                            </div>
                        </div>

                        <div class="ms-5 d-flex align-items-center">
                            <span class="fs-6 fw-bold me-1">
                                {{ $decimal(50000, 0) }}
                            </span>
                            <span class="fs-7 fw-bold opacity-50">
                                <span>₸</span>
                            </span>
                        </div>
                    </label>

                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-stack text-start px-5 py-3">
                        <div class="d-flex align-items-center me-2">
                            <div class="form-check form-check-custom form-check-solid form-check-primary me-6">
                                <input class="form-check-input" type="radio" name="add_to_slider" value="small"/>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center fs-6 mb-0 fw-medium flex-wrap">
                                    {{ $t('Add To Small Slider') }}
                                </div>
                            </div>
                        </div>

                        <div class="ms-5 d-flex align-items-center">
                            <span class="fs-6 fw-bold me-1">
                                {{ $decimal(25000, 0) }}
                            </span>
                            <span class="fs-7 fw-bold opacity-50">
                                <span>₸</span>
                            </span>
                        </div>
                    </label>
                </div>

                <Popper v-if="!$root.user.is_package_active || !['standart-plus', 'standart-maximum'].includes($root.user.package_slug)" placement="top-start" class="" hover>
                    <div>
                        <div class="form-check form-switch form-check-custom form-check-solid mb-5">
                            <input class="form-check-input" disabled="" type="checkbox" value="1" id="post-push"/>
                            <label class="form-check-label text-gray-700" for="post-push">
                                {{ $t('Send Notifications To Fcm') }}
                            </label>
                        </div>
                        <div class="form-check form-switch form-check-custom form-check-solid mb-5">
                            <input class="form-check-input" disabled="" type="checkbox" value="1" id="post-tg"/>
                            <label class="form-check-label text-gray-800" for="post-tg">
                                {{ $t('Send Notifications To Telegram') }}
                            </label>
                        </div>
                    </div>
    
                    <template #content="{ close }">
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold mw-350px min-w-325px w-auto d-block p-5">
                            <div class="d-flex">
                                <i class="ki-duotone ki-notification-bing fs-1 text-primary me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <span class="fs-4">{{ $t('Select package') }}</span>
                            </div>

                            <hr class="text-gray-500 my-3"/>

                            <div class="fw-semibold mb-3 fs-6">{{ $t('To be able to send notifications, you need to purchase the "Standart Plus" or "Standart Maximum" service package.') }}</div>
        
                            <app-link :to="{name: 'packages'}" class="btn btn-sm fs-6 fw-bold btn-primary w-100">
                                {{ $t('Select') }}
                            </app-link>
                        </div>
                    </template>
                </Popper>
                <div v-else>
                    <div class="form-check form-switch form-check-custom form-check-solid mb-5">
                        <input class="form-check-input" type="checkbox" v-model="post.to_fcm" :value="1" id="post-push"/>
                        <label class="form-check-label text-gray-800" for="post-push">
                            {{ $t('Send Notifications To Fcm') }}
                        </label>
                    </div>

                    <div class="form-check form-switch form-check-custom form-check-solid mb-5">
                        <input class="form-check-input" type="checkbox" v-model="post.to_telegram" :value="1" id="post-tg"/>
                        <label class="form-check-label text-gray-800" for="post-tg">
                            {{ $t('Send Notifications To Telegram') }}
                        </label>
                    </div>
                </div>

                <!-- <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" disabled="" :value="1" id="post-color"/>
                    <label class="form-check-label text-gray-800" for="post-color">
                        {{ $t('Special Block Style') }}
                    </label>
                </div> -->
            </div>
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
                <button :disabled="isSend" type="button" class="btn rounded-2 btn-light me-2" @click="$root.closeModal('post-editor')">{{ $t('Close') }}</button>
                <button :disabled="isSend || !$root.user.is_package_active" type="button" class="btn rounded-2 btn-light-success" @click="save">{{ post.uuid? $t('Update'): $t('Publish') }}</button>
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
import { BubbleMenu, Editor, EditorContent } from '@tiptap/vue-3'
import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import Typography from '@tiptap/extension-typography'
import Bold from '@tiptap/extension-bold'
import Italic from '@tiptap/extension-italic'
import Strike from '@tiptap/extension-strike'
import Link from '@tiptap/extension-link'
import ListItem from '@tiptap/extension-list-item'
import OrderedList from '@tiptap/extension-ordered-list'
import BulletList from '@tiptap/extension-bullet-list'
import History from '@tiptap/extension-history'
import Gapcursor from '@tiptap/extension-gapcursor'
import { TipTapCustomImage } from '@/plugins/tiptap/Image'
import VImageUpload from '@/components/VImageUpload.vue'
import Modal from '@/components/Modal.vue'
import Popper from "vue3-popper"
import showErrors from "@/helpers/notify"
import { ElNotification} from 'element-plus'

export default defineComponent({
    name: 'Editor',
    props: [],
    components: {
        Modal,
        EditorContent,
        BubbleMenu,
        VImageUpload,
        Popper,
    },
    data() {
        const postTemplate = {
            title: {
                en: '',
                ru: '',
                kk: '',
            },
            summary: {
                en: '',
                ru: '',
                kk: '',
            },
            content: {
                en: '',
                ru: '',
                kk: '',
            },
            files: [],
            category_id: '',
        }

        return {
            editor: null,
            photoEditorUploading: false,
            editorUploadError: false,
            isSend: false,
            saveError: false,
            loading: true,
            inited: false,
            locale: this.$root.locale,
            post: {...postTemplate},
            postTemplate: {...postTemplate},
        }
    },
    computed: {
        ready() {
            return this.inited && !this.loading
        }
    },
    created() {
        if (this.$root.post) {
            this.fetchData()
        } else {
            this.post = {...this.postTemplate}

            if (this.$root.postEditorEvent) {
                this.post.category_id = 8
                this.$root.postEditorEvent = false
            } else if (this.$root.user.allowed_categories) {
                this.post.category_id = Object.keys(this.$root.user.allowed_categories)[0]
            }

            this.loading = false
        }
    },
    mounted() {
        // let content = ''

        // if (this.post.uuid) {
        //     content = this.post.source
        // } else if (typeof this.$root.draft == 'string') {
        //     content = this.$root.draft
        // }

        this.editor = new Editor({
            onUpdate: ({ editor }) => {
                this.post.content[this.locale] = editor.getHTML()
                // if (!this.post.uuid) {
                //     this.$store.commit('setDraft', editor.getHTML())
                // }
            },
            onCreate: ({ editor }) => {
                this.inited = true
            },
            // content: content,
            extensions: [
                Document,
                Paragraph,
                Text,
                Typography,
                Bold,
                Italic,
                Strike,
                Link.configure({
                    openOnClick: false,
                    autolink: false,
                }),
                ListItem,
                OrderedList,
                BulletList,
                History,
                Gapcursor,
                TipTapCustomImage(this.editorUpload),
            ],
        })
    },
    beforeUnmount() {
        // if (this.post.uuid) {
            // this.$root.post = {...this.$root.postDefault}
        // }

        this.editor.destroy()
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api('post/editor', true, {
                method: 'POST',
                data: {
                    slug: this.$root.post
                }
            })
            .then(({data}) => {
                this.loading = false

                if (!data.ok) return

                let post = data.post
                if (post.created_at) {
                    post.created_at = this.$dayjs(post.created_at).format('YYYY-MM-DDTHH:mm')
                }
                this.post = post
                this.editor.commands.setContent(this.post.content[this.locale])
            })
        },
        setLocale(locale) {
            this.locale = locale
            this.editor.commands.setContent(this.post.content[this.locale])
        },
        save() {
            this.isSend = true
            
            this.$store.commit('updateCacheKey')

            this.$api(this.post.uuid? 'post/update': 'post/save', true, {
                method: 'post',
                data: { ...this.post }
            })
            .then(({data}) => {
                this.isSend = false

                if (data.ok) {
                    this.saveError = false

                    this.$root.closeModal('post-editor')

                    ElNotification({
                        type: 'success',
                        title: this.$t('Notification'),
                        message: data.message,
                        duration: 2000,
                    })

                    if (!this.post.uuid) {
                        this.$router.push({name: 'post', params: {slug: data.slug}})
                    }
                } else {
                    this.saveError = this.$t(data.message)
                }
            })
            .catch((e) => {
                this.isSend = false
                showErrors(e.response)
            })
        },
        insertImage(e) {
            if (!e.target.files.length) return

            this.editorUpload(e.target.files[0]).then((src) => {
                e.target.value = ''

                if (src) {
                    this.editor.commands.setImage({ src })
                    this.editor.commands.createParagraphNear()
                }
            })
        },
        setImages(urls) {
            this.post.image = urls.lg
        },
        async editorUpload(file) {
            this.photoEditorUploading = true
            this.editorUploadError = false

            const response = await this.$upload(file, 'original')

            this.photoEditorUploading = false

            if (response.ok) {
                return `${import.meta.env.VITE_APP_URL}/storage/${response.images.lg}`
            } else {
                if (!response) {
                    this.editorUploadError = this.$t('upload_error')
                } else if (response.message) {
                    this.editorUploadError = this.$t(response.message)
                }
            }
        },
        updateCategory() {
            if (!this.post.image && parseInt(this.post.category_id) === 8) {
                this.post.image = 'event.jpg';
            } else if (this.post.category_id !== 8 && (!this.post.image || this.post.image === 'event.jpg')) {
                this.post.image = '';
            }
        }
    },
});
</script>