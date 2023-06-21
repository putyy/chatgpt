<template>
  <a-modal v-model:visible="visible" :footer="false" draggable width="600px">
    <template #title>批量添加openai key</template>
    <ma-form v-model="form" v-model:columns="columns" @onSubmit="submit" ref="maformRef">
    </ma-form>
  </a-modal>
</template>

<script setup>
import {reactive, ref} from 'vue'
import aiOpenaiKey from '@/api/ai/aiOpenaiKey'
import {Message} from "@arco-design/web-vue";

const form = ref({
  content: '',
  remark: ''
})

const maformRef = ref()
const visible = ref(false)
const emit = defineEmits(['success'])

const open = () => {
  visible.value = true
}

const submit = async (data) => {
  if (data) {
    let response = await aiOpenaiKey.batchAdd(data)
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
    title: '卡密内容',
    dataIndex: 'content',
    formType: 'textarea'
  }, {
    title: '备注',
    dataIndex: 'remark',
    formType: 'textarea'
  }
])

defineExpose({open})
</script>
