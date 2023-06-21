<!--底部导航-->
<template lang="pug">
div.footer
  div.item(
    :class="{active: footerIndex === 0}"
    @click="operationFooter(0)"
  )
    span.icon
      uni-icons( type="chatbubble" size="35" :color="footerIndex === 0 ? '#55aaef' : '#ccc'")
    span.text Ai问答
  div.item(
    :class="{active: footerIndex === 1}"
    @click="operationFooter(1)"
  )
    span.icon
      uni-icons( type="medal" size="35" :color="footerIndex === 1 ? '#55aaef' : '#ccc'")
    span.text 公开频道
  div.item(
    :class="{active: footerIndex === 2}"
    @click="operationFooter(2)"
  )
    span.icon
      uni-icons( type="home" size="35" :color="footerIndex === 2 ? '#55aaef' : '#ccc'")
    span.text 我的
</template>

<script setup>
import {byRedirectTo} from "../common/utils/jump"

const props = defineProps({
  footerIndex: {
    type: Number,
    required: false,
    default: 0
  }, // 选择的角标
  fromUid: {
    type: Number,
    required: false,
    default: 0
  } // 分享人的uid
})

/**
 * 底部按钮跳转
 * @param index {Number} 选择的角标
 */
const operationFooter = (index) => {
  if (props.footerIndex === index) {
    return
  }
  switch (index) {
    case 0:
      byRedirectTo("/pages/chatgpt/room")
      break
    case 1:
      byRedirectTo("/pages/chatgpt/channel")
      break
    case 2:
      byRedirectTo("/pages/user/mine")
      break
  }
}

</script>

<style scoped lang="scss">
.footer {
  display: flex;
  flex-direction: row;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 2;
  width: 100%;
  border-top: 1rpx solid #e8e8e8;
  background-color: #fff;
  justify-content: space-around;
  text-align: center;
}

.item {
  display: flex;
  flex-direction: column;
}

.text {
  font-size: 24rpx;
  transform: scale(.83);
  transform-origin: center;
  color: #ccc;
}

.active .text {
  color: $theme-color !important;
}
</style>
