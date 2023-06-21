import {UserMineCacheKey} from "../common/const"
import {getMine} from "../common/api"

export function userService() {
    const initMine = async () => {
        // @ts-ignore
        let cache = uni.getStorageSync(UserMineCacheKey)
        if (cache) {
            return cache
        }
        let data = {}
        await getMine().then((res: ptAny) => {
            if (res.code === 200) {
                data = res.data
                // @ts-ignore
                uni.setStorageSync(UserMineCacheKey, res.data)
            }
        })
        return data
    }

    const updateMineUserCache = (data: ptAny) => {
        // @ts-ignore
        let cache = uni.getStorageSync(UserMineCacheKey)
        if (cache) {
            cache.head_img = data.head_img
            cache.nick_name = data.nick_name
            cache.mobile = data.mobile
            // @ts-ignore
            uni.setStorageSync(UserMineCacheKey, cache)
        }
    }

    return {initMine, updateMineUserCache}
}
