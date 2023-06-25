<template lang="pug">
div.nothing
  div.tips {{config.tips}}
  div.entry(v-if="config.entry" @tap="emit('entry', config)") {{config.entry}}
</template>

<script setup lang="ts">
// @ts-ignore
const props = defineProps({
  config:{
    type: Object,
    default: {
      tips: "暂无数据",
      entry: null,
    },
  }
})
// @ts-ignore
const emit = defineEmits(['entry'])
</script>

<style lang="scss">
.nothing {
  text-align: center;
  padding-top: 400rpx;
  background-image: url('../../static/images/nothing.png');
  background-position: center;
  background-repeat: no-repeat;
  background-size: auto 267rpx;
}

.tips {
  padding: 50rpx;
  font-size: 30rpx;
  color: #9e9e9e;
}

.entry {
  width: 280rpx;
  height: 80rpx;
  color: #333;
  font-size: 30rpx;
  text-align: center;
  line-height: 80rpx;
  border-radius: 99rpx;
  border: 1px solid #ccc;
  margin: auto;

  &:active {
    background: darken(#fff, 5%);
  }
}
</style>