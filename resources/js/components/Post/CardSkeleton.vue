<template>
    <div class="card card-flush mb-6">
        <div class="card-header px-6 pt-0 pb-0">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-40px me-5">
                    <Skeletor class="rounded-3" size="40"/>
                </div>
                <div class="flex-grow-1">
                    <a href="#" class="text-gray-800 text-hover-primary fs-4 fw-bold"><Skeletor width="180"/></a>
                    <span class="text-gray-400 fw-semibold d-block"><Skeletor width="120"/></span>
                </div>
            </div>
        </div>

        <div class="card-body px-0 py-0">
            <div class="fs-3 px-6 fw-semibold text-gray-700 mb-3"><Skeletor width="95%"/></div>
            <div class="fs-6 px-6 fw-normal text-gray-700"><Skeletor width="85%"/><br/><Skeletor width="75%"/></div>
            <div class="mt-4"><Skeletor width="100%" height="250"/></div>
        </div>

        <div class="card-footer px-6 py-0">
            <ul class="nav py-3">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary btn-active-light-primary fw-bold px-4 me-1">
                        <i class="ki-duotone ki-message-text-2 fs-2 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> <Skeletor width="80"/>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary fw-bold px-4 me-1">
                        <i class="ki-duotone ki-heart fs-2 me-1"><span class="path1"></span><span class="path2"></span></i> <Skeletor width="60"/>
                    </a>
                </li>
            </ul>
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