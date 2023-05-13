<template>
    <div class="card card-flush mb-6">
        <div class="card-body px-0 pt-4 pb-0">
            <div class="fs-3 px-6 fw-semibold text-gray-700 mb-3"><Skeletor width="90%"/></div>
            <div class="mt-4"><Skeletor width="100%" height="250"/></div>
        </div>

        <div class="card-footer px-6 py-0">
            <ul class="nav py-3">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary btn-active-light-primary fw-bold px-4 me-1">
                        <Skeletor width="80"/>
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