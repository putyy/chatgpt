import { request } from '@/utils/request.js'

/**
 * 聊天数据 API JS
 */

export default {

  /**
   * 获取聊天数据分页列表
   * @returns
   */
  getList (params = {}) {
    return request({
      url: 'ai/chatMessage/index',
      method: 'get',
      params
    })
  },

  /**
   * 读取聊天数据
   * @returns
   */
  read (data = {}) {
    return request({
      url: 'ai/chatMessage/read',
      method: 'get',
      data
    })
  },

  /**
   * 将聊天数据删除，有软删除则移动到回收站
   * @returns
   */
  deletes (data) {
    return request({
      url: 'ai/chatMessage/delete',
      method: 'delete',
      data
    })
  },


}