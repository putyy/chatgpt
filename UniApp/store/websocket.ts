// @ts-ignore
import {defineStore} from 'pinia'
// @ts-ignore
import {ref} from "vue"
import {getConfig} from '../common/config'
import { xTokenCacheKey } from '../common/const'

export const useWebsocketStore = defineStore('websocket', () => {
    // @ts-ignore
    const websocketTask = ref<UniNamespace.SocketTask>()
    const heartbeatTime = ref(0)
    const isOpenSocket = ref(false)
    const isReconnect = ref(true)
    const onMessageHandles = ref({
        ping: (res: ptAny) => {
            console.log("websocket-ping", res)
        },
        opened: (res: ptAny) => {
            console.log("websocket-opened", res)
        },
        close: (res: ptAny) => {
            console.log("websocket-close", res)
        },
        error: (res: ptAny) => {
            console.log("websocket-error", res)
        },
        login: (res: ptAny) => {
            // @ts-ignore
            uni.reLaunch({url: "/pages/login"})
        },
    })

    const websocketInit = () => {
        if (isOpenSocket.value) {
            return
        }
        // @ts-ignore
		let authorization = uni.getStorageSync(xTokenCacheKey)
        // @ts-ignore
		websocketTask.value = uni.connectSocket({
			url: getConfig().wsUrl + "?token=" + (authorization ? authorization.token : ''),
			complete: () => {
			}
		})

        websocketTask.value.onOpen((res: ptAny) => {
            heartbeatTime.value && clearInterval(heartbeatTime.value)
            isOpenSocket.value = true
            heartbeatTime.value = setInterval(() => {
                websocketTask.value.send({data: "{\"type\":\"ping\"}"});
            }, 55000)
        })
		
		websocketTask.value.onMessage((res: ptAny) => {
		  let data = JSON.parse(res.data)
		  if(onMessageHandles.value.hasOwnProperty(data.type)){
              onMessageHandles.value[data.type](data)
		  }else{
              console.log("找不到onMessageHandles")
          }
		})

        websocketTask.value.onClose(() => {
            isOpenSocket.value = false
            isReconnect.value && reconnect();
        })
    }

    const reconnect = () => {
        websocketTask.value.close({})
        if (!isOpenSocket.value) {
            setTimeout(() => {
                websocketInit();
            }, 2000)
        }
    }

    const send = (data: ptAny) => {
        websocketTask.value.send({data:JSON.stringify(data)})
    }

    const close = () => {
        websocketTask.value.close({})
        isOpenSocket.value = false
        websocketTask.value = null
    }

    const bindMessageHandle = (handle: ptAny)=>{
		onMessageHandles.value[handle.type] = handle.event
	}
	
    return {
        websocketInit, send, close, bindMessageHandle
    }
})