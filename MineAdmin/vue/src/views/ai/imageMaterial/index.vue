<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <!-- CRUD 组件 -->
    <ma-crud :options="options" :columns="columns" ref="crudRef">
      <template #img_url="{ record }">
        <img :src="record.img_url" style="object-fit: cover"/>
      </template>
    </ma-crud>
  </div>
</template>
<script setup>
import {ref, reactive, shallowRef} from 'vue'
import aiImageMaterial from '@/api/ai/aiImageMaterial'
import { Message } from '@arco-design/web-vue'
import tool from '@/utils/tool'
import * as common from '@/utils/common'
import {uploadQiniuHandler} from "@/utils/pt-upload";
import ptScene from "@/config/pt-scene";
import ptUpload from '@/components/putyy/pt-upload.vue'

const crudRef = ref()

const options = reactive({
  id: 'by_ai_image_material',
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
  api: aiImageMaterial.getList,
  add: {
    show: true,
    api: aiImageMaterial.save,
    auth: ['ai:imageMaterial:save']
  },
  edit: {
    show: true,
    api: aiImageMaterial.update,
    auth: ['ai:imageMaterial:update']
  },
  delete: {
    show: true,
    api: aiImageMaterial.deletes,
    auth: ['ai:imageMaterial:delete']
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
    hide: true,
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
    title: "使用场景",
    dataIndex: "scene",
    formType: "select",
    search: true,
    dict: {
      data: [
        {
          label: "个人中心",
          value: 1
        },
      ],
      translation: true
    },
  },
  {
    title: "图片地址",
    dataIndex: "img_url",
    formType: 'component',
    component: shallowRef(ptUpload),
    accept: 'image/*',
    ptScene: ptScene.ai_image_materialg
  },
  {
    title: "跳转地址",
    dataIndex: "url",
    formType: "input",
    commonRules: {
      required: true,
      message: "请输入跳转地址"
    }
  },
  {
    title: "备注",
    dataIndex: "remark",
    formType: "input",
    search: true,
    hide: true,
    commonRules: {
      required: true,
      message: "请输入备注"
    }
  },
  {
    title: "排序",
    dataIndex: "sort",
    formType: "input",
    hide: false,
    commonRules: {
      required: true,
      message: "请输入排序"
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
    title: "创建者",
    dataIndex: "created_by",
    formType: "input",
    addDisplay: false,
    editDisplay: false,
    hide: true,
    commonRules: {
      required: true,
      message: "请输入创建者"
    }
  },
  {
    title: "更新者",
    dataIndex: "updated_by",
    formType: "input",
    addDisplay: false,
    editDisplay: false,
    hide: true,
    commonRules: {
      required: true,
      message: "请输入更新者"
    }
  },
  {
    title: "使用开始时间",
    dataIndex: "start_at",
    formType: "date",
    search: true,
    commonRules: {
      required: true,
      message: "请输入使用开始时间"
    },
    showTime: true
  },
  {
    title: "使用结束时间",
    dataIndex: "end_at",
    formType: "date",
    search: true,
    commonRules: {
      required: true,
      message: "请输入使用结束时间"
    },
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
    title: "更新时间",
    dataIndex: "updated_at",
    formType: "date",
    addDisplay: false,
    editDisplay: false,
    hide: true,
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
<script> export default { name: 'ai:imageMaterial' } </script>