<template>
  <div class="ma-content-block lg:flex justify-between p-4">
    <ma-form v-model="form" v-model:columns="columns" @onSubmit="submit" ref="maformRef">
    </ma-form>
  </div>
</template>

<script setup>
import {onMounted, reactive, ref, shallowRef, watch} from 'vue'
import {Message} from "@arco-design/web-vue"
import ptUpload from '@/components/putyy/pt-upload.vue'
import ptScene from "@/config/pt-scene"
import {uploadQiniuHandler} from "@/utils/pt-upload"
import ai from "@/api/ai/aiSetting"
import { useFormStore } from '@/store/index'

const formStore = useFormStore()

const form = ref({
  openai_proxy: '',
  app_close_message: '',
  agreement_user: '',
  head_img: '',
  mobile: '',
  user_name: '',
  work_time: '',
  wx_img_url: '',
  wx_no: '',
})

watch(
    () => formStore.crudList['ai_setting_index'],
    async vl => {
      vl === true && await  ai.getList().then((res) => {
        form.value = Object.assign({}, form.value, res.data)
      })
      formStore.crudList['ai_setting_index'] = false
    }
)

const maformRef = ref()

const emit = defineEmits(['success'])

const columns = reactive([
  {
    title: "openai api地址",
    dataIndex: "openai_proxy",
    formType: "input",
    placeholder: "默认: https://api.openai.com",
  },{
    title: "关站信息",
    dataIndex: "app_close_message",
    formType: "textarea",
    placeholder: "不为空则为关站中",
  }, {
    title: '用户协议',
    dataIndex: 'agreement_user',
    formType: 'textarea'
  }, {
    title: '客服手机',
    dataIndex: 'mobile',
    formType: 'input'
  }, {
    title: '客服名字',
    dataIndex: 'user_name',
    formType: 'input'
  }, {
    title: '客服工作时间',
    dataIndex: 'work_time',
    formType: 'input'
  }, {
    title: '客服微信号',
    dataIndex: 'wx_no',
    formType: 'input'
  }, {
    addDisplay: false,
    title: "客服头像",
    dataIndex: "head_img",
    formType: 'component',
    component: shallowRef(ptUpload),
    accept: 'image/*',
    ptScene: ptScene.ai_customer_head_img
  },
  {
    addDisplay: false,
    title: "客服微信二维码",
    dataIndex: "wx_img_url",
    formType: 'component',
    component: shallowRef(ptUpload),
    accept: 'image/*',
    ptScene: ptScene.ai_customer_wx_img
  },
])

onMounted(() => {
  ai.getList().then((res) => {
    form.value = Object.assign({}, form.value, res.data)
  })
})

const submit = async (form) => {
  if (form.hasOwnProperty('ptFileList')) {
    let files = await uploadQiniuHandler(form.ptFileList)
    for (let dataIndex in files) {
      if (form.hasOwnProperty(dataIndex)) {
        form[dataIndex] = files[dataIndex].source_url
      }
    }
    delete form.ptFileList
  }
  ai.save({
    app_close_message: form.app_close_message,
    agreement_user: form.agreement_user,
    openai_proxy: form.openai_proxy,
    customer: {
      head_img: form.head_img,
      mobile: form.mobile,
      user_name: form.user_name,
      work_time: form.work_time,
      wx_img_url: form.wx_img_url,
      wx_no: form.wx_no,
    },
  }).then((res)=>{
    Message.success('设置成功')
  })
}
</script>