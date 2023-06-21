<template lang="pug">
uni-popup(ref="popupObj" type="bottom")
  scroll-view.agreement_container(scroll-y="true")
    view.title {{agreementData.title}}
    view.content {{agreementData.content}}
    div.close(@click="emit('close')")
      button.btn 关闭
</template>

<script setup lang="ts">
// @ts-ignore
import {computed, ref, watch} from "vue"
import {useIndexStore} from '../store'
// @ts-ignore
const props = defineProps(["isShow", "scene"])
// @ts-ignore
const emit = defineEmits(['close'])

const popupObj = ref()
const agreementData = ref({title:'', content: '', scene: 0})

const isShowCp = computed(() => {
  return props.isShow
})

watch(isShowCp, (v: ptAny)=>{
  if (v){
    if (agreementData.value.scene !== props.scene) {
      // todo 根据场景值获取协议
      agreementData.value.scene = props.scene
      if (props.scene === 1000 ) {
        agreementData.value.title = "用户协议"
        agreementData.value.content = useIndexStore().scene.agreement.user
      }
    }
    popupObj.value.open()
  }else{
    popupObj.value.close()
  }
})
</script>

<style scoped lang="scss">
.agreement_container{
  background-color: #ffffff;
  height: 100vh;
  .title{
    text-align: center;
    padding: 15rpx;
    font-weight: bold;
    font-size: 36rpx;
  }
  .content{
    white-space: pre-wrap;
    padding: 15rpx;
  }
  .close{
    padding-bottom: 20rpx;
  }
  .btn{
    width: 170rpx;
    font-size: 30rpx;
    color: #fff;
    background-color: $theme-color;
  }
}
</style>
