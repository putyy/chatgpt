<template lang="pug">
div.edit(v-show="!showCrop" )
  div.edit_top
    div.head_box(@click="chooseImage(0)")
      image.head(:src="basicsInfo.head")
      div.camera
    div.table
      span.text 用户昵称
      input.value(placeholder='请输入昵称' :value="basicsInfo.name" @input="(res)=>{basicsInfo.name= res.detail.value}")
    div.table
      span.text 手机号
      input.value(placeholder='请输入手机号' type="number" :value="basicsInfo.mobile" @input="(res)=>{basicsInfo.mobile= res.detail.value}")
  button.edit_btn(@click="clickSave") 保存
div.cropper_container(v-show="showCrop" )
  BtCropper(ref="cropper" :ratio="cropperRatio" :imageSrc="cropperImgSrc")
  div.cropper_operate
    button(@click="cropClose") 取消
    button(@click="cropSave") 保存
</template>
<script setup lang="ts">
// @ts-ignore
import {ref, onMounted} from 'vue'
import {useIndexStore} from '../../store'

import {
  getQiniuToken,
  postUserDataInfo,
} from '../../common/api'
import {uploadQiniu} from "../../common/func"
import {userService} from "../../logic/user"
import BtCropper from "../../uni_modules/bt-cropper_3.0.1/components/bt-cropper/bt-cropper.vue"
import {byNavigateTo} from "../../common/utils/jump"

const {initMine, updateMineUserCache} = userService()

const cropper = ref()
const cropperImgSrc = ref("")
const cropperRatio = ref(1)
const tempIndex = ref(0)
const showCrop = ref(false)

const store = useIndexStore()

const basicsInfo = ref({
  head: '', // 头像
  mobile: '', // 手机号
  name: '', // 昵称
  nameLen: 0, // 昵称长度
})

onMounted(async () => {
  let cache = await initMine()
  basicsInfo.value.head = cache.head_img
  basicsInfo.value.mobile = cache.mobile
  basicsInfo.value.name = cache.nick_name
})

const setImgData = (index: number, res: string) => {
  switch (index) {
    case 0:
      basicsInfo.value.head = res
      break;
  }
}

const cropClose = () => {
  setImgData(tempIndex.value, cropperImgSrc.value)
  showCrop.value = false
}

const cropSave = () => {
  cropper.value.crop().then((res: string) => {
    if ("tempFilePath" !== res) {
      setImgData(tempIndex.value, res)
    }
    showCrop.value = false
  })
}

const chooseImage = (index: number) => {
  // @ts-ignore
  uni.chooseImage({
    count: 1,
    sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
    sourceType: ['album'], //从相册选择
    success: (res: ptAny) => {
      cropperRatio.value = 1
      tempIndex.value = index
      cropperImgSrc.value = res.tempFilePaths[0]
      showCrop.value = true
    }
  })
}

/**
 * 点击保存
 */
const clickSave = () => {
  basicsInfo.value.nameLen = 0
  let str = basicsInfo.value.name
  let l = str.length
  // 判断昵称长度
  for (let i = 0; i < l; i++) {
    if ((str.charCodeAt(i) & 0xff00) !== 0) {
      basicsInfo.value.nameLen++
    }
    basicsInfo.value.nameLen++
  }
  if (!basicsInfo.value.name) {
    // @ts-ignore
    uni.showToast({
      title: '昵称不能为空',
      icon: 'none',
      duration: 1000
    })
    return
  }
  if (basicsInfo.value.nameLen > 32) {
    // @ts-ignore
    uni.showToast({
      title: '昵称最多只能16个字哦',
      icon: 'none',
      duration: 1000
    })
    return
  }
  if (!basicsInfo.value.head) {
    // @ts-ignore
    uni.showToast({
      title: '头像不能为空',
      icon: 'none',
      duration: 1000
    })
    return
  }

  if (!basicsInfo.value.mobile) {
    // @ts-ignore
    uni.showToast({
      title: '手机号不能为空',
      icon: 'none',
      duration: 1000
    })
    return
  }
  if (basicsInfo.value.mobile.length > 20 || basicsInfo.value.mobile.length < 5) {
    // @ts-ignore
    uni.showToast({
      title: '手机号格式不正确',
      icon: 'none',
      duration: 1000
    })
    return
  }
  uploadAvatarImg().then(() => {
    let data = {
      head_img: basicsInfo.value.head,
      mobile: basicsInfo.value.mobile,
      nick_name: basicsInfo.value.name,
    }
    postUserDataInfo(data).then((res: ptAny) => {
      if (res.code === 200) {
        // @ts-ignore
        uni.showToast({
          title: '保存成功',
          icon: 'none',
          duration: 1000
        })
        updateMineUserCache(data)
        byNavigateTo("/pages/user/mine")
      }
    })
  })
}

/**
 * 上传所有图片
 */
const uploadAvatarImg = async () => {
  let scene = []
  let reg = RegExp(store.scene.upload.domain)
  if (!reg.test(basicsInfo.value.head)) {
    scene.push(store.scene.upload.image.ai_head_img)
  }

  if (scene.length <= 0) {
    return
  }

  let sceneArr: utilsType.qiniuInfo[] = []
  await getQiniuToken(scene.join("-")).then((res: ptAny) => {
    sceneArr = res.data
  })

  if (sceneArr.length <= 0) {
    return
  }

  for (let key in sceneArr) {
    switch (sceneArr[key].scene) {
      case store.scene.upload.image.ai_head_img:
        await uploadQiniu(sceneArr[key], basicsInfo.value.head).then((res: ptAny) => {
          basicsInfo.value.head = res.url
        })
        break;
    }
  }
}

</script>
<style lang="scss" scoped>
.cropper_container{
  height:100vh;
  width: 100vw;
  position: absolute;
  top: 0;
  left: 0;
  background-color: #999999 !important;
  z-index: 1;
  .cropper_operate{
    display: flex;
    width: 100vw;
    position: absolute;
    bottom: 10rpx;
  }
}

.edit {
  width: 100vw;
  min-height: 100vh;
}

.edit_top {
  background-color: $uni-text-color-inverse;
  padding: 80rpx 30rpx 0;

  .head_box {
    width: 100%;
    height: 226rpx;
    position: relative;
    margin-bottom: 40rpx;

    .head {
      width: 226rpx;
      height: 226rpx;
      border-radius: 50%;
      box-shadow: 0 0 120rpx rgba(249, 71, 71, .1);
      margin-left: 50%;
      transform: translate(-50%);
    }

    .camera {
      position: absolute;
      width: 60rpx;
      height: 60rpx;
      border-radius: 50%;
      top: 166rpx;
      left: 408rpx;
      background: $theme-color url("../../static/images/white-camera.png") no-repeat center;
      background-size: 31rpx 26rpx;
    }
  }

  .table {
    height: 116rpx;
    width: 100%;
    border-bottom: 1rpx solid #ebe8e8;
    line-height: 116rpx;
    color: $uni-text-color;
    display: flex;
    justify-content: space-between;
    align-items: center;

    .text {
      width: 140rpx;
      font-size: 34rpx;
      font-weight: bold;

    }

    .value {
      width: 300rpx;
      font-size: 30rpx;
      font-weight: bold;
      text-align: right;
      margin-left: auto;
      margin-right: 20rpx;
    }

  }
}

.edit_btn {
  width: 498rpx;
  height: 78rpx;
  background: $theme-color;
  box-shadow: 0 6rpx 12rpx rgba(254, 27, 27, .3);
  border-radius: 48rpx;
  font-size: 36rpx;
  font-weight: bold;
  color: $uni-text-color-inverse;
  margin-top: 50rpx;
  margin-left: 126rpx;
}
</style>