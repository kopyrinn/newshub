<template>
    <div class="mb-17">
        <div class="mb-8">
            <h1 class="text-dark fs-1 fw-bold">
                <Skeletor width="100%"/><br/><Skeletor width="20%"/>
            </h1>
            <div class="d-flex flex-wrap">
                <div class="me-5 my-1 d-flex align-items-center">
                    <i class="ki-duotone ki-element-11 fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    <span class="fw-bold text-gray-400"><Skeletor width="90"/></span>
                </div>
                <div  class="me-5 my-1 d-flex align-items-center">
                    <i class="ki-duotone ki-briefcase fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                    <span class="fw-bold text-gray-400"><Skeletor width="60"/></span>
                </div>
                <div class="me-5 my-1 d-flex align-items-center">
                    <i class="ki-duotone ki-price-tag fs-2 me-2"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                    <span class="fw-bold text-gray-400"><Skeletor width="60"/></span>
                </div>
                <div class="d-flex align-items-center me-5">
                    <i class="ki-duotone ki-timer fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <span class="fw-bold text-gray-400"><Skeletor width="70"/></span>
                </div>
                <div class="d-flex align-items-center me-5">
                    <i class="ki-duotone ki-eye fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <span class="fw-bold text-gray-400 fs-7"><Skeletor width="20"/></span>
                </div>
                <div class="d-flex align-items-center me-5">
                    <i class="ki-duotone ki-star fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <span class="fw-bold text-gray-400 fs-7"><Skeletor width="60"/></span>
                </div>
            </div>

            <div class="mt-8">
                <Skeletor class="rounded" width="100%" height="350"/>
                <Skeletor class="fw-semibold mt-1 text-gray-700 fs-6" width="80"/>
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

        <div class="card card-dashed border-hover-primary mb-6">
            <div class="card-body p-5">
                <div class="d-flex overflow-hidden">
                    <div class="me-6 d-flex flex-fill flex-nowrap">
                        <div class="me-6 flex-shrink-0">
                            <div class="symbol symbol-50px w-50px bg-light my-1">
                                <Skeletor size="50"/>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-start justify-content-between">
                                <Skeletor class="fs-3 fw-bold text-dark me-5" width="100"/>
                            </div>
                            <div class="text-gray-400 fw-semibold mt-1 mb-0"><Skeletor class="fs-5" width="98%"/><Skeletor class="fs-5" width="50%"/></div>
                        </div>
                    </div>
                </div>
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