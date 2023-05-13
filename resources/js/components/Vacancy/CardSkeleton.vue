<template>
    <div class="card card-flush mb-6">
        <div class="card-header px-6 pt-0 pb-0">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-40px me-5">
                    <Skeletor class="rounded" size="40"/>
                </div>
                <div class="flex-grow-1">
                    <a href="#" class="text-gray-800 text-hover-primary fs-4 fw-bold"><Skeletor width="200"/></a>
                    <span class="text-gray-400 fw-semibold d-block"><Skeletor width="120"/></span>
                </div>
            </div>
        </div>

        <div class="card-body px-6 pt-0 pb-4">
            <div class="fs-3 fw-semibold text-gray-700 mb-3"><Skeletor width="90%"/></div>
            <div class="fs-6 fw-normal text-gray-700"><Skeletor width="85%"/><br/><Skeletor width="75%"/></div>
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