<template lang="pug">
view {{content}}
</template>

<script setup lang="ts">
// @ts-ignore
import {ref, onMounted} from 'vue'
import {useIndexStore} from '../store/index'
import {AppNoticeCacheKey} from "../common/const"

const store = useIndexStore()
const content = ref([]) // 内容

onMounted(() => {
  // @ts-ignore
  if (uni.getStorageSync(AppNoticeCacheKey)) {
    // @ts-ignore
    content.value = uni.getStorageSync(AppNoticeCacheKey).message
  }
})
</script>

<style scoped>

</style>
