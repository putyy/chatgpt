<template lang="pug">
div.room
  div.message_list
    template(v-for="item in messageList" )
      div.content_box.user
        image.head(:src="user.head ? user.head : '../../static/images/chatgpt.png'")
        div.message_box
          div.message
            mp-html(v-if="item.content" :markdown="true" :content="item.content" :selectable="true")
            div.dot_flashing(v-if="!item.content")
          span.time {{item.created_at}}
      div.content_box.ai
        image.head(src="../../static/images/chatgpt.png")
        div.message_box
          div.message
            mp-html(v-if="item.reply_content" :markdown="true" :content="item.reply_content" :selectable="true")
            div.dot_flashing(v-if="!item.reply_content")
          span.time {{item.reply_at}}
  div.fast
    div.action(:class="isContext ? 'choose' : ''" @click="setContext()") 上下文
    div.action(@click="showPopupIssue") 快 问
  div.send_line
    div.more
      image.icon(src="../../static/images/more.png" @tap="showMore")
    div.input_message
      uni-easyinput(type="textarea" v-model="messageContent" placeholder="请输入内容")
    div.send
      uni-icons.icon(v-if="!isHistoryClose" @click="send" type="paperplane" size="35" color="rgb(85 170 239)")
      uni-icons.icon(v-if="isHistoryClose" type="paperplane" size="35" color="#c1c1c1")
uni-popup(ref="popupMore" type="bottom")
  div.popup_more
    div.item
      span.icon
        uni-icons( type="folder-add" size="35" color="rgb(85 170 239)" @click="newSession")
      span.text 新建会话
    div.item
      span.icon
        uni-icons( type="refresh" size="35" color="rgb(85 170 239)" @click="resetSession")
      span.text 重置会话
    div.item
      span.icon
        uni-icons( type="paperplane" size="35" color="rgb(85 170 239)" @click="shareSession(null, true)")
      span.text 分享会话
    div.item
      span.icon
        uni-icons( type="list" size="35" color="rgb(85 170 239)"  @click="historySession")
      span.text 历史会话
    div.item
      span.icon
        uni-icons( type="gear" size="35" color="rgb(85 170 239)"  @click="setModel(1)")
      span.text 模型设置
    div.item
      span.icon
        uni-icons( type="vip" size="35" color="rgb(85 170 239)"  @click="showOpenVip")
      span.text 解锁VIP
    div.item
      span.icon
        uni-icons( type="medal" size="35" color="rgb(85 170 239)" @click="byRedirectTo('/pages/chatgpt/channel')")
      span.text 公开频道
    div.item
      span.icon
        uni-icons( type="home" size="35" color="rgb(85 170 239)" @click="byRedirectTo('/pages/user/mine')")
      span.text 个人中心
uni-popup(ref="popupIssue" type="center")
  scroll-view.issue_scroll(scroll-y="true")
    div.issue(v-for="issue in quickIssueList")
      div.info
        span.title {{issue.title}}
        span.content {{issue.content}}
      div.action(@click="chooseIssue(issue)") 使用
uni-popup(ref="popupSetModel" type="bottom")
  div.set_model
    uni-forms-item( label="模型名称")
      uni-data-select.select(v-model="modelCache['index']" :localdata="modelList" :clear="false")
      span.desc {{modelList[modelCache.index]?.max_tokens_text}}
    uni-forms-item( label="上下文长度")
      uni-number-box(:min="1" :max="6" v-model="modelCache['context_length']" :step="3")
    uni-forms-item( label="温  度")
      uni-number-box(:min="0" :max="1" v-model="modelCache['temperature']" :step="0.1")
      span.desc 较高的温度会导致更多种类和不可预测的输出）
    uni-forms-item( label="重  复")
      uni-number-box(:min="0" :max="1" v-model="modelCache['frequency_penalty']" :step="0.1")
      span.desc 减少总体上使用频率较高的单词/短语的概率
    uni-forms-item( label="概  率")
      uni-number-box(:min="-2" :max="2" v-model="modelCache['presence_penalty']" :step="0.1")
      span.desc 减少总体上使用频率较高的单词/短语的概率
    button.save_btn(type="primary" size="mini" @click="setModel(2)") 确定
uni-drawer(ref="popupHistory" mode="left" :width="320")
  scroll-view.history_drawer(@scrolltolower="moreHistorySession" scroll-y="true")
    div.session_list
      div.item(v-for="(item, key) in historySessionList" :class="item.id === messageListParam.data.sid ? 'choose': ''")
        div.info
          span.first_message {{item?.first_message?.content}}
          span.created_at {{item?.created_at}}
            span.close {{item?.close===2 ? "已关闭" : ""}}
        div.actions(v-show="item.id !== messageListParam.data.sid" )
          span.action(@click="chooseHistorySession(item)") 进入
          span.action(@click="shareSession(item)") {{item.share === 1 ? '分享' : '取消'}}
          span.action(@click="deleteHistorySession(item)") 删除
uni-drawer(ref="popupNewSession" mode="left" :width="320")
  div.choose_search
    div.content
      input.keyword(placeholder-class="remind" type="text" placeholder="搜索内容" v-model="keywords" confirm-type="search")
      uni-icons.icon_search(type="search" size="30" color="#4d86b5" @tap="confirmSearch")
  scroll-view.roles_scroll(:scroll-top="scrollTop" scroll-y="true")
    div.roles
      div.role(v-for="role in roles")
        span.title {{role.act}}
        span.desc {{role.prompt}}
        span.use_model(@click="chooseRole(role)")  使用
open-vip(:isShow="isShowOpenVip" @close="isShowOpenVip=false")
</template>

<script setup lang="ts">
// @ts-ignore
import {ref, onMounted, nextTick, reactive} from 'vue'
// @ts-ignore
import {onPullDownRefresh, onReachBottom} from "@dcloudio/uni-app"
import {
  AiBaseCacheKey, AiModelCacheKey, AiModelListCacheKey,
  AiQuickIssueCacheKey,
  AiRolesCacheKey,
  xTokenCacheKey
} from '../../common/const'
import {byRedirectTo} from "../../common/utils/jump"
import {getConfig} from "../../common/config"
import {
  getAiRoles,
  getAiMessages,
  getAiSession,
  postAiSessionClose,
  getAiQuickIssue,
  getAiSessionHistory, postAiSessionShare, postAiSessionDelete, getAiModelList
} from '../../common/api'
import {useWebsocketStore} from '../../store/websocket'
import {userService} from "../../logic/user"
import OpenVip from "../../components/OpenVipPopup.vue"

const {initMine} = userService()

// 输入内容
const messageContent = ref('')
// 消息列表
const messageList = ref<{ content: string, created_at: string, reply_content: string, reply_at: string}[]>([])

const loadMessage = ref({
  // 上一页是否加载完
  prev: false,
  next: false,
  isLoading: false
})
const messageListParam = reactive({
  data: {
    slide: 'next',
    sid: 0,
    last_id: 0
  },
})

const modelData = ref({
  isCache: false,
  index: 0,
  temperature: 0,
  frequency_penalty: 0,
  presence_penalty: 0,
  context_length: 3,
})

// 更多操作
const popupMore = ref()
// 历史会话
const popupHistory = ref()
// 角色弹窗 新建会话
const popupNewSession = ref()
// 快捷问题弹窗
const popupIssue = ref()
// 角色列表
const roles = ref([])
const rolesCp = ref([])
// websocket句柄
const websocketStore = useWebsocketStore()
// 是否显示VIP弹窗
const isShowOpenVip = ref(false)
// 基础缓存
const baseInfoCache = ref()
// 用户基础信息
const user = ref({
  head: "",
  name: "",
  userUid: 0,
  vip: 0,
})
// 角色搜索
const keywords = ref("")
// 角色内容滑块
const scrollTop = ref(0)
// 定时器 每3秒计算展示底部
const isScrollTopTime = ref(0);
// 是否启动上下文
const isContext = ref(false)
// 快捷问题列表
const quickIssueList = ref([])
// 是否发送中
const isSend = ref(false)
// 是否第第一次下拉
const isInitMoreMessage = ref(false)

// 是否处于已关闭的历史对话
const isHistoryClose = ref(false)
const isLastHistory = ref(false)

const historySessionList = ref([])


const popupSetModel = ref()
const modelCache = ref({
  index: 0,
  temperature: 0,
  frequency_penalty: 0,
  presence_penalty: 0,
  context_length: 3,
})

const modelList = ref<{ name: string, is_vip: boolean, max_tokens: number, max_tokens_text: string, temperature: number, frequency_penalty: number, presence_penalty: number, context_length: number }[]>([])

onMounted(async () => {
  let userCache: ptAny = await initMine()
  if (userCache) {
    user.value.head = userCache?.head_img
    user.value.name = userCache?.nick_name
    user.value.userUid = userCache?.uid
    user.value.vip = userCache?.vip
  }

  let modelCacheTemp = uni.getStorageSync(AiModelCacheKey)
  if (modelCacheTemp) {
    modelData.value = Object.assign({}, modelData.value, modelCacheTemp)
    modelData.value.isCache = true
  }

  // todo 缓存角色ID 会话id
  // @ts-ignore
  baseInfoCache.value = uni.getStorageSync(AiBaseCacheKey)
  if (baseInfoCache.value.sid) {
    messageListParam.data.sid = baseInfoCache.value.sid
    getAiMessages(messageListParam.data).then((res: ptAny) => {
      messageList.value = res.data.list
    })
  } else {
    await getAiSession({prompt_id: baseInfoCache.value.prompt_id ? baseInfoCache.value.prompt_id : 0}).then((session: ptAny) => {
      baseInfoCache.value = {sid: session.data.sid, prompt_id: session.data.prompt_id}
      // @ts-ignore
      uni.setStorageSync(AiBaseCacheKey, baseInfoCache.value)
      if (!session.data.is_new) {
        messageListParam.data.sid = baseInfoCache.value.sid
        getAiMessages(messageListParam.data).then((res: ptAny) => {
          messageList.value = res.data.list
        })
      }
    })
  }

  // @ts-ignore
  let cache = uni.getStorageSync(AiRolesCacheKey)
  if (cache) {
    roles.value = cache
    rolesCp.value = cache
    for (let key in rolesCp.value) {
      if (rolesCp.value[key].id === baseInfoCache.value.prompt_id) {
        // @ts-ignore
        uni.setNavigationBarTitle({
          title: "角色:" + rolesCp.value[key].act
        });
        break;
      }
    }
  } else {
    getAiRoles().then((res: ptAny) => {
      if (res.code === 200) {
        // @ts-ignore
        uni.setStorageSync(AiRolesCacheKey, res.data.list)
        roles.value = res.data.list
        rolesCp.value = res.data.list
        for (let key in rolesCp.value) {
          if (rolesCp.value[key].id === baseInfoCache.value.prompt_id) {
            // @ts-ignore
            uni.setNavigationBarTitle({
              title: "角色:" + rolesCp.value[key].act
            });
            break;
          }
        }
      }
    })
  }

  try {
    websocketStore.bindMessageHandle({
      type: "message",
      event: (res: ptAny) => {
        let l = messageList.value.length - 1
        messageList.value[l].reply_content += res.content
        if (res.status) {
          isSend.value = false
          isScrollTopTime.value && clearInterval(isScrollTopTime.value)
          setTimeout(() => {
            scrollTopButtom()
          }, 100)
        }
      }
    })

    websocketStore.bindMessageHandle({
      type: "error",
      event: (res: ptAny) => {
        isSend.value = false
        isScrollTopTime.value && clearInterval(isScrollTopTime.value)
        // @ts-ignore
        uni.showToast({
          title: res.content,
          icon: 'none',
          duration: 1500
        })
      }
    })

    websocketStore.websocketInit()
  } catch (e) {
    // @ts-ignore
    uni.showToast({
      title: '初始化失败，请重试！',
      icon: 'none',
      duration: 1500
    })
  }
})

nextTick(() => {
  setTimeout(() => {
    scrollTopButtom()
  }, 500)
})

onPullDownRefresh(() => {
  if (isInitMoreMessage.value) {
    moreMessage("prev")
  } else {
    // @ts-ignore
    uni.stopPullDownRefresh()
    isInitMoreMessage.value = true
  }
})

onReachBottom(() => {
  if (isInitMoreMessage.value) {
    moreMessage("next")
  } else {
    isInitMoreMessage.value = true
  }
})

const setContext = () => {
  if (user.value.vip === 0) {
    // @ts-ignore
    uni.showToast({
      title: '连接上下文可以获得更好的聊天体验，目前只会连接最近4条消息，只针对付费用户使用！',
      icon: 'none',
      duration: 4500
    })
    return
  }
  isContext.value = !isContext.value
}

const moreMessage = (slide: string) => {
  if (slide === "prev" && loadMessage.value.prev) {
    // @ts-ignore
    uni.stopPullDownRefresh()
    return
  }
  if (slide === "next" && loadMessage.value.next) {
    return
  }

  if (loadMessage.value.isLoading) {
    // @ts-ignore
    slide === "prev" && uni.stopPullDownRefresh()
    return
  }
  loadMessage.value.isLoading = true
  if (slide === "next") {
    let last_id = messageList.value[messageList.value.length - 1]?.id
    if (!last_id) {
      // 属于新建聊天内容 已经是最后一条
      loadMessage.value.next = true
      loadMessage.value.isLoading = false
      return
    }
    messageListParam.data.last_id = last_id
  } else {
    messageListParam.data.last_id = messageList.value.length > 0 ? messageList.value[0].id : 0
  }

  messageListParam.data.slide = slide
  getAiMessages(messageListParam.data).then((res: ptAny) => {
    if (res.data.list.length > 0) {
      if (slide === "next") {
        messageList.value.push(...res.data.list)
      } else {
        messageList.value = res.data.list.concat(messageList.value)
      }
    } else {
      loadMessage.value[slide] = true
    }
    loadMessage.value.isLoading = false
    // @ts-ignore
    slide === "prev" && uni.stopPullDownRefresh()
  })
}

const newSession = () => {
  popupNewSession.value.open()
}

const chooseIssue = (issue: ptAny) => {
  messageContent.value = issue.content
  popupIssue.value.close()
}

const setModel = async (mark: number) => {
  if (1 === mark) {
    if (modelList.value.length === 0){
      // @ts-ignore
      let cache = uni.getStorageSync(AiModelListCacheKey)
      if (!cache) {
        await getAiModelList().then((res: ptAny) => {
          cache = res.data
          // @ts-ignore
          uni.setStorageSync(AiModelListCacheKey, cache)
        })
      }
      modelList.value = cache
    }

    if (modelData.value.isCache) {
      modelCache.value = Object.assign({}, modelCache.value, modelData.value)
    } else {
      modelCache.value = Object.assign({}, modelCache.value, modelList.value[modelData.value.index])
    }
    popupSetModel.value.open()
    return
  }

  if (modelList.value[modelData.value.index].is_vip && !user.value.vip) {
    // @ts-ignore
    uni.showToast({
      title: '该模型只有VIP才能使用哦！',
      icon: 'none',
      duration: 4500
    })
    return
  }
  // @ts-ignore
  uni.setStorageSync(AiModelCacheKey, modelCache.value)
  for (let prop in modelData.value) {
    if (modelCache.value.hasOwnProperty(prop)) {
      modelData.value[prop] = modelCache.value[prop];
    }
  }

  popupMore.value.close()
  popupSetModel.value.close()
}

const showOpenVip = () => {
  isShowOpenVip.value = true
  popupMore.value.close()
}

const showPopupIssue = () => {
  if (quickIssueList.value.length > 0) {
    popupIssue.value.open()
    return
  }
  // @ts-ignore
  let cache = uni.getStorageSync(AiQuickIssueCacheKey)
  if (cache) {
    quickIssueList.value = cache
    popupIssue.value.open()
    return
  }
  getAiQuickIssue().then((res: ptAny) => {
    quickIssueList.value = res.data.list
    popupIssue.value.open()
    // @ts-ignore
    uni.setStorageSync(AiQuickIssueCacheKey, quickIssueList.value)
  })
}

const resetSession = () => {
  if (isHistoryClose.value) {
    // @ts-ignore
    uni.showToast({
      title: '历史会话无法重置',
      icon: 'none',
      duration: 1500
    })
    return
  }
  // @ts-ignore
  uni.showModal({
    title: '重置会话',
    content: '重置会话会将当前会话存入历史记录，然后根据当前所选角色重新创建一个新的会话',
    success:  (res: ptAny)=>{
      if (res.confirm) {
        postAiSessionClose({sid: baseInfoCache.value.sid, prompt_id: baseInfoCache.value.prompt_id,}).then((res: ptAny) => {
          if(res.code === 200) {
            messageList.value = []
            messageListParam.data.sid = res.data.sid
            messageListParam.data.last_id = 0
            baseInfoCache.value = {sid: res.data.sid, prompt_id: baseInfoCache.value.prompt_id}
            // @ts-ignore
            uni.setStorageSync(AiBaseCacheKey, baseInfoCache.value)
            popupMore.value.close()
          }
        })
      } else if (res.cancel) {
      }
    }
  });

}

const chooseRole = (role: ptAny) => {
  // 查找会话
  getAiSession({prompt_id: role.id}).then((session: ptAny) => {
    // @ts-ignore
    uni.setNavigationBarTitle({
      title: "角色:" + role.act
    });

    baseInfoCache.value = {sid: session.data.sid, prompt_id: session.data.prompt_id}
    // @ts-ignore
    uni.setStorageSync(AiBaseCacheKey, baseInfoCache.value)
    if (session.data.is_new) {
      messageList.value = []
    } else {
      messageListParam.data.sid = baseInfoCache.value.sid
      messageListParam.data.slide = "next"
      messageListParam.data.last_id = 0
      getAiMessages(messageListParam.data).then((res: ptAny) => {
        loadMessage.value.next = false
        loadMessage.value.prev = false
        messageList.value = res.data.list
      })
    }
    isHistoryClose.value = false
    popupNewSession.value.close()
    popupMore.value.close()
  })
}

const historySession = () => {
  if (historySessionList.value.length) {
    popupHistory.value.open()
    return
  }
  getAiSessionHistory({}).then((res: ptAny) => {
    historySessionList.value = res.data.list
    popupHistory.value.open()
  })
}

const moreHistorySession = () => {
  // 加载更多
  if (isLastHistory.value) {
    return
  }
  let last_id = 0
  if (historySessionList.value.length > 0) {
    last_id = historySessionList.value[historySessionList.value.length - 1].id
  }
  getAiSessionHistory({last_id: last_id}).then((res: ptAny) => {
    if (res.data.list.length > 0) {
      historySessionList.value.push(...res.data.list)
    } else {
      isLastHistory.value = true
    }
  })
}

const chooseHistorySession = (item: ptAny) => {
  if (item.close === 2) {
    isHistoryClose.value = true
  } else {
    baseInfoCache.value = {sid: item.id, prompt_id: item.prompt_id}
    // @ts-ignore
    uni.setStorageSync(AiBaseCacheKey, baseInfoCache.value)
    isHistoryClose.value = false
  }
  for (let key in rolesCp.value) {
    if (rolesCp.value[key].id === item.prompt_id) {
      // @ts-ignore
      uni.setNavigationBarTitle({
        title: "角色:" + rolesCp.value[key].act
      });
      break;
    }
  }
  messageListParam.data.sid = item.id
  messageListParam.data.slide = "next"
  messageListParam.data.last_id = 0
  getAiMessages(messageListParam.data).then((res: ptAny) => {
    loadMessage.value.next = false
    loadMessage.value.prev = false
    messageList.value = res.data.list
    popupMore.value.close()
    popupHistory.value.close()
    setTimeout(() => {
      scrollTopButtom()
    }, 100)
  })
}

const deleteHistorySession = (item: ptAny) => {
  // @ts-ignore
  uni.showModal({
    title: '删除会话',
    content: '删除会话会删除当前选择的会话，一旦删除将无法找回!',
    success: (res: ptAny) => {
      if (res.confirm) {
        postAiSessionDelete({id: item.id}).then((res: ptAny) => {
          if (res.code === 200 && historySessionList.value.length > 0) {
            historySessionList.value = historySessionList.value.filter((i: ptAny) => {
              return i.id != item.id
            })
          }
        })

      } else if (res.cancel) {
      }
    }
  })
}

const shareSession = (item: ptAny, isShare: false) => {
  // @ts-ignore
  uni.showModal({
    title: '分享会话',
    content: '将会推送到公开频道!',
    success: (res: ptAny) => {
      if (res.confirm) {
        let id = item?.id ? item?.id : baseInfoCache.value.sid
        // todo
        postAiSessionShare({id: id, is_share: isShare}).then((res: ptAny) => {
          if (res.code === 200 && historySessionList.value.length > 0) {
            // 更改session列表中的状态
            for (let key in historySessionList.value) {
              if (historySessionList.value[key].id === id) {
                historySessionList.value[key].share = historySessionList.value[key].share === 1 ? 2 : 1
                break;
              }
            }
          }
        })

      } else if (res.cancel) {
      }
    }
  })
}

const confirmSearch = () => {
  if (!keywords.value) {
    roles.value = rolesCp.value
    return
  }
  let res = []
  for (let key in rolesCp.value) {
    if ((new RegExp(keywords.value, 'i')).test(rolesCp.value[key].prompt)) {
      res.push(rolesCp.value[key])
    }
  }
  roles.value = res
}

const showMore = () => {
  popupMore.value.open()
}

const send = () => {
  if (isHistoryClose.value) {
    // @ts-ignore
    uni.showToast({
      title: '当前处于历史会话哦！',
      icon: 'none',
      duration: 1500
    })
    return
  }
  if (isSend.value) {
    // @ts-ignore
    uni.showToast({
      title: '上一次提问还未结束，请等待！',
      icon: 'none',
      duration: 1500
    })
    return
  }

  if (!messageContent.value || messageContent.value.length < 5) {
    // @ts-ignore
    uni.showToast({
      title: '内容过短！',
      icon: 'none',
      duration: 1500
    })
    return
  }

  if (messageContent.value && messageContent.value.length > 1000) {
    // @ts-ignore
    uni.showToast({
      title: '内容过长！',
      icon: 'none',
      duration: 1500
    })
    return
  }

  let context = isContext.value ? messageList.value.slice(-modelData.value.context_length) : []

  isSend.value = true
  messageList.value.push({
    content: messageContent.value,
    reply_content: '',
    created_at: '',
    reply_at: '',
  })

  websocketStore.send({
    type: 'message',
    prompt_id: baseInfoCache.value.prompt_id,
    sid: baseInfoCache.value.sid,
    content: messageContent.value,
    context: context,
    model: modelData.value
  })

  messageContent.value = ""
  isScrollTopTime.value && clearInterval(isScrollTopTime.value)
  isScrollTopTime.value = setInterval(() => {
    scrollTopButtom()
  }, 1000)
}

const scrollTopButtom = () => {
  // @ts-ignore
  let query = uni.createSelectorQuery()
  query.select('.message_list').boundingClientRect()
  query.select('.send_line').boundingClientRect()
  query.exec((res: ptAny) => {
    // @ts-ignore
    uni.pageScrollTo({
      duration: 100,// 过渡时间
      scrollTop: res[0].bottom + res[0].height + res[1].height + res[1].bottom + 1000,// 滚动的实际距离
    })
  })
}

const send1 = () => {
  // @ts-ignore
  let authorization = uni.getStorageSync(xTokenCacheKey)
  let eventSource = new EventSource(getConfig().baseURL + 'test');
  let msg = ""
  let index = messageList.value.push({
    id: 1,
    user_id: 0,
    content: ""
  }) - 1

  eventSource.onmessage = (e: ptAny) => {
    let data = JSON.parse(e.data);
    if (data.status === 0) {
      eventSource.close();
    } else if (data.status === 1) {
      msg += data.content
      messageList.value[index].content += data.content
    } else if (data.status === 2) {
      eventSource.close();
    }
  };
  eventSource.onerror = (e: ptAny) => {
    console.log("onerror", e);
    eventSource.close();
  };

}
</script>

<style scoped lang="scss">
:deep(.open_vip .uni-table) {
  min-width: unset !important;
}

:deep(.open_vip .uni-table-th) {
  font-size: 23rpx;
}

:deep(.uni-easyinput__content-textarea) {
  height: 92rpx !important;
  min-height: 92rpx !important;
}

:deep(.roles_scroll .uni-scroll-view-content) {
  width: 100% !important;
  height: 90vh !important;
  display: flex !important;
  flex-direction: row !important;
  flex-wrap: wrap !important;
  justify-content: center !important;
  align-content: flex-start !important;
}

.dot_flashing {
  margin: 15rpx 40rpx;
  position: relative;
  width: 10px;
  height: 10px;
  border-radius: 5px;
  background-color: #9880ff;
  color: #9880ff;
  animation: dotFlashing 1s infinite linear alternate;
  animation-delay: .5s;
}

.dot_flashing::before, .dot_flashing::after {
  content: '';
  display: inline-block;
  position: absolute;
  top: 0;
}

.dot_flashing::before {
  left: -15px;
  width: 10px;
  height: 10px;
  border-radius: 5px;
  background-color: #9880ff;
  color: #9880ff;
  animation: dotFlashing 1s infinite alternate;
  animation-delay: 0s;
}

.dot_flashing::after {
  left: 15px;
  width: 10px;
  height: 10px;
  border-radius: 5px;
  background-color: #9880ff;
  color: #9880ff;
  animation: dotFlashing 1s infinite alternate;
  animation-delay: 1s;
}

@keyframes dotFlashing {
  0% {
    background-color: #9880ff;
  }
  50%,
  100% {
    background-color: #ebe6ff;
  }
}

.message_list {
  width: 100%;
  padding-bottom: 160rpx;
  //max-height: 100vh;

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
      display: flex;
      align-items: center;
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

.fast {
  position: fixed;
  left: 0;
  z-index: 5;
  width: auto;
  bottom: 160rpx;

  .action {
    font-size: 23rpx;
    padding: 5rpx;
    margin: 10rpx 0;
    background-color: #c1c1c1;
    border-radius: 0 15rpx 15rpx 0;
    opacity: 0.7;
  }

  .choose {
    color: red;
  }
}

.send_line {
  width: 100%;
  position: fixed;
  bottom: 0;
  display: flex;
  flex-direction: row;
  padding: 15rpx 0;
  align-items: center;
  justify-content: center;
  background-color: aliceblue;

  .input_message {
    width: calc(100% - 215rpx);
  }

  .more {
    padding: 10rpx;

    .icon {
      width: 80rpx;
      height: 80rpx;
    }
  }

  .send {
    width: 80rpx;
    padding: 10rpx;
  }
}

.popup_more {
  background-color: #f1f1f1;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: space-between;
  border-radius: 30rpx 30rpx 0 0;
  padding: 20rpx;

  .item {
    display: flex;
    flex-direction: column;
    align-items: center;
    border: 1rpx solid #e5e4e7;
    border-radius: 15rpx;
    padding: 15rpx;
    margin: 15rpx;

    .text {
      font-size: 24rpx;
      transform: scale(.83);
      transform-origin: center;
      color: #282626;
    }
  }
}

.issue_scroll {
  max-height: 70vh;
  display: flex;
  flex-direction: column;
  background-color: #f6f6f7;
  border-radius: 15rpx;
  width: 80%;
  margin: auto;
  padding: 10rpx 0;
  .issue {
    padding: 20rpx;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    background-color: #fff;
    margin: 10px;
    border-radius: 15rpx;

    .info {
      width: 80%;
      display: flex;
      flex-direction: column;
    }

    .title {
      font-size: 26rpx;
      font-weight: bold;
    }

    .content {
      font-size: 20rpx;
      color: #999999;
    }

    .action {
      color: #2196F3;
    }
  }
}

.roles {
  width: 100%;
  height: 90vh;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: center;
  align-content: flex-start;

  .role {
    margin: 10rpx;
    width: 40%;
    display: flex;
    flex-direction: column;
    background-color: #8583fd;
    padding: 15rpx;
    border-radius: 20rpx;
    justify-content: space-evenly;
    height: auto;

    .title {
      color: #fff;
      font-size: 29rpx;
    }

    .desc {
      color: #fff;
      font-size: 23rpx;
      padding: 10rpx 0 5rpx 0;
      overflow-x: hidden;
    }

    .use_model {
      color: #FFC107;
      margin: 5px 0 0 0;
      text-align: center;
      border: 1rpx solid #fff;
    }
  }
}

.choose_search {
  display: flex;
  align-items: center;
  position: sticky;
  padding: 0 30rpx;
  top: 0;
  z-index: 2;
  height: 10vh;
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

  .keyword {
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

.history_drawer {
  background-color: #f6f6f7;
  max-height: 100vh;
  padding: 10rpx 0;

  .session_list {
    display: flex;
    flex-direction: column;

    .choose {
      background-color: #cce0ef !important;
    }

    .item {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      padding: 15rpx 30rpx;
      margin: 15rpx 0;
      background-color: #fff;
    }

    .info {
      display: flex;
      flex-direction: column;
      width: 80%;
    }

    .first_message {
      font-size: 26rpx;
      font-weight: bold;
      padding: 5rpx;
    }

    .created_at {
      font-size: 20rpx;
      color: #999999;
      padding: 5rpx;
    }

    .close {
      padding-left: 10rpx;
      color: #f0ad4e;
    }

    .actions {
      color: #2196F3;
      display: flex;
      flex-direction: column;

      .action {
        margin: 3rpx;
      }
    }
  }
}

:deep(.set_model .uni-forms-item){
  padding: 0 15rpx;
  margin-bottom: 5rpx;
}
:deep(.set_model .uni-forms-item__label){
  width: 160rpx!important;
}

.set_model{
  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
  justify-content: space-between;
  border-radius: 30rpx 30rpx 0 0;
  width: 100%;
  background: $uni-text-color-inverse;
  padding-top: 30rpx;
  .desc{
    font-size: 20rpx;
    color: red;
  }
  .save_btn{
    width: 60%;
    height: 78rpx;
    background: $theme-color;
    box-shadow: 0 6rpx 12rpx rgba(254, 27, 27, .3);
    border-radius: 48rpx;
    font-size: 36rpx;
    font-weight: bold;
    color: $uni-text-color-inverse;
    margin-left: 20%;
    margin-top: 25rpx;
    margin-bottom: 25rpx;
  }
}
</style>