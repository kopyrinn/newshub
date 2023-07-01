<template>
    <div class="row">
        <div class="col-lg-7">
            <div class="card card-flush mb-6">
                <div class="card-header card-dashed card-header-stretch">
                    <div class="card-title">
                        <h3 class="m-0 text-gray-800">{{ $t('Your latest notifications') }}</h3>
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
                            <app-link :to="item.url? item.url: ''" class="">
                                <span class="fs-6 text-gray-800 text-hover-primary fw-bold me-2">{{ item.title }}</span>
                                <span v-if="item.message" class="text-gray-700 fs-7">{{ item.message }}</span>
                            </app-link>
                        </template>
                        <template v-slot:cell-created_at="{ row: item }">
                            <VDate :datetime="new Date(item.created_at)" :key="item.created_at"/>
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
import { ElNotification} from 'element-plus'

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
        formatDate(dateString) {
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('ru-RU', {dateStyle: 'long'}).format(date);
        }
    },
});
</script>