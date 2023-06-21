<template lang="pug">
div.sessions
  div.item(v-for="item in sessions")
    div.user
      image.head(:src="item.user?.head_img? item.user.head_img : '../../static/images/chatgpt.png'")
      span.name {{item.user?.nick_name}}
    div.info
      span.message {{item.first_message.content}}
      span.time {{item.created_at}}
      span.all(@click="loadMessage(item)") 查看全文
uni-popup(ref="popupMessages" type="bottom")
  scroll-view.message_list(@scrolltolower="moreMessage" scroll-y="true")
    template(v-for="item in messageList" )
      div.content_box.user
        image.head(:src="useSessionItem.user.head_img ? useSessionItem.user.head_img : '../../static/images/chatgpt.png'")
        div.message_box
          div.message
            mp-html(v-if="item.content" :markdown="true" :content="item.content" :selectable="true")
          span.time {{item.created_at}}
      div.content_box.ai
        image.head(src="../../static/images/chatgpt.png")
        div.message_box
          div.message
            mp-html(:markdown="true" :content="item.reply_content ? item.reply_content : '...'" :selectable="true")
          span.time {{item.reply_at}}
FooterCommon(:footerIndex="1")
</template>

<script setup lang="ts">
import FooterCommon from '../../components/FooterCommon.vue'
// @ts-ignore
import {ref, onMounted} from 'vue'
import {getAiSessionShareList, getAiSessionShareMessageList} from "../../common/api";
// @ts-ignore
import {onReachBottom} from "@dcloudio/uni-app"

const sessions = ref([])
const isOverSessions = ref(false)
const isLoading = ref(false)
const popupMessages = ref()
const useSessionItem = ref({
  id: 0,
  user: {
    nick_name: '',
    head_img: '',
  }
})
const isOverMessages = ref(false)
const messageList = ref([])

onMounted(()=>{
  getAiSessionShareList({last_id: 0}).then((res: ptAny)=>{
    sessions.value = res.data.list
  })
})

onReachBottom(() => {
  if (sessions.value.length <= 0) {
    return
  }
  if (isOverSessions.value || isLoading.value) {
    return
  }
  getAiSessionShareList({last_id: sessions.value[sessions.value.length - 1].id}).then((res: ptAny) => {
    if (res.data.list.length > 0) {
      sessions.value.push(...res.data.list)
    } else {
      isOverSessions.value = false
    }
  })
})

const loadMessage = (item: any) => {
  if (useSessionItem.value.id != item.id) {
    useSessionItem.value = item
    isOverMessages.value = false
    getAiSessionShareMessageList({sid: useSessionItem.value.id, last_id: 0}).then((res: ptAny) => {
      messageList.value = res.data.list
      popupMessages.value.open()
    })
  } else {
    popupMessages.value.open()
  }
}

const moreMessage = ()=>{
  if (isOverMessages.value){
    return
  }
  getAiSessionShareMessageList({sid: useSessionItem.value.id, last_id: messageList.value[messageList.value.length-1].id}).then((res: ptAny) => {
    if (res.data.list.length <= 0) {
      isOverMessages.value = true
      return
    }
    messageList.value.push(...res.data.list)
  })
}
</script>

<style scoped lang="scss">
.sessions{
  display: flex;
  flex-direction: column;
  padding-bottom: 114rpx;
  width: 100%;
  .item{
    width: 100%;
    display: flex;
    flex-direction: column;
    border-bottom: 1rpx solid #f1f1f1;
    margin: 15rpx auto;
  }
  .info{
    display: flex;
    flex-direction: column;
    margin-left: 80rpx;
    padding: 0 20rpx;
    .message{
      padding: 15rpx 0;
      font-size: 30rpx;
      color: #3F51B5;
    }
    .time{
      font-size: 18rpx;
      color: #c8c7cc;
    }
    .all{
      color: #2196F3;
      padding: 15rpx 0;
    }
  }
  .user{
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 0 20rpx;
    .head {
      width: 70rpx;
      height: 70rpx;
      border-radius: 50%;
      box-shadow: 0 15rpx 30rpx rgba(0, 0, 0, .4);
    }
    .name{
      font-size: 30rpx;
      padding-left: 15rpx;
      max-width: 70%;
      overflow-y: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
  }
}

.message_list{
  max-height: 80vh;
  background-color: white;
  border-radius: 15rpx 15rpx 0 0;
  padding-top: 20rpx;
  .content_box {
    display: flex;
    padding: 15rpx;
    .message_box{
      display: flex;
      flex-direction: column;
      .time{
        color:  #c1c1c1;
        font-size: 20rpx;
        text-align: center;
      }
    }
    .message {
      max-width: 470rpx;
      padding: 0 20rpx;
      margin: 5rpx;
      border-radius: 30rpx;
      font-size: 26rpx;
      box-shadow: 0 15rpx 30rpx rgba(0, 0, 0, .4);
    }

    .head {
      width: 75rpx;
      height: 75rpx;
      border-radius: 50%;
      background: #c1c1c1;
      box-shadow: 0 15rpx 30rpx rgba(0, 0, 0, .4);
    }
  }

  .ai {
    flex-direction: row;

    .message {
      background-color: #e6edf3;
    }
  }

  .user {
    flex-direction: row-reverse;

    .message {
      color: #fff;
      background-color: #8583fd;
    }
  }
}
</style>