<template>
    <div class="card card-flush mb-6">
        <div class="card-body p-5">
            <div class="d-flex overflow-hidden">
                <div class="me-6 d-flex flex-fill flex-nowrap">
                    <div class="me-6 flex-shrink-0">
                        <div class="symbol symbol-50px w-50px bg-light rounded-3 my-1">
                            <Skeletor size="50" class="rounded-3"/>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="fs-3 fw-bold text-dark me-5"><Skeletor width="160"/></div>
                        </div>
                        <div class="text-gray-400 fw-semibold fs-5 mt-1 mb-0"><Skeletor width="97%"/><br/><Skeletor width="60%"/></div>
                    </div>
                </div>
                <div>
                    <Skeletor width="115" height="35" class="rounded d-none d-xl-inline-block"/>
                    <Skeletor size="35" class="rounded d-inline-block d-xl-none"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"

export default defineComponent({
    name: 'CardSkeleton',
    components: {
        
    },
    data() {
        return {
            
        }
    },
    methods: {
        
    }
})
</script>