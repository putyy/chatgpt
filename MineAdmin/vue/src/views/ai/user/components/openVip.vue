<template>
  <a-modal v-model:visible="visible" :footer="false" draggable width="600px">
    <template #title>开通VIP</template>

    <ma-form v-model="form" v-model:columns="columns" @onSubmit="submit" ref="maformRef">
      <template #form-vip>
        <a-select
            style="width: 100%"
            v-model="form['vip']"
            allow-clear
            allow-search
            placeholder="请选择vip等级"
        >
          <a-option
              class="w-full"
              v-for="(item, index) in ptConst.ai_vip"
              :label="item.label"
              :value="item.value"
              :key="index"
          >
          </a-option>
        </a-select>
      </template>
    </ma-form>
  </a-modal>
</template>

<script setup>
import {reactive, ref} from 'vue'
import ptConst from "@/config/pt-const"
import ai from "@/api/ai/aiUser"
import {Message} from "@arco-design/web-vue"

const form = ref({
  id: 0,
  vip: 0,
  price: 0,
  remark: '',
})

const maformRef = ref()
const visible = ref(false)
const emit = defineEmits(['success'])

const open = (value) => {
  form.value.id = value.id
  form.value.vip = value.vip
  form.value.price = 0
  form.value.remark = ''
  visible.value = true
}

const submit = async (data) => {
  if (data) {
    let response = await ai.openVip(data.id, data)
    if (response.success) {
      Message.success('开通成功')
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
    dataIndex: 'id',
    disabled: true
  },
  {
    title: "vip等级",
    dataIndex: "vip",
    formType: "select",
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
    title: "金额",
    dataIndex: "price",
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
