<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <!-- CRUD 组件 -->
    <ma-crud :options="options" :columns="columns" ref="crudRef">
    </ma-crud>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import aiChatSession from '@/api/ai/aiChatSession'
import { Message } from '@arco-design/web-vue'
import tool from '@/utils/tool'
import * as common from '@/utils/common'

const crudRef = ref()




const options = reactive({
  id: 'by_ai_chat_session',
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
  api: aiChatSession.getList,
  delete: {
    show: true,
    api: aiChatSession.deletes,
    auth: ['ai:chatSession:delete']
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
    sortable: {
      sortDirections: [
        "ascend",
        "descend"
      ],
      sorter: true
    }
  },
  {
    title: "用户uid",
    dataIndex: "uid",
    formType: "input",
    search: true,
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "模型ID",
    dataIndex: "prompt_id",
    formType: "input",
    search: true,
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "是否关闭",
    dataIndex: "share",
    formType: "select",
    search: true,
    addDisplay: false,
    editDisplay: false,
    dict: {
      data: [
        {
          label: "正常",
          value: 1
        }, {
          label: "关闭",
          value: 2
        }
      ],
      translation: true
    },
  },
  {
    title: "是否分享",
    dataIndex: "share",
    formType: "select",
    search: true,
    addDisplay: false,
    editDisplay: false,
    dict: {
      data: [
        {
          label: "关闭",
          value: 1
        }, {
          label: "公开",
          value: 2
        }
      ],
      translation: true
    },
  },
  {
    title: "创建时间",
    dataIndex: "created_at",
    formType: "date",
    addDisplay: false,
    editDisplay: false,
    hide: true,
    showTime: true
  }
])
</script>
<script> export default { name: 'ai:chatSession' } </script>