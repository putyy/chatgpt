<template lang="pug">
div.login
  div.header
    div.hello
      text {{type === "login" ? "Hi，欢迎使用" : "Hi，欢迎注册"}}
  div.content
    div.tip 账号
    input.input(:value="loginData.user_name" @input="(res)=>{loginData.user_name = res.detail.value}" type="text" placeholder="请输入账号" placeholder-class="placeholder")

    div.tip 密码
    input.input(:value="loginData.password" @input="(res)=>{loginData.password = res.detail.value}" type="password" placeholder="请输入密码" placeholder-class="placeholder")

    div.tip(v-show="type === 'register'") 邀请码
    input.input(:value="loginData.vid" v-show="type === 'register'" @input="(res)=>{loginData.vid = res.detail.value}" type="text" placeholder="必填" placeholder-class="placeholder")

    div.tip.link( @tap="changeType") {{type === "login" ? "还没有账号？快去注册吧" : "已有账号，前往登录"}}
    button.submit(type="button" @click="loginHandle") {{type === "login" ? "登录" : "注册"}}
</template>

<script lang="ts" setup>
// @ts-ignore
import {onMounted, ref} from "vue"
import {getLogin} from "../common/api"
import {AiLoginCacheKey, xTokenCacheKey} from "../common/const"
import {useIndexStore} from '../store'

const type = ref("login")

const loginData = ref({
  user_name: '',
  password: '',
  vid: '',
})

const route: any = ref('/pages/user/mine')

const store = useIndexStore()

onMounted(() => {
  // @ts-ignore
  let pages = getCurrentPages()
  if (pages.length) {
    let tmp = pages[pages.length - 1].route
    if (/^pages/.test(tmp) && '/' + tmp != "/pages/login") {
      route.value = '/' + tmp
    }
  }
  // @ts-ignore
  let cache = uni.getStorageSync(AiLoginCacheKey)
  if (cache) {
    loginData.value = cache
  }
})

const changeType = () => {
  type.value = type.value === "register" ? "login" : "register"
}

const loginHandle = () => {
  if (loginData.value.user_name === "" || !/[a-zA-Z0-9]{6,18}$/.exec(loginData.value.user_name)) {
    // @ts-ignore
    uni.showToast({
      title: '用户名只能是6-18位大小写字母、数字(可组合)',
      icon: 'none',
      duration: 1500
    })
    return
  }

  if (loginData.value.password === "" || !/[a-zA-Z0-9]{6,18}$/.exec(loginData.value.password)) {
    // @ts-ignore
    uni.showToast({
      title: '密码只能是6-18位大小写字母、数字(可组合)',
      icon: 'none',
      duration: 1500
    })
    return
  }

  if (type.value === "register" && loginData.value.vid === "") {
    // @ts-ignore
    uni.showToast({
      title: '邀请人ID必须',
      icon: 'none',
      duration: 1500
    })
    return
  }

  getLogin(loginData.value).then(async (res: ptAny) => {
    if (res.code === 200) {
      // @ts-ignore
      uni.clearStorageSync()
      // @ts-ignore
      uni.setStorageSync(xTokenCacheKey, {
        token: res.data.token,
        check_status: res.data.check_status,
        login_time: res.data.login_time,
        version: res.data.version,
      })
      // @ts-ignore
      uni.setStorageSync(AiLoginCacheKey, loginData.value)
      await store.setInit().then(async (r: ptAny) => {
      })
      // @ts-ignore
      uni.reLaunch({url: route.value})
    } else {
      // @ts-ignore
      uni.showToast({
        title: res.message,
        icon: 'none',
        duration: 1500
      })
    }
  })
}
</script>

<style scoped lang="scss">
	.login {
    height: 80vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    .header{
      padding-bottom: 50rpx;
      width: 60%;
      .hello {
        font-size: 50rpx;
        font-weight: 500;
        display: flex;
        color: var(--black);
        align-items: center;
      }
    }
    .content{
      width: 60%;
      .input {
        border: 0;
        color: var(--black);
        line-height: 70rpx;
        border-bottom: 1px solid #ccc;
        margin-top: 30rpx;
        padding-bottom: 20rpx;
      }

      .placeholder {
        color: #c0c3cb;
        font-size: 32rpx;
      }

      .tip{
        font-size: 28rpx;
        margin: 25rpx 0 0 0rpx;
        color: var(--black);
      }

      .link {
        color: $uni-color-primary;
      }
      .submit {
        margin-top: 50rpx;
        height: 80rpx;
        background: $theme-color;
        color: #fff;
        border-radius: 45rpx;
        font-size: 32rpx;
        line-height: 80rpx;
      }
    }
	}

</style>