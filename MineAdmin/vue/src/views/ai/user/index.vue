<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <!-- CRUD 组件 -->
    <ma-crud :options="options" :columns="columns" ref="crudRef">
      <template #nick_name="{ record }">
        <div>{{record.nick_name}}</div>
        <div>{{record.mobile}}</div>
      </template>
      <template #head_img="{ record }">
        <img :src="record.head_img" style="object-fit: cover"/>
      </template>
      <template #from_uid="{ record }">
        <sapn>{{record.from_uid ? record.parent_nick_name+'('+record.from_uid+')' : ''}}</sapn>
      </template>
      <template #balance="{ record }">
        <div>余额: {{record.balance}}</div>
        <div>总收入: {{record.balance_total}}</div>
      </template>
      <template #operationAfterExtend="{ record }">
        <a-link v-auth="['ai:user:open-vip']" @click="showOpenVip(record)">
          <icon-edit/>
          开通VIP
        </a-link>
        <a-link v-auth="['ai:user:lock']" @click="lock(record)">
          <icon-edit/>
          {{record.is_lock === 1 ? "锁定" : "解除锁定" }}
        </a-link>
      </template>
    </ma-crud>
    <open-vip ref="openVipRef" @success="openVipSuccess"/>
  </div>
</template>
<script setup>
import {ref, reactive, shallowRef} from 'vue'
import aiUser from '@/api/ai/aiUser'
import { Message } from '@arco-design/web-vue'
import ptUpload from '@/components/putyy/pt-upload.vue'
import ptScene from "@/config/pt-scene";
import ptConst from "@/config/pt-const";
import {uploadQiniuHandler} from "@/utils/pt-upload";
import OpenVip from './components/openVip.vue'
import user from "@/api/ai/aiUser"

const openVipRef = ref()

const crudRef = ref()

const options = reactive({
  id: 'by_ai_user',
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
  api: aiUser.getList,
  edit: {
    show: true,
    api: aiUser.update,
    auth: ['ai:user:update']
  },
  delete: {
    show: true,
    api: aiUser.deletes,
    auth: ['ai:user:delete']
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
    title: "Uid",
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
    title: "昵称",
    dataIndex: "nick_name",
    formType: "input",
    search: true,
    addDisplay: false,
    commonRules: {
      required: true,
      message: "请输入昵称"
    }
  },
  {
    addDisplay: false,
    title: "头像",
    dataIndex: "head_img",
    formType: 'component',
    component: shallowRef(ptUpload),
    accept: 'image/*',
    ptScene: ptScene.ai_head_img
  },
  {
    title: "手机号",
    dataIndex: "mobile",
    formType: "input",
    hide: true,
    search: true,
    addDisplay: false,
    commonRules: {
      required: true,
      message: "请输入手机号"
    }
  },
  {
    title: "账户",
    dataIndex: "balance",
    formType: "input",
    search: false,
    addDisplay: false,
    editDisplay: false,
    commonRules: {
      required: true,
      message: "请输入手机号"
    }
  },
  {
    title: "总收入",
    dataIndex: "balance_total",
    formType: "input",
    hide: true,
    search: false,
    addDisplay: false,
    editDisplay: false,
    commonRules: {
      required: true,
      message: "请输入手机号"
    }
  },
  {
    title: "上级",
    dataIndex: "from_uid",
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
    title: "vip等级",
    dataIndex: "vip",
    formType: "select",
    search: true,
    addDisplay: false,
    disabled: true,
    commonRules: {
      required: true,
      message: "vip等级"
    },
    dict: {
      data: ptConst.ai_vip,
      translation: true
    },
  },
  {
    title: "vip到期时间",
    dataIndex: "vip_ent_at",
    formType: "date",
    search: true,
    addDisplay: false,
    showTime: true
  },
  {
    title: "是否锁定",
    dataIndex: "is_lock",
    formType: "select",
    search: true,
    addDisplay: false,
    editDisplay: false,
    commonRules: {
      required: true,
      message: "是否锁定"
    },
    dict: {
      data:ptConst.is_lock,
      translation: true
    },
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

const showOpenVip = (record)=>{
  openVipRef.value.open(record)
}

const openVipSuccess = (res)=>{
  res && crudRef.value.refresh()
}

const lock = (record)=>{
  user.lock(record.id).then((res)=>{
    Message.success('操作成功')
    crudRef.value.refresh()
  })
}
</script>
<script> export default { name: 'ai:user' } </script>