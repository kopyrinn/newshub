<template>
    <div class="row g-9">
        <div class="col-md-7 col-xl-7">
            <app-link v-for="category in $root.config.users" :to="{name: 'user-category', params: {slug: category.slug}}" class="card border-hover-primary mb-6">
                <div class="card-body p-5">
                    <div class="d-flex">
                        <div class="me-6">
                            <div class="symbol symbol-50px w-50px bg-light">
                                <span class="symbol-label bg-light-success">
                                    <i class="ki-duotone fs-2hx text-success" :class="'ki-' + category.icon">
                                        <i v-for="n in category.layers" :class="'path' + n"></i>
                                    </i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark">{{ category.name }}</div>
                            <p class="text-gray-400 fw-semibold fs-5 mt-1 mb-0">{{ category.users_count }} Пользователей / {{ category.posts_count }} Записей</p>
                        </div>
                    </div>
                </div>
            </app-link>
        </div>
        <div class="col-lg-5">
            <Sidebar/>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import Sidebar from "@/components/Sidebar.vue"

export default defineComponent({
    name: "Users",
    components: {
        Sidebar
    },
    data() {
        return {
            
        }
    },
    head() {
        return {
            title: this.$root.meta.title
        }
    },
    methods: {
    },
});
</script>