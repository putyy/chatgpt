<template lang="pug">
div.wallet
  div.back_box
    div.top
    div.bottom
  div.info_box
    div.prices
      div.can
        span.desc 可提现
        span.price ￥{{wallet.balance}}
      div.more
        div.item
          span.desc(@click="byNavigateTo('/pages/user/walletList')") 累计收益
          span.price ￥{{wallet.balance_total}}
        div.item
          span.desc(@click="byNavigateTo('/pages/user/withdrawalList')") 已提现
          span.price ￥{{wallet.balance_total-wallet.balance}}
    div.pay_box
      input.price(placeholder-class="remind" type="text" placeholder="请输入提现金额" v-model="price" confirm-type="search")
      button.submit(@click="submit") 提现
      span.desc 提现相关问题请联系客服！
</template>

<script setup lang="ts">
// @ts-ignore
import {ref, onMounted} from 'vue'
import {getWalletInfo, postWithdrawal} from "../../common/api"
import {byNavigateTo} from "../../common/utils/jump"

const price = ref(0)

const wallet = ref({
  balance: 0,
  balance_total: 0,
})

onMounted(()=>{
  getWalletInfo().then((res: ptAny)=>{
    wallet.value = res.data
    price.value = res.data.balance
  })
})

const submit = () => {
  if (wallet.value.balance <= 0) {
    // @ts-ignore
    uni.showToast({
      title: '余额不足',
      icon: 'error',
      duration: 1000
    })
    return
  }

  if (parseFloat(price.value) > wallet.value.balance) {
    // @ts-ignore
    uni.showToast({
      title: '余额不足',
      icon: 'error',
      duration: 1000
    })
    return
  }
  postWithdrawal({amount: price.value}).then((res: ptAny) => {
    if (res.code === 200) {
      wallet.value.balance = wallet.value.balance - parseFloat(price.value)
      // @ts-ignore
      uni.showToast({
        title: '申请成功',
        icon: 'success',
        duration: 1000
      })
    }
  })
}

</script>

<style scoped lang="scss">
.wallet{
  width: 100%;
  height: 100%;
  position: fixed;
}
.back_box{
  display: flex;
  flex-direction: column;
  height: 100%;
  .top{
    height: 350rpx;
    background-color: rgb(78, 129, 176);
  }
  .bottom{
    height: calc(100% - 350rpx);
    background-color: #e6edee;
  }
}

.info_box {
  display: flex;
  width: 600rpx;
  flex-direction: column;
  position: absolute;
  background-color: #ffffff;
  top: 450rpx;
  left:50%;
  transform: translate(-50%,-50%);
  border-radius: 15rpx;
  padding: 30rpx;
  .prices{
    display: flex;
    flex-direction: column;
    padding-bottom: 40rpx;
    .can{
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 15rpx 0;
      font-weight: bold;
      .desc{
        font-size: 36rpx;
      }
      .price{
        color: #ec8d13;
        font-size: 40rpx;
        padding: 15rpx 0;
      }
    }
    .more{
      display: flex;
      flex-direction: row;
      justify-content: space-around;
      padding: 15rpx 0;
      .desc{
        font-size: 32rpx;
        color: #5e80a6;
      }
      .price{
        color: #ec8d13;
        font-size: 32rpx;
        padding: 10rpx 0;
      }
      .item{
        display: flex;
        flex-direction: column;
        align-items: flex-start;
      }
    }
  }

  .pay_box{
    display: flex;
    flex-direction: column;
    align-items: center;
    .price{
      color: $uni-text-color;
      font-size: 30rpx;
      font-weight: bold;
      width: 60%;
      border: #efecec solid 1rpx;
      padding: 15rpx;
      border-radius: 15rpx;
    }
    .submit{
      width: 498rpx;
      height: 78rpx;
      background: $theme-color;
      box-shadow: 0 6rpx 12rpx rgba(254, 27, 27, .3);
      border-radius: 48rpx;
      font-size: 36rpx;
      font-weight: bold;
      color: $uni-text-color-inverse;
      margin-top: 50rpx;
    }

    .desc{
      color: #999999;
      font-size: 24rpx;
      padding-top: 20rpx;
    }
  }
}
</style>