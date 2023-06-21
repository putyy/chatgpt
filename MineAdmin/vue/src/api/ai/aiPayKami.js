import { request } from '@/utils/request.js'

/**
 * 卡密 API JS
 */

export default {

    /**
     * 获取卡密分页列表
     * @returns
     */
    getList (params = {}) {
        return request({
            url: 'ai/payKami/index',
            method: 'get',
            params
        })
    },

    /**
     * 读取卡密
     * @returns
     */
    read (data = {}) {
        return request({
            url: 'ai/payKami/read',
            method: 'get',
            data
        })
    },

    /**
     * 更新卡密数据
     * @returns
     */
    update (id, data = {}) {
        return request({
            url: 'ai/payKami/update/' + id,
            method: 'put',
            data
        })
    },

    /**
     * 将卡密删除，有软删除则移动到回收站
     * @returns
     */
    deletes (data) {
        return request({
            url: 'ai/payKami/delete',
            method: 'delete',
            data
        })
    },

    /**
     * 将卡密删除，有软删除则移动到回收站
     * @returns
     */
    add (data) {
        return request({
            url: 'ai/payKami/add',
            method: 'post',
            data
        })
    },
}