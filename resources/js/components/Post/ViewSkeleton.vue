<template>
    <div class="mb-17">
        <div class="mb-8">
            <div class="d-flex flex-wrap mb-6">
                <div class="me-9 my-1 d-flex align-items-center">
                    <i class="ki-duotone ki-element-11 text-primary fs-2 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    <span class="fw-bold text-gray-400"><Skeletor width="120"/></span>
                </div>
                <div class="me-9 my-1 d-flex align-items-center">
                    <i class="ki-duotone ki-briefcase text-primary fs-2 me-1"><span class="path1"></span><span class="path2"></span></i>
                    <span class="fw-bold text-gray-400"><Skeletor width="120"/></span>
                </div>
                <div class="my-1 d-flex align-items-center">
                    <i class="ki-duotone ki-message-text-2 text-primary fs-2 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <span class="fw-bold text-gray-400"><Skeletor width="120"/></span>
                </div>
            </div>

            <h1 class="text-dark fs-1 fw-bold">
                <Skeletor width="100%"/><br/><Skeletor width="20%"/>
                <span class="fw-bold text-muted fs-5 ps-1"><Skeletor width="100"/></span>
            </h1>

            <div class="mt-8">
                <Skeletor class="rounded" width="100%" height="350"/>
            </div>
        </div>

        <div class="fs-5 fw-medium text-gray-900">
            <Skeletor width="98%"/>
            <Skeletor width="100%"/>
            <Skeletor width="98%"/>
            <Skeletor width="96%"/>
            <Skeletor width="99%"/>
            <Skeletor width="50%" class="mb-8"/>
            <Skeletor width="98%"/>
            <Skeletor width="100%"/>
            <Skeletor width="98%"/>
            <Skeletor width="96%"/>
            <Skeletor width="99%"/>
            <Skeletor width="50%" class="mb-8"/>
            <Skeletor width="98%"/>
            <Skeletor width="100%"/>
            <Skeletor width="98%"/>
            <Skeletor width="96%"/>
            <Skeletor width="99%"/>
            <Skeletor width="50%" class="mb-8"/>
        </div>

        <div class="d-flex align-items-center border-1 border-dashed card-rounded p-5 p-lg-10 mb-14">
            <div class="text-center flex-shrink-0 me-7 me-lg-13">
                <div class="symbol symbol-70px symbol-circle mb-2">
                    <Skeletor circle size="70"/>
                </div>

                <div class="mb-0">
                    <span class="text-gray-700 fw-bold text-hover-primary"><Skeletor width="100"/></span>
                </div>
            </div>

            <div class="mb-0 fs-6 w-100">
                <div class="text-muted fw-semibold lh-lg mb-2">
                    <Skeletor width="98%"/>
                    <Skeletor width="96%"/>
                    <Skeletor width="99%"/>
                    <Skeletor width="50%"/>
                </div>

                <span class="fw-semibold link-primary"><Skeletor width="150"/></span>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"

export default defineComponent({
    name: 'ViewSkeleton',
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