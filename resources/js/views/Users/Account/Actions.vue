<template>
    <div class="row">
        <div class="col-lg-7">
            <div class="card card-flush mb-6">
                <div class="card-header card-header-stretch">
                    <div class="card-title">
                        <h3 class="m-0 text-gray-800">{{ $t('Chronology of actions in your account') }}</h3>
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
                        <template v-slot:cell-name="{ row: item }">
                            <span class="fw-bolder">{{ item.name }}</span>
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
    name: "Actions",
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
                    name: this.$t('Actions'),
                    key: "name",
                    sortable: false,
                },
                {
                    name: this.$t('Time'),
                    key: "created_at",
                    sortable: true,
                },
            ],
            table: {
                current_page: 1,
                data: [],
                per_page: 15,
                total: 0,
                search: "",
                sort: "created_at",
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

            this.$api(`user/${this.slug}/actions`, true, {
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

                this.table = Object.assign(this.table, data.actions);
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