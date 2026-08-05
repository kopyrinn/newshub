<template>
    <div class="event-meta rounded-3 border border-dashed border-gray-300 bg-light px-3 px-md-4 py-1" data-testid="event-meta">
        <div class="event-meta__row" data-testid="event-date-time">
            <span class="event-meta__icon">
                <i class="ki-duotone ki-calendar-tick fs-2 text-primary"><i class="path1"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i><i class="path5"></i><i class="path6"></i></i>
            </span>
            <span class="d-flex flex-column min-w-0">
                <span class="event-meta__label">{{ $t('Date and time') }}</span>
                <span class="event-meta__value text-gray-900">{{ formattedEventDate }}</span>
            </span>
        </div>

        <div class="event-meta__row" data-testid="event-location">
            <span class="event-meta__icon">
                <i class="ki-duotone ki-geolocation fs-2 text-primary"><i class="path1"></i><i class="path2"></i></i>
            </span>
            <span class="d-flex flex-column min-w-0">
                <span class="event-meta__label">{{ $t('Location') }}</span>
                <span class="event-meta__value text-gray-900">{{ item.place || $t('Not specified') }}</span>
            </span>
        </div>

        <div class="event-meta__row" data-testid="event-publisher">
            <span class="event-meta__icon">
                <i class="ki-duotone ki-profile-circle fs-2 text-primary"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
            </span>
            <span class="d-flex flex-column min-w-0">
                <span class="event-meta__label">{{ $t('Posted by') }}</span>
                <app-link :to="{name: 'user', params: {slug: item.user_id}}" class="event-meta__value text-gray-900 text-hover-primary">{{ item.name }}</app-link>
            </span>
        </div>

        <div class="event-meta__row" data-testid="event-contacts">
            <span class="event-meta__icon">
                <i class="ki-duotone ki-address-book fs-2 text-primary"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
            </span>
            <span class="d-flex flex-column min-w-0">
                <span class="event-meta__label">{{ $t('Contacts') }}</span>
                <span v-if="item.phone || item.email" class="event-meta__contacts">
                    <a v-if="item.phone" :href="`tel:${item.phone}`" class="event-meta__value text-gray-900 text-hover-primary">{{ item.phone }}</a>
                    <a v-if="item.email" :href="`mailto:${item.email}`" class="event-meta__value text-gray-900 text-hover-primary text-break">{{ item.email }}</a>
                </span>
                <span v-else class="event-meta__value text-gray-900">{{ $t('Not specified') }}</span>
            </span>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
    name: 'EventMeta',
    props: {
        item: {
            type: Object,
            required: true,
        },
    },
    computed: {
        formattedEventDate() {
            if (!this.item.event_date) return this.$t('Not specified')

            return this.$dayjs(this.item.event_date).format('DD MMMM YYYY, HH:mm')
        },
    },
})
</script>

<style scoped>
.event-meta__row {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 8px 0;
}

.event-meta__row + .event-meta__row {
    border-top: 1px dashed var(--bs-gray-300);
}

.event-meta__icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: rgba(var(--bs-primary-rgb), .1);
}

.event-meta__icon :deep(i) {
    font-size: 17px !important;
}

.event-meta__label {
    margin-bottom: 1px;
    color: var(--bs-gray-600);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .04em;
    line-height: 1.3;
    text-transform: uppercase;
}

.event-meta__value {
    font-size: 14px;
    font-weight: 600;
    line-height: 1.35;
}

.event-meta__contacts {
    display: flex;
    flex-wrap: wrap;
    gap: 4px 16px;
}

@media (min-width: 768px) {
    .event-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        column-gap: 16px;
    }

    .event-meta__row + .event-meta__row {
        border-top: 0;
    }

    .event-meta__row:nth-child(n + 3) {
        border-top: 1px dashed var(--bs-gray-300);
    }

    .event-meta__row:nth-child(even) {
        border-left: 1px dashed var(--bs-gray-300);
        padding-left: 16px;
    }
}
</style>
