<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <!-- CRUD 组件 -->
    <ma-crud :options="options" :columns="columns" ref="crudRef">
      <template #icon="{ record }">
        <img :src="record.icon" style="object-fit: cover"/>
      </template>
    </ma-crud>
  </div>
</template>
<script setup>
import {ref, reactive, shallowRef, onMounted, onBeforeMount} from 'vue'
import aiMineMenu from '@/api/ai/aiMineMenu'
import { Message } from '@arco-design/web-vue'
import tool from '@/utils/tool'
import * as common from '@/utils/common'
import ptUpload from '@/components/putyy/pt-upload.vue'
import ptScene from "@/config/pt-scene";
import ptConst from "@/config/pt-const";
import aiMineMenuGroup from "@/api/ai/aiMineMenuGroup";
import {uploadQiniuHandler} from "@/utils/pt-upload";

const crudRef = ref()

const options = reactive({
  id: 'by_ai_mine_menu',
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
  api: aiMineMenu.getList,
  add: {
    show: true,
    api: aiMineMenu.save,
    auth: ['ai:mineMenu:save']
  },
  edit: {
    show: true,
    api: aiMineMenu.update,
    auth: ['ai:mineMenu:update']
  },
  delete: {
    show: true,
    api: aiMineMenu.deletes,
    auth: ['ai:mineMenu:delete']
  },

  beforeAdd: async (form) => {
    let files = await uploadQiniuHandler(form.ptFileList)
    if (files === true) {
      return
    }
    for (let dataIndex in files) {
      if (form.hasOwnProperty(dataIndex)) {
        form[dataIndex] = files[dataIndex].source_url
      }
    }
    delete form.ptFileList
  },
  beforeEdit: async (form) => {
    let files = await uploadQiniuHandler(form.ptFileList)
    if (files === true) {
      return
    }
    for (let dataIndex in files) {
      if (form.hasOwnProperty(dataIndex)) {
        form[dataIndex] = files[dataIndex].source_url
      }
    }
    delete form.ptFileList
  },
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
    title: "分组",
    dataIndex: "gid",
    formType: "select",
    dict: {
      props:  { label: 'name', value: 'id' },
      data: async () => {
        let res = await aiMineMenuGroup.getList({pageSize: 999})
        return res.data.items
      },
      translation: true
    }
  },
  {
    title: "菜单名称",
    dataIndex: "name",
    formType: "input",
    search: true
  },
  {
    title: "排序",
    dataIndex: "sort",
    formType: "input",
    sortable: {
      sortDirections: [
        "ascend",
        "descend"
      ],
      sorter: true
    }
  },
  {
    title: "使用权限限制 0全部",
    dataIndex: "use_vip",
    formType: "select",
    dict: {
      data: ptConst.ai_vip,
      translation: true
    },
  },
  {
    title: "点击类型 1跳转 2调用函数",
    dataIndex: "click_type",
    formType: "select",
    dict: {
      data: [
        {
          label: "跳转",
          value: 1
        }, {
          label: "调用函数",
          value: 2
        },
      ],
      translation: true
    },
  },
  {
    title: "函数标识 小程序端提前封装",
    dataIndex: "click_func",
    formType: "input"
  },
  {
    title: "打开的页面路径",
    dataIndex: "path",
    formType: "input"
  },
  {
    title: "小程序appid",
    dataIndex: "app_id",
    formType: "input"
  },
  {
    title: "需要传递给目标小程序的数据 json",
    dataIndex: "extra_data",
    formType: "input"
  },
  {
    title: "要打开的小程序版本",
    dataIndex: "env_version",
    formType: "input"
  },
  {
    title: "小程序链接",
    dataIndex: "short_link",
    formType: "input"
  },
  {
    title: "图标",
    dataIndex: "icon",
    formType: 'component',
    component: shallowRef(ptUpload),
    accept: 'image/*',
    ptScene: ptScene.ai_mine_menu_icon
  },
  {
    title: "是否锁定",
    dataIndex: "is_lock",
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
<script> export default { name: 'ai:mineMenu' } </script>