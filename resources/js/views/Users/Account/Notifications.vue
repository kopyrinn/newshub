<template>
    <div class="row">
        <div class="col-lg-7">
            <div class="card card-flush mb-6">
                <div class="card-header card-dashed card-header-stretch">
                    <div class="card-title">
                        <h3 class="m-0 text-gray-800">{{ $t('Your latest notifications') }}</h3>
                    </div>
                    <div v-if="$root.user && $root.user.notifications_count" class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light-primary" @click="markAllAsRead" :disabled="$root.user.read_notifications_loading">
                            <i class="ki-duotone ki-double-check fs-2"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                            {{ $t('Mark all as read') }}
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <VTable
                        :loading="loading"
                        :table="table"
                        :table-header="tableHeader"
                        @current-change="currentPageChange"
                        @items-per-page-change="currentPerPageChange"
                        :enable-items-per-page-dropdown="true"
                        @sort="onSort"
                    >
                        <template v-slot:cell-message="{ row: item }">
                            <app-link :to="item.url? item.url: ''" class="notification-entry d-flex align-items-start gap-3 rounded px-4 py-3" :class="{'notification-entry--unread': !item.is_read}" @click="markNotificationAsRead(item)">
                                <span v-if="!item.is_read" class="notification-unread-dot flex-shrink-0 mt-2"></span>
                                <span class="d-flex flex-column">
                                    <span class="fs-6 text-hover-primary" :class="item.is_read? 'text-gray-700 fw-normal': 'text-gray-900 fw-bolder'">{{ item.title }}</span>
                                    <span v-if="item.message" class="fs-7 mt-1" :class="item.is_read? 'text-gray-600 fw-normal': 'text-gray-800 fw-bold'">{{ item.message }}</span>
                                </span>
                            </app-link>
                        </template>
                        <template v-slot:cell-created_at="{ row: item }">
                            <span :class="item.is_read? 'fw-normal': 'text-gray-900 fw-bolder'">
                                <VDate :datetime="new Date(item.created_at)" :key="item.created_at"/>
                            </span>
                        </template>
                    </VTable>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <Sidebar/>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue"
import VTable from "@/components/VTable.vue"
import Sidebar from "@/components/Sidebar.vue"
import showErrors from "@/helpers/notify";

export default defineComponent({
    name: "Notifications",
    components: {
        Sidebar,
        VTable,
    },
    data() {
        return {
            slug: this.$route.params.slug,
            loading: true,
            checkedData: [],
            tableHeader: [
                {
                    name: this.$t('Message'),
                    key: "message",
                    sortable: false,
                },
                {
                    name: this.$t('Time'),
                    key: "created_at",
                    sortable: false,
                },
            ],
            table: {
                current_page: 1,
                data: [],
                per_page: 15,
                total: 0,
                search: "",
                sort: "",
                order: "desc",
                top_pagination: false,
            },
        }
    },
    created() {
        if (this.$route.meta.noSsr && import.meta.env.SSR) return false

        this.fetchData()
    },
    methods: {
        fetchData() {
            this.loading = true;

            this.$api(`account/notifications`, true, {
                params: {
                    page: this.table.current_page,
                    per_page: this.table.per_page,
                    search: this.table.search,
                    sort: this.table.sort,
                    order: this.table.order,
                }
            })
            .then(({ data }) => {
                this.loading = false;

                if (!data.ok) return

                this.table = Object.assign(this.table, data.notifications);
                this.$root.setNotificationCount(data.unread_count);
            })
            .catch(({ response }) => {
                this.loading = false;
                showErrors(response);
            });
        },
        currentPageChange(page) {
            this.table.current_page = page;
            this.fetchData();
        },
        currentPerPageChange(per_page) {
            this.table.per_page = per_page;
            this.fetchData();
        },
        searchItems() {
            if (!this.table.search.length || this.table.search.length > 2) {
                this.fetchData();
            }
        },
        onSort(sort) {
            this.table.sort = sort.columnName
            this.table.order = sort.order
            this.fetchData();
        },
        markNotificationAsRead(notification) {
            return this.$root.markNotificationAsRead(notification)
        },
        async markAllAsRead() {
            const markedAsRead = await this.$root.markAsRead()

            if (markedAsRead) {
                this.table.data.forEach(notification => notification.is_read = true)
            }
        },
        formatDate(dateString) {
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('ru-RU', {dateStyle: 'long'}).format(date);
        }
    },
});
</script>

<style scoped>
.notification-entry {
    min-width: 0;
    border-left: 3px solid transparent;
    transition: background-color .15s ease, border-color .15s ease;
}

.notification-entry--unread {
    border-left-color: var(--bs-primary);
    background-color: rgba(var(--bs-primary-rgb), .08);
}

.notification-unread-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: var(--bs-primary);
}
</style>
