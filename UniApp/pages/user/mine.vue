<template lang="pug">
div.mine(v-show="personal.userUid > 0")
  div.mine_personal
    image.head(:src="personal.head ? personal.head : '../../static/images/default_head_img.png'")
    div.name-box
      p.name {{personal.name}}
      p.rank(@click.stop="isShowOpenVip = true")
        span.grade {{personal.rank}}
    div.action
      span.setting(@click.stop="notice") 偏好设置
      span.edit(@click.stop="byNavigateTo('/pages/user/perfect')") 编辑资料
  div.mine_my
    div.friend(@click.stop="byNavigateTo('/pages/user/friend')")
      span 我的好友
      span.num {{personal.friendNum}}
    div.friend(@click="byNavigateTo('/pages/user/wallet')")
      span 我的钱包
      span.num ￥{{personal.moneyNum}}
  div.mine_ad(v-if="banner.length > 0")
    uni-swiper-dot(mode="round")
      swiper.swiper-box 
        swiper-item(v-for="item in banner")
          image.img(:src="item.img_url" @click="clickBanner(item)")
  div.menus
    div.menu_group(v-for="item in menus")
      label.group_name {{item.name}}
      div.menu_list
        div.menu__box(v-for="menu in item.menus" @click="clickMenu(menu)")
          image.menu_icon(:src="menu.icon ? menu.icon : '../../static/images/default_head_img.png'")
          span.menu_name {{menu.name}}
  // 底部导航
  FooterCommon(:footerIndex="2")
  // 客服弹窗
  QrCodePopup(:isShow="isShowCode" :codeData="publicCodeData" @close="isShowCode=false")
  open-vip(:isShow="isShowOpenVip" @close="isShowOpenVip=false")
</template>

<script setup lang="ts">
// @ts-ignore
import {onMounted, reactive, ref} from 'vue'
import {useIndexStore} from '../../store'
import FooterCommon from '../../components/FooterCommon.vue'
import {userService} from "../../logic/user"
import {byNavigateTo} from "../../common/utils/jump"
import QrCodePopup from '../../components/QrCodePopup.vue'
import OpenVip from "../../components/OpenVipPopup.vue"

const {initMine} = userService()
let isShowOpenVip = ref(false)
const store = useIndexStore()

const personal = reactive({
  head: '', // 头像
  name: '', // 名字
  userUid: '', // 用户uid
  rank: '', // 等级级别
  friendNum: '', // 声友数量
  moneyNum: '', // 我的钱包数量
  mobile: '', // mobile
})

const menus = ref([])

// 联系客服弹窗
const publicCodeData = reactive({
  nickname: '',
  portraitSrc: '', // 头像
  codeSrc: '', // 二维码
  text: '~', // 描述
  hint: '', // 描述
  promptText: '' // 提醒文字
})

const isShowCode = ref(false) // 联系客服弹窗

const banner = ref([])

const clickBanner = (item: ptAny)=>{
  if (item.url) {
    /* #ifdef H5 */
    window.location.href = item.url
    /* #endif */

    /* #ifdef APP-PLUS */
    // @ts-ignore
    plus.runtime.openURL(item.url)
    /* #endif */
  }
}

const notice = () => {
  // @ts-ignore
  uni.showToast({
    title: '马不停蹄,开发中!',
    icon: 'none',
    duration: 1500
  })
}

/**
 * 快捷入口跳转到对应页面
 * @param item {Object} 对应数据
 */
const clickMenu = (item: ptAny) => {
  if (item.click_type === 1) {
    byNavigateTo(item.path)
    return;
  }
  switch (item.click_func) {
    case "1001":
      let data = store.customerInfo
      publicCodeData.nickname = "我是【" + data.user_name + "】"
      publicCodeData.codeSrc = data.wx_img_url // 二维码
      publicCodeData.text = '可通过微信联系我哦~' // 描述
      publicCodeData.promptText = '长按识别二维码添加' // 提醒文字
      publicCodeData.hint = '工作时间: ' + data.work_time
      isShowCode.value = true
      return;
    case "1002":
      isShowOpenVip.value = true
      return;
    case "1003":
      // @ts-ignore
      uni.clearStorageSync()
      byNavigateTo('/pages/login')
      return;
  }

}

onMounted(async () => {
  let cache: ptAny = await initMine()
  if (cache) {
    personal.moneyNum = cache?.amount
    personal.friendNum = cache?.friend_num
    personal.head = cache?.head_img
    personal.name = cache?.nick_name
    personal.userUid = cache?.uid
    personal.rank = cache?.vip_name
    personal.mobile = cache?.mobile
    banner.value = cache?.banner
    menus.value = cache?.menus
  }
})

</script>

<style scoped lang="scss">
  :deep(.open_vip .uni-table){
    min-width: unset !important;
  }

  :deep(.open_vip .uni-table-th){
    font-size: 23rpx;
  }
  .swiper-box{
    height: 182rpx;
  }

  .mine {
    width: 92vw;
    background: linear-gradient(180deg, $uni-bg-color, #f6f6f6);
    padding: 40rpx 30rpx 140rpx 30rpx;
  }

  .mine_personal {
      width: 100%;
      height: 106rpx;
      margin-top: 20rpx;
      display: flex;

      .head {
        width: 106rpx;
        height: 106rpx;
        box-shadow: 0 0 .12 rgba(249, 71, 71, .1);
        border-radius: 50%;
        background: #c1c1c1;
      }

      .name-box {
        margin-left: 26rpx;
        display: flex;
        flex-direction: column;

        .name {
          width: 100%;
          font-size: 38rpx;
          font-weight: bold;
          color: $uni-text-color;
        }

        .rank {
          max-width: 180rpx;
          padding: 0 10rpx;
          border-radius: 8rpx;
          font-size: 22rpx;
          line-height: 44rpx;
          color: $uni-text-color-inverse;
          background-color: $theme-color;
          width: fit-content;
        }

      }

      .action {

        font-size: 26rpx;
        color: $uni-text-color-placeholder;
        margin-left: auto;

        padding-right: 30rpx;
        display: flex;
        flex-direction: column;
      }
      .setting, .edit{
        height: 40rpx;
        margin: 10rpx 0;
        background-size: 13rpx 25rpx;
      }
  }

  .mine_my{
      height: 166rpx;
      margin-top: 30rpx;
      background: rgba(255, 255, 255, 0.39);
      box-shadow: 0 0 20rpx rgba(0, 0, 0, .1);
      border-radius: 16rpx;
      display: flex;
      align-items: center;

      .friend {
        flex: 1;
        text-align: center;
        font-size: 30rpx;
        color: $uni-text-color;
        display: flex;
        flex-direction: column;
      }

      .num {
        font-size: 38rpx;
        font-weight: 600;
        color: $uni-text-color;
      }
    }

  .mine_ad {
    height: 182rpx;
    margin-top: 30rpx;
    box-shadow: 0 0 20rpx rgba(254, 27, 27, .35);
    border-radius: 16rpx;

    .img {
      width: 100%;
      height: 100%;
      border-radius: 16rpx;
    }
  }

  .menus{
    margin-top: 25rpx;
    border-top: #f1f1f1 5rpx dashed;
  }

  .menu_group{
    width: 100%;
    display: flex;
    flex-direction: column;
    margin-top: 18rpx;
    .group__name{
      padding: 10rpx;
      font-size: 34rpx;
      font-weight: 700;
      color: #090909;
    }
    .menu_list {
      width: 100%;
      display: flex;
      flex-wrap: wrap;
      padding: 30rpx 0;
      border: solid #f1f1f1 1rpx;
      border-radius: 26rpx;
      box-shadow: 0 2px 6px 0 rgb(186 197 214 / 50%);
    }
    .menu__box{
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 33.33%;
      margin-top: 15rpx;
      margin-bottom: 15rpx;
    }
    .menu_icon{
      width: 80rpx;
      height: 80rpx;
    }
    .menu_name{
      margin-top: 7rpx;
      font-size: 28rpx;
      color: #545454;
    }
  }

</style>
