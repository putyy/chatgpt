<template lang="pug">
uni-popup(ref="popupObj" :mask-click="false")
  div.code
    div.code_content
      div.code_middle
        p.nickname(v-if="codeData.nickname")
          span.nickname_text {{codeData.nickname}}
        p.text(v-if="codeData.text") {{codeData.text}}
        img.img(:src="codeData.codeSrc")
        p.prompt(v-if="codeData.promptText") {{codeData.promptText}}
        p.hint(v-if="codeData.hint") {{codeData.hint}}
    div.code_following(@click="emit('close')")
        uni-icons(type="close" size="35")
</template>

<script setup lang="ts">
// @ts-ignore
import {computed, ref, watch} from "vue"
// @ts-ignore
const props = defineProps(["codeData", "isShow"]);
// @ts-ignore
const emit = defineEmits(['close'])

const popupObj = ref()

const isShowCp = computed(() => {
  return props.isShow
})

watch(isShowCp, (v: ptAny) => {
  if (v) {
    popupObj.value.open()
  } else {
    popupObj.value.close()
  }
})
</script>

<style scoped lang="scss">
  .code {
    width: 570rpx;

    &_content {
      width: 570rpx;
    }

    .image-bg{
      position: absolute;
      z-index: -1;
      left: 0;
      right: 0;
      bottom: 0;
      width: 100%;
      height: 100%;
    }

    &_middle {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40rpx;
      border-radius:32rpx 32rpx;
      background-color: $uni-text-color-inverse;

      .nickname {
        color: $uni-text-color;
        font-size: 34rpx;
        //.ellipsisLn(1);

        &_text {
          font-weight: bold;
        }
      }

      .text {
        margin-top: 14rpx;
        color: $uni-color-primary;
        font-size: 32rpx;
      }

      .img {
        width: 222rpx;
        height: 222rpx;
        margin: 20rpx 0;
        border: 10rpx solid $uni-text-color-inverse;
      }

      .prompt {
        color: $uni-text-color;
        font-size: 30rpx;
      }

      .hint {
        width: 490rpx;
        height: 62rpx;
        margin-top: 30rpx;
        background: #f3f3f3;
        border-radius: 80rpx;
        text-align: center;
        font-size: 22rpx;
        color: $uni-text-color;
        line-height: 62rpx;
      }
    }

    &_following {
      width: 80rpx;
      height: 80rpx;
      margin: 60rpx auto 0 auto;
    }
  }
</style>
