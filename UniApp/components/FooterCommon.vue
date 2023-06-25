<template lang="pug">
div.footer
  div.item(v-for="(item, key) in footer"
    :class="{active: footerIndex === key}"
    @click="operationFooter(key)"
  )
    span.icon
      uni-icons( :type="item.icon" size="35" :color="footerIndex === key ? '#55aaef' : '#ccc'")
    span.text {{item.name}}
</template>

<script setup>
// @ts-ignore
import {ref} from "vue"
import {byRedirectTo} from "../common/utils/jump"

const props = defineProps({
  footerIndex: {
    type: Number,
    required: false,
    default: 0
  }
})

const footer = ref([
  {
    icon: 'chatbubble',
    name: "Ai问答"
  },
  {
    icon: 'medal',
    name: "公开频道"
  },
  {
    icon: 'home',
    name: "我的"
  },
])

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
