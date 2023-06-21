<template>
  <a-modal v-model:visible="visible" :footer="false" draggable width="600px">
    <template #title>生成卡密</template>
    <ma-form v-model="form" v-model:columns="columns" @onSubmit="submit" ref="maformRef">
    </ma-form>
  </a-modal>
</template>

<script setup>
import {reactive, ref} from 'vue'
import ptConst from "@/config/pt-const";
import ai from "@/api/ai/aiPayKami";
import {Message} from "@arco-design/web-vue";

const form = ref({
  uid: 0,
  price: 0,
  remark: '',
  number: 1,
})

const maformRef = ref()
const visible = ref(false)
const emit = defineEmits(['success'])

const open = () => {
  visible.value = true
}

const submit = async (data) => {
  if (data) {
    let response = await ai.add(data)
    if (response.success) {
      Message.success('创建成功')
      emit('success', true)
      visible.value = false
    } else {
      emit('success', false)
    }
  }
}

const columns = reactive([
  {
    title: '用户UID',
    dataIndex: 'uid',
    formType: "input"
  },
  {
    title: "金额",
    dataIndex: "price",
    formType: "input"
  }, {
    title: "数量",
    dataIndex: "number",
    formType: "input"
  },
  {
    title: '备注',
    dataIndex: 'remark',
    formType: 'textarea'
  }
])

defineExpose({open})
</script>
