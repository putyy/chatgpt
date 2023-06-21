import { request } from '@/utils/request.js'

/**
 * 快捷问题
 API JS
 */

export default {

  /**
   * 获取快捷问题
分页列表
   * @returns
   */
  getList (params = {}) {
    return request({
      url: 'ai/quickIssue/index',
      method: 'get',
      params
    })
  },

  /**
   * 更新快捷问题
数据
   * @returns
   */
  update (id, data = {}) {
    return request({
      url: 'ai/quickIssue/update/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 添加快捷问题

   * @returns
   */
  save (data = {}) {
    return request({
      url: 'ai/quickIssue/save',
      method: 'post',
      data
    })
  },

  /**
   * 读取快捷问题

   * @returns
   */
  read (data = {}) {
    return request({
      url: 'ai/quickIssue/read',
      method: 'get',
      data
    })
  },

  /**
   * 将快捷问题
删除，有软删除则移动到回收站
   * @returns
   */
  deletes (data) {
    return request({
      url: 'ai/quickIssue/delete',
      method: 'delete',
      data
    })
  },


}