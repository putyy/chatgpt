<template lang="pug">
Search(ref="searchCps" :columns="columns" :options="options")
  template(v-slot="{result}")
    div.order_list
      div.item(v-for="item in result")
        span.content {{item.remark}}
        span.price 金额: {{item.balance_text}}
        span.status 场景: {{item.scene_text}}
        span.created_at 发生时间: {{item.created_at ? item.created_at : ''}}
</template>

<script setup lang="ts">
// @ts-ignore
import {ref} from "vue"
import Search from '../../components/Search.vue'
import {getChangeLogList} from "../../common/api"
// @ts-ignore
import {onReachBottom} from "@dcloudio/uni-app"

const searchCps = ref()

const options = ref({
  api: getChangeLogList,
});

const columns = ref([
  {
    index: "create_time",
    title: "创建时间",
    value: [],
    formType: "time",
  },{
    index:"direction",
    title: "类型",
    value: 0,
    formType: "select",
    data: [
      {
        value: 0,
        text: "全部"
      }, {
        value: 1,
        text: "收入"
      }, {
        value: 2,
        text: "支出"
      }
    ]
  },
]);

onReachBottom(() => {
  searchCps.value.loadMore()
})

const copySn = (item: ptAny) => {
  // @ts-ignore
  uni.setClipboardData({
    data: item.ord_sn,
    success: () => {
    }
  });
}

</script>

<style scoped lang="scss">
.order_list{
  display: flex;
  flex-direction: column;
  padding-top: 25rpx;
  background-color: #f7f7f7;
  .item{
    display: flex;
    flex-direction: column;
    padding: 15rpx 30rpx;
    margin: 5rpx 0;
    background-color: $uni-text-color-inverse;
    justify-content: space-between;
    .price{
      color: #e18c8c;
      font-size: 26rpx;
    }
    .ord_sn, .created_at, .status{
      font-size: 26rpx;
      color: #c8c7cc;
    }
  }
}
</style>