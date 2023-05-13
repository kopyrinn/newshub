<template>
  <div class="row">
    <!-- <div
      class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"
    >
      <div
        v-if="enableItemsPerPageDropdown"
        class="dataTables_length"
        id="kt_customers_table_length"
      >
        <label
          ><select
            name="kt_customers_table_length"
            class="form-select form-select-sm form-select-solid"
            @change="setItemsPerPage"
          >
            <option value="15">15</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select></label
        >
      </div>
    </div> -->
    <div
      class="col-12 d-flex align-items-center justify-content-center justify-content-md-end mb-2"
    >
      <paginate
        v-if="table.top_pagination"
        v-model:current-page="table.current_page"
        :page-count="table.last_page || 0"
        :page-range="5"
        :margin-pages="0"
        :click-handler="currentPageChange"
        :container-class="'pagination'"
        :page-link-class="'page-link cursor-pointer'"
        :prev-link-class="'page-link cursor-pointer'"
        :next-link-class="'page-link cursor-pointer'"
        :page-class="'page-item'"
        :prev-class="'page-item previous'"
        :next-class="'page-item next'"
        :prev-text="`<i class='previous'></i>`"
        :next-text="`<i class='next'></i>`"
      >
      </paginate>
    </div>
  </div>

  <div class="dataTables_wrapper dt-bootstrap4 no-footer">
    <div class="table-responsive">
      <table
        :class="[loading && 'overlay overlay-block min-h-300px']"
        class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
        id="kt_customers_table"
        role="grid"
      >
        <thead>
          <tr
            class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0"
            role="row"
          >
            <!-- <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 29.25px;">
              <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1">
              </div>
            </th> -->
            <template v-for="(cell, i) in tableHeader" :key="i">
              <th
                @click="
                  sort(
                    cell.sortingField ? cell.sortingField : cell.key,
                    cell.sortable
                  )
                "
                :class="[
                  cell.name && 'min-w-125px',
                  cell.sortable !== false && 'sorting',
                  tableHeader.length - 1 === i && 'text-end',
                  currentSort ===
                    `${cell.sortingField ? cell.sortingField : cell.key}desc` &&
                    'sorting_desc',
                  currentSort ===
                    `${cell.sortingField ? cell.sortingField : cell.key}asc` &&
                    'sorting_asc',
                ]"
                tabindex="0"
                rowspan="1"
                colspan="1"
                style="cursor: pointer"
              >
                {{ cell.name }}
              </th>
            </template>
          </tr>
        </thead>
        <tbody class="fw-bold text-gray-600">
          <template v-if="getItems.length">
            <template v-for="(item, i) in getItems" :key="i">
              <tr class="odd">
                <template v-for="(cell, i) in tableHeader" :key="i">
                  <td :class="{ 'text-end': tableHeader.length - 1 === i }">
                    <slot :name="`cell-${cell.key}`" :row="item">
                      {{ item[prop] }}
                    </slot>
                  </td>
                </template>
              </tr>
            </template>
          </template>
          <template v-else-if="!loading">
            <tr class="odd">
              <td colspan="7" class="dataTables_empty text-center">
                  <!--begin::Icon-->
                  <div class="py-10">
                      <!--begin::Svg Icon | path: icons/duotune/files/fil024.svg-->
                      <span class="svg-icon svg-icon-4x opacity-50">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path opacity="0.3" d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" fill="currentColor" />
                              <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z" fill="currentColor" />
                              <rect x="13.6993" y="13.6656" width="4.42828" height="1.73089" rx="0.865447" transform="rotate(45 13.6993 13.6656)" fill="currentColor" />
                              <path d="M15 12C15 14.2 13.2 16 11 16C8.8 16 7 14.2 7 12C7 9.8 8.8 8 11 8C13.2 8 15 9.8 15 12ZM11 9.6C9.68 9.6 8.6 10.68 8.6 12C8.6 13.32 9.68 14.4 11 14.4C12.32 14.4 13.4 13.32 13.4 12C13.4 10.68 12.32 9.6 11 9.6Z" fill="currentColor" />
                          </svg>
                      </span>
                      <!-- <span class="svg-icon svg-icon-4x opacity-50">
                      </span> -->
                      <!--end::Svg Icon-->
                  </div>
                  <!--end::Icon-->
                  <!--begin::Message-->
                  <div class="fw-semibold">
                      <h3 class="text-gray-600 fs-5 mb-2">{{ $t('Not found results') }}</h3>
                  </div>
                  <!--end::Message-->
                <!-- <div class="w-300px h-200px mx-auto my-3 bgi-position-center bgi-size-cover" style="background-image: url(/assets/media/illustrations/dozzy-1/5.png);"></div> -->
                <!-- <span class="fs-4">{{ emptyTableText }}</span> -->
              </td>
            </tr>
          </template>
        </tbody>
        <div
          v-if="loading"
          class="overlay-layer card-rounded bg-dark bg-opacity-5"
        >
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">{{ $t('Please, wait') }} ...</span>
          </div>
        </div>
      </table>
    </div>

    <div class="row">
      <div
        class="col-sm-12 col-md-3 d-flex align-items-center justify-content-center justify-content-md-start"
      >
        <div
          v-if="enableItemsPerPageDropdown"
          class="dataTables_length"
        >
          <div class="form-floating">
              <select class="form-select form-select-solid min-w-100px" @change="setItemsPerPage" id="kt_customers_table_length">
                  <option value="15">15</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
              </select>
              <label for="kt_customers_table_length">{{ $t('Per page') }}</label>
          </div>
          <!-- <label
            ><select
              name="kt_customers_table_length"
              class="form-select form-select-sm form-select-solid"
            >
            </select></label
          > -->
        </div>
      </div>
      <div
        class="col-sm-12 col-md-9 d-flex align-items-center justify-content-center justify-content-md-end"
      >
        <paginate
          v-model:current-page="table.current_page"
          :page-count="table.last_page || 0"
          :page-range="5"
          :margin-pages="0"
          :click-handler="currentPageChange"
          :container-class="'pagination'"
          :page-link-class="'page-link cursor-pointer'"
          :prev-link-class="'page-link cursor-pointer'"
          :next-link-class="'page-link cursor-pointer'"
          :page-class="'page-item'"
          :prev-class="'page-item previous'"
          :next-class="'page-item next'"
          :prev-text="`<i class='previous'></i>`"
          :next-text="`<i class='next'></i>`"
        >
        </paginate>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {
  computed,
  defineComponent,
  ref,
  watch,
  getCurrentInstance,
} from "vue";
import Paginate from "vuejs-paginate-next"

interface IHeaderConfiguration {
  name?: string;
  key: string;
  sortingField?: string;
  sortable?: boolean;
}

export default defineComponent({
  name: "VTable",
  emits: ["current-change", "sort", "items-per-page-change"],
  props: {
    tableHeader: {
      type: Array as () => Array<IHeaderConfiguration>,
      required: true,
    },
    table: {
      type: Object,
      required: true,
    },
    emptyTableText: { type: String, default: "Не найдено" },
    loading: { type: Boolean, default: false },
    enableItemsPerPageDropdown: { type: Boolean, default: true },
    order: { type: String, default: "asc" },
    sortLabel: { type: String, default: "" },
  },
  components: {
    Paginate
  },
  setup(props, { emit }) {
    const currentSort = ref<string>("created_atdesc");
    const order = ref(props.order);
    const label = ref(props.sortLabel);

    const getItems = computed(() => {
      return props.table.data;
    });

    const currentPageChange = (val) => {
      emit("current-change", val);
    };

    const sort = (columnName, sortable) => {
      if (sortable === false) {
        return;
      }

      if (order.value === "asc") {
        order.value = "desc";
        emit("sort", { columnName: columnName, order: "desc" });
      } else {
        order.value = "asc";
        emit("sort", { columnName: columnName, order: "asc" });
      }

      currentSort.value = columnName + order.value;
    };

    const setItemsPerPage = (event) => {
      emit("items-per-page-change", parseInt(event.target.value));
    };

    return {
      currentPageChange,
      getItems,
      sort,
      currentSort,
      setItemsPerPage,
    };
  },
});
</script>