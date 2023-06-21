<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <!-- CRUD 组件 -->
    <ma-crud :options="options" :columns="columns" ref="crudRef">
    </ma-crud>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import aiOrder from '@/api/ai/aiOrder'
import { Message } from '@arco-design/web-vue'
import tool from '@/utils/tool'
import * as common from '@/utils/common'
import ptConst from "@/config/pt-const";

const crudRef = ref()

const options = reactive({
  id: 'by_ai_order',
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
  api: aiOrder.getList,
  delete: {
    show: true,
    api: aiOrder.deletes,
    auth: ['ai:order:delete']
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
    title: "用户UID",
    dataIndex: "uid",
    formType: "input",
    search: true,
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "上级UID",
    dataIndex: "from_uid",
    formType: "input",
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "营销部UID",
    dataIndex: "market_uid",
    formType: "input",
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "订单号",
    dataIndex: "ord_sn",
    formType: "input",
    search: true,
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "订单类型",
    dataIndex: "ord_type",
    formType: "select",
    search: true,
    addDisplay: false,
    editDisplay: false,
    dict: {
      data: [
        {
          label: "开通VIP",
          value: 1
        }, {
          label: "提现",
          value: 2
        }, {
          label: "成为营销部",
          value: 3
        }
      ],
      translation: true
    },
  },
  {
    title: "支付方式",
    dataIndex: "pay_type",
    formType: "select",
    search: true,
    addDisplay: false,
    editDisplay: false,
    dict: {
      data: [
        {
          label: "微信",
          value: 1
        }, {
          label: "后台免费",
          value: 2
        }, {
          label: "后台付费",
          value: 3
        }, {
          label: "免费卡密",
          value: 4
        }, {
          label: "付费卡密",
          value: 5
        }
      ],
      translation: true
    },
  },
  {
    title: "订单状态",
    dataIndex: "status",
    formType: "select",
    search: true,
    addDisplay: false,
    editDisplay: false,
    dict: {
      data: [
        {
          label: "未支付(待处理)",
          value: 1
        }, {
          label: "已支付(已完成)",
          value: 2
        }, {
          label: "支付失败",
          value: 3
        }
      ],
      translation: true
    },
  },
  {
    title: "总金额",
    dataIndex: "total_price",
    formType: "input",
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "实际金额",
    dataIndex: "amount_price",
    formType: "input",
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "订单描述",
    dataIndex: "content",
    formType: "input",
    addDisplay: false,
    editDisplay: false
  },
  {
    title: "备注",
    dataIndex: "remark",
    formType: "input",
    addDisplay: false,
    editDisplay: false
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
    title: "订单完成时间",
    dataIndex: "pay_at",
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
</script>
<script> export default { name: 'ai:order' } </script>