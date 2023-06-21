<template lang="pug">
div.container
  div.search_box
    div.top(v-if="props.columns.length >= 0")
      template(v-for="(item, index) in props.columns" :key="index")
        div.keyword(v-if="item.index === 'keyword'")
          div.content
            input.input(placeholder-class="remind" type="text" placeholder="搜索内容" v-model="searchForm[item.index]" confirm-type="search")
            uni-icons.icon_search(type="search" size="30" color="#4d86b5" @tap="submit")
      div.choose_where(v-if="props.columns.length >= 1" @click="searchFormPopupOb.open()")
        uni-icons(type="settings" size="25" color="#fff")
        span 筛选
  slot(:result="resultList")
  nothing(v-if="!resultList.length")
  uni-popup(ref="searchFormPopupOb" type="bottom")
    div.search_form
      template(v-for="(item, index) in props.columns" :key="index")
        template(v-if="item.formType === 'time'" )
          uni-section(:title="item.title" titleFontSize="36rpx")
          div.select_time
            uni-datetime-picker(v-model="searchForm[item.index]" type="daterange" rangeSeparator="至")
        template(v-if="item.formType === 'select'" )
          uni-section(:title="item.title" titleFontSize="36rpx")
          uni-data-checkbox.select_vip(v-model="searchForm[item.index]" :localdata="item.data" selectedColor="#fa8806" selectedTextColor="#fa8806")
      uni-section.select_action(title="")
        button.btn(type="primary" size="mini" @click="searchFormPopupOb.close()") 取消
        button.btn(type="primary" size="mini" @click="searchFormPopupOb.close() & submit()") 确定
</template>

<script setup lang="ts">
// @ts-ignore
import {onMounted, ref} from "vue"
import Nothing from "../components/Nothing.vue"
// @ts-ignore
const props = defineProps({
  // 组件设置
  options: {
    type: Object
  },
  // 字段列表
  columns: {type: Array, default: []}
})

const options = ref({
  api: () => {
  },
  // 关键字搜索
  isKeywordSearch: true,
  // 每页记录数
  pageSize: 10,
})

const resultList = ref<Object[]>([])

const isLoadingMore = ref(false)
const isLastPage = ref(false)


const searchFormPopupOb = ref()

const searchForm = ref({last_id: 0})

const requestApi = ref()

onMounted(() => {
  options.value = Object.assign({}, options.value, props.options)
  requestApi.value = options.value.api

  let isKeyword = false

  props.columns.map((item: { index: string, value: ptAny }) => {
    searchForm.value[item.index] = item.value
    item.index === "keyword" && (isKeyword = true)
  })

  if (!isKeyword && options.value.isKeywordSearch) {
    props.columns.push({
      index: "keyword",
      title: "搜索内容",
      value: "",
      formType: "input"
    })
  }

  let param = Object.assign({}, searchForm.value)

  for (let prop in param) {
    for (let i in props.columns) {
      if (props.columns[i].index === prop && props.columns[i].formType === 'time') {
        param[prop] = param[prop].length > 0 ? param[prop][0] + ',' + param[prop][1] : ''
        break;
      }
    }
  }

  requestApi.value(param).then((res: ptAny) => {
    resultList.value = res.data.list
  })

})

const loadMore = () => {
  if (isLoadingMore.value || !resultList.value.length || isLastPage.value) {
    return
  }
  isLoadingMore.value = true
  searchForm.value.last_id = resultList.value[resultList.value.length - 1].id
  requestApi.value(searchForm.value).then((res: ptAny) => {
    isLoadingMore.value = false
    if (res.data.list.length <= 0) {
      isLastPage.value = true
      return
    }
    resultList.value.push(...res.data.list)
  })
}

const submit = () => {
  isLastPage.value = false
  searchForm.value.last_id = 0
  resultList.value = []

  let param = Object.assign({}, searchForm.value)
  for (let prop in param) {
    for (let i in props.columns) {
      if (props.columns[i].index === prop && props.columns[i].formType === 'time') {
        param[prop] = param[prop].length > 0 ? param[prop][0] + ',' + param[prop][1] : ''
        break;
      }
    }
  }

  requestApi.value(param).then((res: ptAny) => {
    if (res.data.list.length <= 0) {
      isLastPage.value = true
      return
    }
    resultList.value.push(...res.data.list)
  })
}
// @ts-ignore
defineExpose({
  loadMore
})
</script>

<style scoped lang="scss">
.container{
  height: 100vh;
  position: relative;
}

.search_box {
  position: -webkit-sticky;
  position: sticky;
  background-color: #fff;
  z-index: 1;
  top: var(--window-top);
}

.top {
 display: flex;
 flex-direction: row;
 width: 100%;
 justify-content: center;
 background-color: $theme-color;
 align-items: center;
}

.keyword {
 display: flex;
 align-items: center;
 height: 108rpx;
 position: sticky;
 top: 0;
 z-index: 2;
 width: 70vw;

 .content {
   flex: 1;
   display: flex;
   align-items: center;
   height: 70rpx;
   background-color: #f3f3f3;
   border-radius: 16rpx;
   padding: 0 20rpx;
 }

 .from {
   flex: 1;
 }

 .input {
   color: $uni-text-color;
   font-size: 30rpx;
   font-weight: bold;
   width: 100%;
 }

 .remind {
   color: #9e9e9e;
   font-size: 28rpx;
 }
}

.choose_where {
 display: flex;
 align-items: center;
 justify-content: center;
 padding-left: 15rpx;
 font-size: 40rpx;
 color: $uni-text-color-inverse;
}

.search_form {
 display: flex;
 flex-direction: column;
 width: 100%;
 background: $uni-text-color-inverse;
 border-radius: 15rpx 10rpx 0 0;
 padding: 10rpx 20rpx 30rpx 20rpx;

 .select_vip, .select_time {
   padding: 0 22rpx;
 }

 .select_action {
   text-align: center;

   .btn {
     margin: 0 50rpx;
     width: 200rpx;
     background-color: $theme-color;
   }
 }
}
</style>