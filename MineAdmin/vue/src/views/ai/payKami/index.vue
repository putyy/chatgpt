<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <!-- CRUD 组件 -->
    <ma-crud :options="options" :columns="columns" ref="crudRef">
      <template #tableAfterButtons="{ record }">
        <a-button class="w-full lg:w-auto mt-2 lg:mt-0" type="primary" @click="addRef.open()" ><icon-plus />&nbsp;&nbsp;新增</a-button>
      </template>
    </ma-crud>
    <add ref="addRef" @success="addSuccess"/>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import aiPayKami from '@/api/ai/aiPayKami'
import { Message } from '@arco-design/web-vue'
import tool from '@/utils/tool'
import * as common from '@/utils/common'
import Add from './components/Add.vue'

const crudRef = ref()
const addRef = ref()


const options = reactive({
  id: 'by_ai_pay_kami',
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
  api: aiPayKami.getList,
  edit: {
    show: true,
    api: aiPayKami.update,
    auth: ['ai:payKami:update']
  },
  delete: {
    show: true,
    api: aiPayKami.deletes,
    auth: ['ai:payKami:delete']
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
    }
  },
  {
    title: "绑定用户",
    dataIndex: "uid",
    formType: "input",
    search: true
  },
  {
    title: "价格",
    dataIndex: "price",
    formType: "input"
  },
  {
    title: "卡密号",
    dataIndex: "code",
    formType: "input",
    search: true,
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "状态",
    dataIndex: "status",
    formType: "select",
    search: true,
    addDisplay: false,
    editDisplay: false,
    dict: {
      data: [
        {
          label: "待使用",
          value: 1
        }, {
          label: "已使用",
          value: 2
        }
      ],
      translation: true
    },
  },
  {
    title: "备注",
    dataIndex: "remark",
    formType: "input",
    search: true,
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "创建者",
    dataIndex: "created_by",
    formType: "input",
    addDisplay: false,
    editDisplay: false
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
    title: "使用时间",
    dataIndex: "use_at",
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

const addSuccess = (res)=>{
  res && crudRef.value.refresh()
}
</script>
<script> export default { name: 'ai:payKami' } </script>