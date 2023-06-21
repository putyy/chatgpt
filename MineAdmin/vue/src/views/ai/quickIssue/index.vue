<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <!-- CRUD 组件 -->
    <ma-crud :options="options" :columns="columns" ref="crudRef">
    </ma-crud>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import aiQuickIssue from '@/api/ai/aiQuickIssue'
import { Message } from '@arco-design/web-vue'
import tool from '@/utils/tool'
import * as common from '@/utils/common'

const crudRef = ref()




const options = reactive({
  id: 'by_ai_quick_issue',
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
  api: aiQuickIssue.getList,
  add: {
    show: true,
    api: aiQuickIssue.save,
    auth: ['ai:quickIssue:save']
  },
  edit: {
    show: true,
    api: aiQuickIssue.update,
    auth: ['ai:quickIssue:update']
  },
  delete: {
    show: true,
    api: aiQuickIssue.deletes,
    auth: ['ai:quickIssue:delete']
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
    title: "问题标题",
    dataIndex: "title",
    formType: "input",
    search: true,
    commonRules: {
      required: true,
      message: "请输入问题标题"
    }
  },
  {
    title: "问题描述",
    dataIndex: "content",
    formType: "input",
    search: true,
    commonRules: {
      required: true,
      message: "请输入问题描述"
    }
  },
  {
    title: "排序",
    dataIndex: "sort",
    formType: "input",
    commonRules: {
      required: true,
      message: "请输入"
    }
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
    addDisplay: false,
    editDisplay: false,
    showTime: true
  },
  {
    title: "删除时间",
    dataIndex: "deleted_at",
    formType: "date",
    addDisplay: false,
    editDisplay: false,
    hide: true,
    showTime: true
  }
])
</script>
<script> export default { name: 'ai:quickIssue' } </script>