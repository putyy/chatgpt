<template lang="pug">
uni-popup(ref="popupObj" type="bottom" @change="change")
  scroll-view.open_vip_popup(scroll-y="true")
    div.open_vip
      div.vip__title
        span 原创不易，支持作者
      uni-table(border stripe)
        uni-tr(v-for="(item ,index) in equity" :key="index")
          uni-th(align="center" v-for="(item1 ,index1) in item" :key="index1" :style="{color: item1.color}") {{item1.text}}
      div.use_vip 当前等级: {{user.vip_name}}
      div.notice ps：VIP有效期内,支持补差价升级，例如: VIP升级一星抵扣上一次一次订单金额
      div.vip_list
        div.item(v-for="(item ,index) in vipConfig" :key="index" @click="chooseVip(index)" :class="[chooseItem.level === item.level ? 'vip__choose' : '',  item.is_choose ? '' : ' vip_not_choose' ]")
          div.box
            span.level {{item.name}}
            span.rmb ——RMB——
            span.price 限时{{item.price}}
          span.old_price 原价{{item.price_old}}
      div.submit
        input.kami_code(placeholder='输入卡密(暂时只支持卡密支付)' type="input" :value="kami_code" @input="(res)=>{kami_code= res.detail.value}")
        button.go_pay(@click="goPay") {{chooseItem.btn_text}}{{chooseItem.price_pay ? '(￥'+chooseItem.price_pay+')' : ''}}
      div.user_agreement(@click="agreement")
        span 支付即表示同意
        span.aa 《用户协议》
        span 购买后不支持退款
  AgreementPopup(:isShow="isShowAgreement" :scene="1000" @close="isShowAgreement=false")
</template>

<script lang="ts" setup>
// @ts-ignore
import {computed, ref, watch} from "vue"
import {getVipConfig, postKamiOpenVip} from "../common/api"
import AgreementPopup from './AgreementPopup.vue'
// @ts-ignore
const props = defineProps(["isShow"]);
// @ts-ignore
const emit = defineEmits(['close'])
const isShowAgreement = ref(false)
const popupObj = ref()
const equity: ptAny = ref([])
const user = ref({
  vip:0,
  vip_name: "免费会员"
})
const vipConfig = ref<{is_choose: boolean,is_default: boolean,price_pay: number, level: number, btn_text: string}[]>([])
const kami_code = ref('')
const isShowCp = computed(() => {
  return props.isShow
})

const chooseItem = ref({
  price_pay: 0,
  level: 0,
  btn_text: ''
})

const agreement = ()=>{
  isShowAgreement.value = true
}

const change = (res: ptAny)=>{
  if (res.show === false){
    emit('close')
  }
}

const chooseVip = (index: number)=>{
  if (!vipConfig.value[index].is_choose) {
    // @ts-ignore
    uni.showToast({
      title: '不能选择低于自身等级的VIP',
      icon: 'none',
      duration: 1000
    })
    return
  }
  chooseItem.value = Object.assign(chooseItem.value, vipConfig.value[index])
}

const goPay = () => {
  if (!kami_code.value) {
    // @ts-ignore
    uni.showToast({
      title: '请填写卡密',
      icon: 'none',
      duration: 1500
    })
    return
  }

  if (!chooseItem.value.level) {
    // @ts-ignore
    uni.showToast({
      title: '请选择需要开通的VIP等级',
      icon: 'none',
      duration: 1500
    })
    return
  }

  postKamiOpenVip({kami_code: kami_code.value, level: chooseItem.value.level}).then((res: ptAny)=>{
    if (res.code === 200) {
      // @ts-ignore
      uni.showToast({
        title: '开通成功',
        icon: 'success',
        duration: 1500
      })
      // @ts-ignore
      uni.clearStorageSync()
      // @ts-ignore
      uni.redirectTo({
        url: '/pages/login'
      })
      return
    }
  })
}

watch(isShowCp, v=>{
  if (v){
    if (vipConfig.value.length <= 0){
      getVipConfig().then((res: ptAny)=>{
        vipConfig.value = res.data.config
        equity.value = res.data.equity
        user.value = res.data.user
        let tLen = vipConfig.value.length
        for (let i=0; i< tLen; i++) {
          if (vipConfig.value[i].is_default){
            chooseItem.value = Object.assign(chooseItem.value, vipConfig.value[i])
          }
        }
        popupObj.value.open()
      })
      return
    }
    popupObj.value.open()
  }else{
    popupObj.value.close()
  }
})
</script>

<style scoped lang="scss">
.open_vip_popup{
  max-height: 80vh;
  border-radius: 15rpx 15rpx 0 0;
  padding-top: 15rpx;
}
.open_vip{
  width: 100%;
  background-color: #fff;
  border-radius: 15rpx 15rpx 0 0;
  .vip__title{
    display: flex;
    align-items: center;
    padding: 15rpx;
    justify-content: center;
    font-weight: bold;
    font-size: 36rpx;
  }

  .vip_list{
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
  }

  .item{
    display: flex;
    flex-direction: column;
    text-align: center;
    border: .3rpx solid #efe8e8;
    margin: 15rpx;
    border-radius: 10%;
    .box{
      color: #fff;
      background-color: #b1765f;
      border-radius: 10% 10% 0 0;
      display: flex;
      flex-direction: column;
      padding: 30rpx;
    }
    .level{
      font-weight: bold;
      font-size: 30rpx;
    }
    .rmb{
      font-size: 24rpx;
      color: #353131;
    }
    .price {
      font-size: 34rpx;
    }
    .old_price{
      color: #fa8806;
      font-size: 26rpx;
      text-decoration: line-through;
      padding: 15rpx;
    }
  }

  .vip_not_choose{
    background-color: #efefef !important;
    .box{
      background-color: #a0a0a0 !important
    }
  }

  .vip__choose{
    border: .3rpx solid #fa8806;
    .box{
      background: linear-gradient(#FF9800 0%, #FF5722 20%, #e0562b 100%) !important;
    }
  }

  .submit{
    padding: 15rpx 0;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    .kami_code{
      border: #c8c7cc solid 1rpx;
      padding: 15rpx;
      margin: 10rpx 0;
      width: 280px;
    }
  }

  .go_pay{
    width: 330rpx;
    font-size: 30rpx;
    color: #fff;
    background: linear-gradient(#FF9800 0%, #FF5722 20%, #e0562b 100%) !important;
  }

  .user_agreement{
    text-align: center;
    font-size: 23rpx;
    color: #8c8c8c;
    width: 100%;
    padding-bottom: 15rpx;
    .aa{
      color: #00c6ff;
    }
  }
  .use_vip{
    color: $theme-color;
    padding: 10rpx 20rpx;
  }
  .notice{
    font-size: 23rpx;
    color: red;
    padding: 0 20rpx;
  }
}
</style>