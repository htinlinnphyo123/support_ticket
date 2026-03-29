<template>
  <fwb-table hoverable striped>
    <fwb-table-head>
      <fwb-table-head-cell v-for="(col, index) in columns" :key="index">
        {{ col.label }}
      </fwb-table-head-cell>
      <fwb-table-head-cell v-if="$slots.actions">
        <span class="sr-only">Actions</span>
      </fwb-table-head-cell>
    </fwb-table-head>
    
    <fwb-table-body>
      <fwb-table-row v-for="(row, rowIndex) in rows" :key="rowIndex">
        <fwb-table-cell v-for="(col, colIndex) in columns" :key="colIndex">
          <slot :name="`cell-${col.key}`" :row="row" :value="resolveValue(row, col.key)">
            {{ resolveValue(row, col.key) }}
          </slot>
        </fwb-table-cell>
        
        <fwb-table-cell v-if="$slots.actions">
          <slot name="actions" :row="row" />
        </fwb-table-cell>
      </fwb-table-row>
    </fwb-table-body>
  </fwb-table>
</template>

<script setup>
import {
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'

defineProps({
    columns: {
        type: Array,
        required: true,
    },
    rows: {
        type: Array,
        required: true,
    }
})

const resolveValue = (row, key) => {
    return key.split('.').reduce((o, i) => (o ? o[i] : null), row);
};
</script>