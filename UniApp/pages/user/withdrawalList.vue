<template lang="pug">
Search(ref="searchCps" :columns="columns" :options="options")
  template(v-slot="{result}")
    div.order_list
      div.item(v-for="item in result")
        div.left
          span.content {{item.content}}
          span.price 订单金额: {{item.amount_price_text}}
          span.status 订单状态: {{item.status_text}}
          span.ord_sn 订单号: {{item.ord_sn}}
          span.created_at 订单时间: {{item.created_at ? item.created_at : ''}}
        div.actions
          span.action.copy(@click="copySn(item)") 复制订单号
</template>

<script setup lang="ts">
// @ts-ignore
import {ref} from "vue"
import Search from '../../components/Search.vue'
import {getWithdrawalList} from "../../common/api"
// @ts-ignore
import {onReachBottom} from "@dcloudio/uni-app"

const searchCps = ref()

const options = ref({
  api: getWithdrawalList,
});

const columns = ref([
  {
    index: "create_time",
    title: "创建时间",
    value: [],
    formType: "time",
  }
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
    flex-direction: row;
    padding: 15rpx 30rpx;
    margin: 5rpx 0;
    background-color: $uni-text-color-inverse;
    justify-content: space-between;
  }
  .left{
    display: flex;
    flex-direction: column;
    width: 80%;
    .price{
      color: #e18c8c;
      font-size: 26rpx;
    }
    .ord_sn, .created_at, .status{
      font-size: 26rpx;
      color: #c8c7cc;
    }
  }
  .copy{
    padding: 4rpx 5rpx;
    border-radius: 5%;
    background-color: $theme-color;
    color: $uni-text-color-inverse;
    font-size: 23rpx;
    border: 1rpx solid $theme-color;
  }
  .actions{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    .action{
      padding: 4rpx 5rpx;
      border-radius: 5%;
      font-size: 23rpx;
      margin: 5rpx 0;
    }
    .copy{
      background-color: $theme-color;
      border: 1rpx solid $theme-color;
      color: $uni-text-color-inverse;
    }
  }
}
</style>