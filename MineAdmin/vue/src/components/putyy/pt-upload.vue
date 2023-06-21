<template>
  <a-space direction="vertical" :style="{ width: '100%' }">
    <a-upload v-if="uploadType === 'image'" :custom-request="customRequest" :accept="props.component.accept" @before-remove="beforeRemove" :limit="1" :file-list="ptFileList" list-type="picture-card" image-preview/>
    <a-upload v-else-if="uploadType === 'audio'" :custom-request="customRequest" :accept="props.component.accept" @before-remove="beforeRemove" :file-list="ptFileList" :limit="1" :show-cancel-button="false">

    </a-upload>

    <a-upload v-else-if="uploadType === 'video'" :custom-request="customRequest" :accept="props.component.accept" @before-remove="beforeRemove" :file-list="ptFileList" :limit="1" :show-cancel-button="false">

    </a-upload>
  </a-space>
</template>

<script setup>
import {ref, inject, onMounted, watch} from 'vue'
import {Message} from '@arco-design/web-vue'

const props = defineProps({
  component: Object,
  customField: { type: String, default: undefined }
})

const formModel = inject('formModel')

// 当前字段名
let dataIndex = props.customField ?? props.component.dataIndex

const maxFileSize = props.component.hasOwnProperty('ptMaxFileSize') ? props.component['ptMaxFileSize'] : 0

let uploadType = props.component.accept.slice(0, 5)

let ptFileList = ref([])
let sourceUrl = ref('')

onMounted(()=>{
  // 追加fileList属性
  formModel.value.ptFileList = formModel.value.ptFileList ? formModel.value.ptFileList : []

  if (formModel.value[dataIndex]) {
    ptFileList.value = [{
      name: formModel.value[dataIndex],
      url: formModel.value[dataIndex]
    }]
    sourceUrl.value = formModel.value[dataIndex]
  }
})

watch(formModel, newValue => {
  ptFileList.value = [{
    name: formModel.value[dataIndex],
    url: formModel.value[dataIndex]
  }]
  sourceUrl.value = formModel.value[dataIndex]
})


const duration = ref(0)

let customRequest = option => {
  if (!props.component.ptScene) {
    Message.error('请填入场景值')
  }
  if (maxFileSize && option.fileItem.file.size > maxFileSize) {
    Message.error('文件大小不能超过' + (maxFileSize / 1024 / 1024) + 'M')
    return
  }

  formModel.value.ptFileList[dataIndex] = {
    file: option.fileItem.file,
    url: window.URL.createObjectURL(option.fileItem.file),
    name: dataIndex,
    dataIndex: dataIndex,
    size: option.fileItem.file.size,
    duration: 0,
    type: 'image',
    ptScene: props.component.ptScene
  }

  ptFileList.value = [{
    name: dataIndex,
    url: formModel.value.ptFileList[dataIndex].url
  }]
  sourceUrl.value = formModel.value.ptFileList[dataIndex].url
  if (uploadType.value !== 'image') {
    let videoElement = new Audio(formModel.value.ptFileList[dataIndex].url)
    videoElement.addEventListener('loadedmetadata', event => {
      duration.value = videoElement.duration
    })
  }
}

let beforeRemove = file => {
  formModel.value.ptFileList[dataIndex] = []
  ptFileList.value = []
  sourceUrl.value = ''
}
</script>

<style scoped lang="less">
</style>
