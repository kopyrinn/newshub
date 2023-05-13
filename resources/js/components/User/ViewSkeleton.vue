<template>

    <div class="card mb-5 mb-xxl-8">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap">
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-150px symbol-fixed position-relative">
                        <Skeletor width="100%" height="100%" class="w-100px h-100px h-lg-150px w-lg-150px rounded"/>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><Skeletor width="180"/></span>
                            </div>

                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="ki-duotone ki-profile-circle fs-4 me-1"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span></i> <Skeletor width="100"/>
                                </a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="ki-duotone ki-call fs-4 me-1"><i class="path1"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i><i class="path5"></i><i class="path6"></i><i class="path7"></i><i class="path8"></i></i> <Skeletor width="100"/>
                                </a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <i class="ki-duotone ki-sms fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> <Skeletor width="100"/>
                                </a>
                            </div>
                        </div>

                        <div class="d-flex my-4">
                            <Skeletor width="115" height="35" class="rounded"/>
                        </div>
                    </div>
                    
                    <div class="text-gray-800 fs-6 fw-semibold"><Skeletor width="87%"/><br/><Skeletor width="89%"/><br/><Skeletor width="70%"/></div>
                </div>
            </div>

            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                <li class="nav-item mt-2">
                    <a @click.prevent="" class="nav-link text-active-primary ms-0 me-10 py-5 active" href=""><Skeletor width="55"/></a>
                </li>
            </ul>
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