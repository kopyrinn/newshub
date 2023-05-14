<template>
    <div class="d-flex flex-column flex-center text-center p-10">
        <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">
            {{ $t('Oops!') }}
        </h1>

        <div class="fw-semibold fs-6 text-gray-500 mb-7">
            {{ $t('We can`t find that page.') }}
        </div>

        <div class="mb-3">
            <img :src="$media('auth/404-error.png')" class="mw-100 mh-300px theme-light-show" alt="">
            <img :src="$media('auth/404-error-dark.png')" class="mw-100 mh-300px theme-dark-show" alt="">
        </div>

        <div class="mb-0">
            <app-link to="/" class="btn btn-sm btn-primary">{{ $t('Return Home') }}</app-link>
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