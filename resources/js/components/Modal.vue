<template>
    <div class="modal fade d-block" :class="{'show': show}" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
        <OnClickOutside @trigger="outsideClick" class="modal-dialog modal-dialog-scrollable" :class="dialogClass" role="document">
            <div class="modal-content" :class="{'blockui position-relative overflow-hidden': loading}">
                <div class="modal-header">
                    <h3 class="modal-title d-flex align-items-center"><slot name="title"/></h3>

                    <div class="d-flex flex-nowrap">
                        <div @click="fs = !fs" class="btn btn-icon btn-sm btn-active-light-primary ms-2">
                            <i class="ki-duotone ki-arrow-two-diagonals fs-1"><i class="path1"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i><i class="path5"></i></i>
                        </div>
                        <div v-if="canClose" @click="close" class="btn btn-icon btn-sm btn-active-light-primary ms-2">
                            <i class="ki-duotone ki-cross fs-1"><i class="path1"></i><i class="path2"></i></i>
                        </div>
                    </div>
                </div>
                <div class="modal-body py-0" :class="contentClass">
                    <div class="py-6">
                        <slot></slot>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <slot name="footer" />
                </div>
                <div v-if="loading" class="blockui-overlay z-index-1"><span class="spinner-border text-primary"></span></div>
            </div>
        </OnClickOutside>
    </div>
</template>
<script>
import { defineComponent } from "vue"
import { OnClickOutside } from '@vueuse/components'

export default defineComponent({
    name: 'Modal',
    props: {
        name: {
            type: String,
            required: true,
        },
        bodyClass: {
            type: String,
            required: false,
            default: '',
        },
        contentClass: {
            type: String,
            required: false,
            default: '',
        },
        loading: {
            type: Boolean,
            required: false,
            default: false
        },
        outside: {
            type: Boolean,
            required: false,
            default: true
        },
        canClose: {
            type: Boolean,
            required: false,
            default: true
        },
        fsMode: {
            type: Boolean,
            required: false,
            default: false
        },
    },
    components: {
        OnClickOutside,
    },
    computed: {
        dialogClass() {
            return this.bodyClass + (this.fs? ' modal-fullscreen': ' modal-dialog-centered')
        }
    },
    data() {
        return {
            show: false,
            fs: this.fsMode,
        }
    },
    mounted() {
        setTimeout(() => {
            this.show = true
        }, 10);
    },
    methods: {
        close() {
            this.$root.closeModal(this.name)
        },
        outsideClick() {
            if (this.$root.confirmation) return

            if (this.outside) {
                this.close()
            }
        }
    }
})
</script>