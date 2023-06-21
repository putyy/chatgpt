<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <!-- CRUD 组件 -->
    <ma-crud :options="options" :columns="columns" ref="crudRef">
    </ma-crud>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import aiMineMenuGroup from '@/api/ai/aiMineMenuGroup'
import { Message } from '@arco-design/web-vue'
import tool from '@/utils/tool'
import * as common from '@/utils/common'
import ptConst from "@/config/pt-const";

const crudRef = ref()




const options = reactive({
  id: 'by_ai_mine_menu_group',
  rowSelection: {
    showCheckedAll: true
  },
  pk: 'id',
  operationColumn: true,
  operationWidth: 160,
  formOption: {
    viewType: 'modal',
    width: 600
  },
  api: aiMineMenuGroup.getList,
  add: {
    show: true,
    api: aiMineMenuGroup.save,
    auth: ['ai:mineMenuGroup:save']
  },
  edit: {
    show: true,
    api: aiMineMenuGroup.update,
    auth: ['ai:mineMenuGroup:update']
  },
  delete: {
    show: true,
    api: aiMineMenuGroup.deletes,
    auth: ['ai:mineMenuGroup:delete']
  }
})

const columns = reactive([
  {
    title: "主键",
    dataIndex: "id",
    formType: "input",
    addDisplay: false,
    editDisplay: false,
    hide: true,
    commonRules: {
      required: true,
      message: "请输入主键"
    }
  },
  {
    title: "分组名称",
    dataIndex: "name",
    formType: "input",
    search: true
  },
  {
    title: "排序",
    dataIndex: "sort",
    formType: "input",
    search: true
  },
  {
    title: "是否锁定",
    dataIndex: "is_lock",
    addDisplay: false,
    formType: "select",
    search: true,
    dict: {
      data: ptConst.is_lock,
      translation: true
    },
  },
  {
    title: "创建时间",
    dataIndex: "created_at",
    formType: "date",
    search: true,
    addDisplay: false,
    editDisplay: false,
    showTime: true
  },
  {
    title: "更新时间",
    dataIndex: "updated_at",
    formType: "date",
    search: true,
    addDisplay: false,
    editDisplay: false,
    showTime: true
  }
])
</script>
<script> export default { name: 'ai:mineMenuGroup' } </script>