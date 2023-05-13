<template>
    <div class="d-flex flex-column flex-center text-center p-10">
        <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">
            Oops!
        </h1>

        <div class="fw-semibold fs-6 text-gray-500 mb-7">
            We can't find that page.
        </div>

        <div class="mb-3">
            <img src="/assets/media/auth/404-error.png" class="mw-100 mh-300px theme-light-show" alt="">
            <img src="/assets/media/auth/404-error-dark.png" class="mw-100 mh-300px theme-dark-show" alt="">
        </div>

        <div class="mb-0">
            <app-link to="/" class="btn btn-sm btn-primary">Return Home</app-link>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";

export default defineComponent({
    name: '404',
    components: {
    },
    data() {
        return {
            
        }
    },
    created() {
        
    },
    methods: {

    }
});
</script>