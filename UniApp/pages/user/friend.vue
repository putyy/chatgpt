<template lang="pug">
Search(ref="searchCps" :columns="columns" :options="options")
  template(v-slot="{result}")
    div.friend_list
      div.item(v-for="item in result")
        div.left
          div.user
            image.head(:src="item.head_img")
            span.vip {{item.vip_name}}
          div.info
            span.nick_name {{item.nick_name}}
            span.mobile {{item.mobile}}
            span.register_time {{item.created_at ? item.created_at : ''}}
        div.actions
          span.copy_mobile(@click="copyMobile(item)") 复制手机号
</template>

<script setup lang="ts">
// @ts-ignore
import {ref} from "vue"
import Search from '../../components/Search.vue'
import {getFriends} from "../../common/api"
// @ts-ignore
import {onReachBottom} from "@dcloudio/uni-app"

const searchCps = ref()

const options = ref({
  api: getFriends,
});

const columns = ref([
  {
    index: "register_time",
    title: "注册时间",
    value: [],
    formType: "time",
  },
  {
    index:"vip",
    title: "vip等级",
    value: -1,
    formType: "select",
    data: [
      {
        value: -1,
        text: "全部"
      }, {
        value: 0,
        text: "免费"
      }, {
        value: 10,
        text: "VIP"
      }, {
        value: 20,
        text: "一星"
      }, {
        value: 30,
        text: "二星"
      },
    ]
  },
]);

const copyMobile = (item: ptAny) => {
  // @ts-ignore
  uni.setClipboardData({
    data: item.mobile,
    success: () => {
    }
  });
}

onReachBottom(() => {
  searchCps.value.loadMore()
})

</script>

<style scoped lang="scss">
.friend_list{
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
    .left{
      display: flex;
      flex-direction: row;
    }
  }
  .copy_mobile{
    padding: 4rpx 5rpx;
    border-radius: 5%;
    background-color: $theme-color;
    color: $uni-text-color-inverse;
    font-size: 23rpx;
    border: 1rpx solid $theme-color;
  }
  .user,.info,.actions{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  .info{
    margin: 0 15rpx;
    display: flex;
    flex-direction: column;
    align-items:  flex-start;
    font-size: 33rpx;
    .nick_name{
      width: 250rpx;
      overflow-y: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .register_time, .mobile{
      font-size: 24rpx;
    }
  }
  .user{
    width: 100rpx;
    position: relative;
    .head{
      width: 100rpx;
      height: 100rpx;
      box-shadow: 0 0 .12 rgba(249, 71, 71, .1);
      border-radius: 50%;
      background: #c1c1c1;
    }

    .vip {
      position: absolute;
      font-size: 15rpx;
      background: $theme-color;
      border-radius: 20%;
      /* height: 40rpx; */
      bottom: 0;
      right: 0;
      color: uni-text-color-inverse;
      //width: 40rpx;
      text-align: center;
    }
  }
}
</style>