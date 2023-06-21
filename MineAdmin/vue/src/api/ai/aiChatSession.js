import { request } from '@/utils/request.js'

/**
 * 问答会话 API JS
 */

export default {

  /**
   * 获取问答会话分页列表
   * @returns
   */
  getList (params = {}) {
    return request({
      url: 'ai/chatSession/index',
      method: 'get',
      params
    })
  },

  /**
   * 读取问答会话
   * @returns
   */
  read (data = {}) {
    return request({
      url: 'ai/chatSession/read',
      method: 'get',
      data
    })
  },

  /**
   * 将问答会话删除，有软删除则移动到回收站
   * @returns
   */
  deletes (data) {
    return request({
      url: 'ai/chatSession/delete',
      method: 'delete',
      data
    })
  },


}