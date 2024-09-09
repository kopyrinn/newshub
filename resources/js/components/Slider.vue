<template>
    <Carousel :autoplay="3000" :wrapAround="true" class="w-100 h-400px mw-xl-700px w-xl-700px carousel carousel-custom flex-shrink-0" v-model="currentSlide">
        <Slide v-for="item in $root.config.postSlides" :key="item.image" class="h-400px overflow-hidden">
            <div class="carousel__item position-relative w-100 h-100">
                <div class="slide-item h-100 w-100">
                    <picture>
                        <source media="(max-width: 500px)" :srcset="$storage(item.image_fit)" />
                        <source media="(min-width: 501px)" :srcset="$storage(item.image_md)" />
                        <img class="object-fit-cover object-position-center h-100 w-100" :src="$storage(item.image_md)"/>
                    </picture>
                    <div class="position-absolute h-100 w-100 bg-black bg-opacity-50 top-0 bottom-0 text-start">
                        <div class="h-100 d-flex flex-column justify-content-end py-10 px-10"> 
                            <div class="fs-2qx fw-bold text-white mb-6 text-truncate-2">{{ item.title }}</div>
                            <div class="fw-semibold text-white fs-6 mb-8 opacity-75 text-truncate-2">{{ item.summary }}</div>
                            <div class="d-flex flex-column flex-sm-row d-grid gap-2">
                                <app-link :to="{name: 'post', params: {slug: item.slug}}" class="btn btn-primary flex-shrink-0" style="background: rgba(255, 255, 255, 0.2)">{{ $t('Read more') }}</app-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Slide>
        <template #addons>
            <div class="position-absolute bottom-0 end-0 mb-4 me-4">
                <ol class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-primary">
                    <li v-for="n in $root.config.postSlides.length" class="ms-1" :class="{'active': n == currentSlide + 1}"></li>
                </ol>
            </div>
        </template>
    </Carousel>
</template>

<script>
import { defineComponent } from "vue";
import { Carousel, Slide } from 'vue3-carousel'

export default defineComponent({
    name: "Slider",
    components: {
        Carousel,
        Slide,
    },
    data() {
        return {
            currentSlide: null,
        }
    },
});
</script>