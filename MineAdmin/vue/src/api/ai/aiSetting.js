import { request } from '@/utils/request.js'

/**
 * 用户主表 API JS
 */

export default {

    /**
     * 获取用户主表分页列表
     * @returns
     */
    getList (params = {}) {
        return request({
            url: 'ai/setting/index',
            method: 'get',
            params
        })
    },

    /**
     * 更新用户主表数据
     * @returns
     */
    save (data = {}) {
        return request({
            url: 'ai/setting/save',
            method: 'post',
            data
        })
    },

    getQiniuToken(scenes= "") {
        let params = {scenes: scenes}
        return request({
            url: 'ai/setting/upload-token',
            method: 'get',
            params
        })
    }
}