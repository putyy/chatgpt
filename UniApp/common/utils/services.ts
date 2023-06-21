import Request from './request'
import {AppNoticeCacheKey, xTokenCacheKey} from "../const";
import {useIndexStore} from '../../store'
import {getConfig} from "../config";

Request.use( (config: utilsType.requestConfig) => {
	if (!config.header) {
		config.header = {}
	}
	// @ts-ignore
	let authorization = uni.getStorageSync(xTokenCacheKey)

	config.header['Authorization'] = 'Bearer ' + (authorization ? authorization.token : '')

	if (config.url.slice(0, 8) !== "https://") {
		config.url = getConfig().baseURL + config.url
	}

	return config
},  (response: ptAny) => {
	switch (response.data.code) {
		case 10006: // 参数错误
			// @ts-ignore
			uni.showToast({
				title: response.data.message,
				icon: 'none',
				duration: 1500
			})
			break
		case 10001: // token 无效
			useIndexStore().authorization(true)
			break
		case 10002: // 账号被锁定
			// @ts-ignore
			uni.setStorageSync(AppNoticeCacheKey, response.data)
			// @ts-ignore
			uni.redirectTo({
				url: '/pages/notice'
			})
			break
		case 10003: // 跳转到登录页
			// @ts-ignore
			uni.redirectTo({
				url: '/pages/login'
			})
			break
		case 10004: // 关站维护
			// @ts-ignore
			uni.setStorageSync(AppNoticeCacheKey, response.data)
			// @ts-ignore
			uni.redirectTo({
				url: '/pages/notice'
			})
			break
		case 10005: // 清空所有缓存数据
			// @ts-ignore
			uni.clearStorageSync()
			break
	}
	return response
})

export default Request
