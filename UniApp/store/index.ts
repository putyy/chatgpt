// @ts-ignore
import {defineStore} from 'pinia'
// @ts-ignore
import {ref} from "vue"
import {
    getInit
} from "../common/api"
import {AppInitCacheKey, xTokenCacheKey} from "../common/const"

export const useIndexStore = defineStore('index', () => {
    const scene = ref({ // 场景值
        ads: { // 广告
        },
        agreement: {  // 协议
            user: ''
        },
        upload: {  // 上传
            image : {
                byv_default: "1000",
                byv_head_img: "1002",
                byv_home_back: "1005",
                byv_real_name: "1001",
                byv_wx_er: "1003",
                byv_wx_video_er: "1004",
            },
			domain: '',
        },
        tutorials: { // 教程
        },
    })

    const appInfo = ref({
        copyright: '', // 版权信息
        version: '',
    })

    const customerInfo = ref({ // 客服信息
        customer_id: 0, // id
        head_img: '', // 头像
        mobile: '', // 头像
        user_name: '', // 头像
        work_time: '', // 头像
        wx_img_url: '', // 昵称
        wx_no: '', // 昵称
    })

    const setInit = async ()=>{
        // @ts-ignore
        let initInfo = uni.getStorageSync(AppInitCacheKey)
        if (initInfo) {
            scene.value = initInfo.scene
            customerInfo.value = initInfo.other.customer_info
            appInfo.value.copyright = initInfo.other.copyright
            appInfo.value.version = initInfo.other.version
            return Promise.resolve()
        } else {
            return getInit().then((res: ptAny) => {
                if (res.code === 200) {
                    // @ts-ignore
                    uni.setStorageSync(AppInitCacheKey, res.data)
                    scene.value = res.data.scene
                    customerInfo.value = res.data.other.customer_info
                    appInfo.value.copyright = res.data.other.copyright
                    appInfo.value.version = res.data.other.version
                }
            })
        }
    }

    const authorization = async (isForce?: boolean) => {
        // @ts-ignore
        let xTokenCache = uni.getStorageSync(xTokenCacheKey)
        if (xTokenCache && !isForce) {
            await setInit().then(async (r: ptAny) => {
            })
            return
        }
        // @ts-ignore
        uni.reLaunch({url: "/pages/login"})
    }

    return {
        scene, appInfo,customerInfo,setInit,
        authorization
    }
});