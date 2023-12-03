<template>
    <div class="row">
        <div class="col-lg-7">
            <div class="card card-flush mb-6">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                            <i class="ki-duotone ki-magnifier fs-2"><i class="path1"></i><i class="path2"></i></i>
                            <!-- <inline-svg src="/assets/media/icons/duotune/general/gen021.svg" /> -->
                        </span>
                        <input
                            type="text"
                            v-model="table.search"
                            v-debounce:300ms="searchItems"
                            class="form-control w-250px ps-15"
                            :placeholder="$t('Search')"
                        />
                        </div>
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
                        <template v-slot:cell-checkbox="{ row: item }">
                            <div
                                class="form-check form-check-sm form-check-custom form-check-solid"
                            >
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    :value="item.id"
                                    v-model="checkedData"
                                />
                            </div>
                        </template>
                        <template v-slot:cell-title="{ row: item }">
                            <app-link :to="{name: 'post', params: {slug: item.slug}}" class="fw-bolder">{{ item.title }}</app-link>
                        </template>
                        <template v-slot:cell-created_at="{ row: item }">
                            <VDate :datetime="new Date(item.created_at)" :key="item.created_at"/>
                        </template>
                        <template v-slot:cell-actions="{ row: item }">
                            <button
                                type="button"
                                @click="$root.toggleFavorite(item)"
                                :class="{'active': item.is_favorite}"
                                class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1"
                            >
                                <i class="ki-duotone ki-star fs-2"><i class="path1"></i><i class="path2"></i></i>
                            </button>
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
    name: "Favorite",
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
                    name: this.$t('Favorite'),
                    key: "title",
                    sortable: false,
                },
                {
                    name: this.$t('Time'),
                    key: "created_at",
                    sortable: true,
                },
                {
                    name: this.$t('Actions'),
                    key: "actions",
                    sortable: false,
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

            this.$api(`account/favorite`, true, {
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

                this.table = Object.assign(this.table, data.posts);
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
    },
});
</script>