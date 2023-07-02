<template>
    <div v-if="$root.config.lastVacancies.length" class="card card-flush mb-6 overflow-hidden">
        <div class="card-body px-5 pt-6 pb-7">
            <div class="fw-semibold">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fs-3 mb-0">{{ $t('Vacancies') }}</h3>
                    <button v-if="$root.token" class="btn btn-sm btn-light-primary px-3 py-2" @click="$root.vacancy = null, $root.openModal('vacancy-editor')">{{ $t('Publish')}}</button>
                </div>
                <div v-for="(item, index) in $root.config.lastVacancies" class="d-flex align-items-center text-truncate" :class="{'mb-5': index + 1 < $root.config.lastVacancies.length}">
                    <div class="text-truncate">
                        <app-link :to="{name: 'vacancy', params: {slug: item.id}}" class="text-gray-800 text-hover-primary fs-4 fw-bold text-truncate">{{ item.job_title }}</app-link>
                        <span class="text-gray-600 fw-semibold d-block"><VDate :datetime="new Date(item.created_at)"/></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"

export default defineComponent({
    name: 'RecommendedVacancies',
})
</script>