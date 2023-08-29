<template>
    <div class="d-flex flex-column flex-center text-center pt-10 mb-">
        <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">
            {{ $t('Oops!') }}
        </h1>

        <div class="fw-semibold fs-6 text-gray-500 mb-7">
            {{ $t('We cant find that page') }}
        </div>

        <div>
            <app-link to="/" class="btn btn-light-primary">{{ $t('Return home') }}</app-link>
        </div>

        <div>
            <img :src="$media('auth/404.png')" class="mw-100 mh-400px mh-lg-600px theme-light-show" alt="" loading="lazy">
            <img :src="$media('auth/404-dark.png')" class="mw-100 mh-400px mh-lg-600px theme-dark-show" alt="" loading="lazy">
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";

export default defineComponent({
    name: '404',
    head() {
        return {
            title: '404',
        }
    },
});
</script>