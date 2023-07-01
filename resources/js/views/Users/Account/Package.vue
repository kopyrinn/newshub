<template>
    <div class="row">
        <div class="col-lg-7">
            <div class="card card-flush mb-6">
                <div class="card-header">
                    <div class="card-title m-0">
                        <h3 class="m-0 text-gray-800">{{ $t('Your package') }} <span v-if="$root.user.package_name" class="fw-bolder text-primary">{{ $root.user.package_name }}</span></h3>
                    </div>
                    <div class="card-toolbar">
                        <span v-if="$root.user.is_package_active" class="badge py-3 px-4 fs-7 badge-light-success">
                            {{ $t('Active until') }}
                            <VDate class="ms-1" :dateOnly="true" :datetime="new Date($root.user.package_expired_at)"/>
                        </span>
                        <span v-else class="badge py-3 px-4 fs-7 badge-light-danger">
                            {{ $t('Expired on') }}
                            <VDate class="ms-1" :dateOnly="true" :datetime="new Date($root.user.package_expired_at)"/>
                        </span>
                    </div>
                </div>
                <div class="card-body pt-0 pb-0 pb-4">
                    <div class="text-gray-800 fw-semibold fs-5 mb-4">{{ $t('We recommend extending the tariff in advance in order to get maximum efficiency from our services. Please note that unused services are not carried over to the next month.') }}</div>
                    <app-link to="/packages" class="btn btn-sm btn-light-primary">{{ $root.user.is_package_active? $t('Prolong package'): $t('Select package') }}</app-link>
                </div>
                <div class="card-body p-0 pb-4">
                    <VTable
                        :loading="loading"
                        :table="table"
                        :table-header="tableHeader"
                        @current-change="currentPageChange"
                        @items-per-page-change="currentPerPageChange"
                        :enable-items-per-page-dropdown="false"
                        :enableFooter="false"
                        @sort="onSort"
                    >
                        <template v-slot:cell-name="{ row: item }">
                            <span class="fs-6 text-gray-800 fw-bold">{{ item.name }}</span>
                        </template>
                        <template v-slot:cell-count="{ row: item }">
                            <span class="fs-6 text-gray-800 fw-bold">{{ item.count }}</span>
                        </template>
                    </VTable>
                </div>
            </div>
            <div class="card card-flush mb-6">
                <div class="card-header">
                    <div class="card-title m-0">
                        <h3 class="m-0 text-gray-800">{{ $t('About packages') }}</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="text-gray-800 fw-semibold fs-5">
                        <p>{{ $t('NewsHub.kz offers its users a wide range of tariffs and discounts. At the same time, we provide one month of free use of the "Standard" package.') }}</p>
                        <p>{{ $t('This will allow you to evaluate all the benefits of using NewsHub.kz and choose the appropriate set of services that meet the requirements and financial capabilities of a company or organization. With paid service, the client can choose a different package. Each package includes discounts when paying for several months.') }}</p>
                    </div>

                    <app-link to="/packages" class="btn btn-light-primary">{{ $t('All packages') }}</app-link>
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
                    name: this.$t('Service'),
                    key: "name",
                    sortable: false,
                },
                {
                    name: this.$t('Available'),
                    key: "count",
                    sortable: false,
                },
            ],
            table: {
                current_page: 1,
                data: [],
                per_page: 15,
                total: 0,
                search: "",
                sort: "count",
                order: "desc",
                top_pagination: false,
            },
        }
    },
    created() {
        if (this.$route.meta.noSsr && import.meta.env.SSR) return false

        this.loadData()
    },
    methods: {
        loadData() {
            this.table.data = [
                {
                    name: 'Самостоятельное размещение, редактирование и удаление материалов в рубрике пресс-релизы (Пресс-центр) с возможностью прикрепления дополнительных файлов для скачивания',
                    count: this.$root.user.package_press,
                },
                {
                    name: 'Самостоятельное размещение, редактирование и удаление материалов в рубрике «События»',
                    count: this.$root.user.package_events,
                },
                {
                    name: 'Самостоятельное размещение, редактирование и удаление материалов в рубрике «Вакансия»',
                    count: this.$root.user.package_vacancies,
                },
                {
                    name: 'Push-оповещение зарегистрированных на портале журналистов через мобильное приложение NewsHub.kz',
                    count: this.$root.user.package_help,
                },
                {
                    name: 'Возможность заказа перевода статьи от редакции NewsHub.kz',
                    count: this.$root.user.package_translate,
                },
                // {
                //     name: '',
                //     count: this.$root.user.package_pr,
                // },
            ]

            this.loading = false;

            // this.$api(`user/${this.slug}/actions`, true, {
            //     params: {
            //         page: this.table.current_page,
            //         per_page: this.table.per_page,
            //         search: this.table.search,
            //         sort: this.table.sort,
            //         order: this.table.order,
            //     }
            // })
            // .then(({ data }) => {
            //     this.loading = false;

            //     if (!data.ok) return

            //     this.table = Object.assign(this.table, data.actions);
            // })
            // .catch(({ response }) => {
            //     this.loading = false;
            //     showErrors(response);
            // });
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