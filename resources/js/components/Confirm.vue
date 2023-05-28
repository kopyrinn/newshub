<template>
    <div v-if="$root.confirmation" class="modal fade d-block" :class="{'show': show}" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true" style="z-index: 3055;">
        <OnClickOutside @trigger="_cancel" class="modal-dialog modal-sm modal-dialog-centered" :class="{'w-75 mx-auto': $root.isMobile}" role="document">
            <div class="modal-content overflow-hidden">
                <div class="modal-body text-center">
                    <svg width="48" height="48" class="text-primary d-sm-none mb-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/><path d="M11.276 13.654C11.276 13.2713 11.3367 12.9447 11.458 12.674C11.5887 12.394 11.738 12.1653 11.906 11.988C12.0833 11.8107 12.3167 11.61 12.606 11.386C12.942 11.1247 13.1893 10.896 13.348 10.7C13.5067 10.4947 13.586 10.2427 13.586 9.944C13.586 9.636 13.4833 9.356 13.278 9.104C13.082 8.84267 12.69 8.712 12.102 8.712C11.486 8.712 11.066 8.866 10.842 9.174C10.6273 9.482 10.52 9.82267 10.52 10.196L10.534 10.574H8.826C8.78867 10.3967 8.77 10.2333 8.77 10.084C8.77 9.552 8.90067 9.07133 9.162 8.642C9.42333 8.20333 9.81067 7.858 10.324 7.606C10.8467 7.354 11.4813 7.228 12.228 7.228C13.1987 7.228 13.9687 7.44733 14.538 7.886C15.1073 8.31533 15.392 8.92667 15.392 9.72C15.392 10.168 15.322 10.5507 15.182 10.868C15.042 11.1853 14.874 11.442 14.678 11.638C14.482 11.834 14.2253 12.0533 13.908 12.296C13.544 12.576 13.2733 12.8233 13.096 13.038C12.928 13.2527 12.844 13.528 12.844 13.864V14.326H11.276V13.654ZM11.192 15.222H12.928V17H11.192V15.222Z" fill="currentColor"/></svg>
                    <h3 class="mb-0">{{ title }}</h3>
                    <div v-if="message" class="mt-3 fw-medium">{{ message }}</div>
                </div>
                <div class="btn-group btn-group-lg rounded-0 overflow-hidden w-100" role="group">
                    <button type="button" :disabled="this.timer > 0" class="w-50 btn btn-lg rounded-0 btn-alt-secondary text-primary fw-bolder" @click="_confirm">{{ ok }} <span v-if="this.timer > 0">({{ timer }}{{ $t('s.') }})</span></button>
                    <button type="button" class="w-50 btn btn-lg rounded-0 btn-alt-secondary text-danger fw-bolder" @click="_cancel">{{ no }}</button>
                </div>
            </div>
        </OnClickOutside>
    </div>
</template>
<script>
import { defineComponent, defineAsyncComponent } from "vue"
import { OnClickOutside } from '@vueuse/components'

export default defineComponent({
    name: 'Confirm',
    components: {
        OnClickOutside
    },
    data() {
        return {
            timer: 5,
            show: false,
            title: undefined,
            message: undefined,
            ok: this.$t('Yes'),
            no: this.$t('Cancel'),
            resolvePromise: undefined,
            rejectPromise: undefined,
        }
    },
    methods: {
        open(opts = {}) {
            this.title = opts.title
            this.message = opts.message
            this.ok = opts.ok || this.$t('Yes')
            this.no = opts.no || this.$t('Cancel')

            document.body.classList.add('confirm-open')
            setTimeout(() => this.show = true, 5)

            this.timer = typeof opts.timer != 'undefined'? opts.timer: 2

            const confirmTimer = () => {
                if (this.timer <= 0) return

                setTimeout(() => {
                    this.timer -= 1
                    confirmTimer()
                }, 1000)
            }

            confirmTimer()

            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve
                this.rejectPromise = reject
            })
        },

        _confirm() {
            this.close()
            this.resolvePromise(true)
        },

        _cancel() {
            this.close()
            this.resolvePromise(false)
        },

        close() {
            this.show = false
            document.body.classList.remove('confirm-open')
            setTimeout(() => {
                this.$root.confirmation = false
            }, 250)
        }
    }
})
</script>