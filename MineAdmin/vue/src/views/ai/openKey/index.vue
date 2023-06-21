<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <!-- CRUD 组件 -->
    <ma-crud :options="options" :columns="columns" ref="crudRef">
      <template #tableAfterButtons="{ record }">
        <a-button class="w-full lg:w-auto mt-2 lg:mt-0" type="primary" @click="batchAdd" >批量添加</a-button>
        <a-button class="w-full lg:w-auto mt-2 lg:mt-0" type="primary" @click="refreshCache" >刷新缓存</a-button>
      </template>
    </ma-crud>
    <add ref="addRef" @success="addSuccess"/>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import aiOpenaiKey from '@/api/ai/aiOpenaiKey'
import { Message } from '@arco-design/web-vue'
import Add from './components/Add.vue'

const crudRef = ref()

const addRef = ref()

const options = reactive({
  id: 'by_ai_openai_key',
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
  api: aiOpenaiKey.getList,
  add: {
    show: true,
    api: aiOpenaiKey.save,
    auth: ['ai:openKey:save']
  },
  edit: {
    show: true,
    api: aiOpenaiKey.update,
    auth: ['ai:openKey:update']
  },
  delete: {
    show: true,
    api: aiOpenaiKey.deletes,
    auth: ['ai:openKey:delete']
  }
})

const columns = reactive([
  {
    title: "主键",
    dataIndex: "id",
    formType: "input",
    search: true,
    addDisplay: false,
    editDisplay: false,
    commonRules: {
      required: true,
      message: "请输入主键"
    },
    sortable: {
      sortDirections: [
        "ascend",
        "descend"
      ],
      sorter: true
    }
  },
  {
    title: "openai_key",
    dataIndex: "openai_key",
    formType: "input",
    search: true
  },
  {
    title: "备注",
    dataIndex: "remark",
    formType: "input",
    search: true
  },
  {
    title: "创建者",
    dataIndex: "created_by",
    formType: "input",
    addDisplay: false,
    editDisplay: false,
    hide: true
  },
  {
    title: "更新者",
    dataIndex: "updated_by",
    formType: "input",
    addDisplay: false,
    editDisplay: false,
    hide: true
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

const batchAdd = ()=>{
  addRef.value.open()
}

const refreshCache = ()=>{
  aiOpenaiKey.refreshCache().then((res)=>{
    Message.success('操作成功')
  })
}

const addSuccess = (res)=>{
  res && crudRef.value.refresh()
}
</script>
<script> export default { name: 'ai:openKey' } </script>