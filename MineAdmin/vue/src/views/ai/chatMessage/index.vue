<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <!-- CRUD 组件 -->
    <ma-crud :options="options" :columns="columns" ref="crudRef">
    </ma-crud>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import aiChatMessage from '@/api/ai/aiChatMessage'
import { Message } from '@arco-design/web-vue'
import tool from '@/utils/tool'
import * as common from '@/utils/common'

const crudRef = ref()

const options = reactive({
  id: 'by_ai_chat_message',
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
  api: aiChatMessage.getList,
  delete: {
    show: true,
    api: aiChatMessage.deletes,
    auth: ['ai:chatMessage:delete']
  }
})

const columns = reactive([
  {
    title: "主键",
    dataIndex: "id",
    formType: "input",
    addDisplay: false,
    editDisplay: false,
    commonRules: {
      required: true,
      message: "请输入主键"
    }
  },
  {
    title: "会话ID",
    dataIndex: "sid",
    formType: "input",
    search: true,
    addDisplay: false,
    editDisplay: false,
    commonRules: {
      required: true,
      message: "请输入会话ID"
    }
  },
  {
    title: "内容",
    dataIndex: "content",
    formType: "editor",
    search: true,
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "回复内容",
    dataIndex: "reply_content",
    formType: "editor",
    search: true,
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "回复时间",
    dataIndex: "reply_at",
    formType: "date",
    search: true,
    addDisplay: false,
    editDisplay: false,
    showTime: true
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
<script> export default { name: 'ai:chatMessage' } </script>