import { request } from '@/utils/request.js'

/**
 * chatgpt角色 API JS
 */

export default {

  /**
   * 获取chatgpt角色分页列表
   * @returns
   */
  getList (params = {}) {
    return request({
      url: 'ai/chatgptPrompts/index',
      method: 'get',
      params
    })
  },

  /**
   * 更新chatgpt角色数据
   * @returns
   */
  update (id, data = {}) {
    return request({
      url: 'ai/chatgptPrompts/update/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 添加chatgpt角色
   * @returns
   */
  save (data = {}) {
    return request({
      url: 'ai/chatgptPrompts/save',
      method: 'post',
      data
    })
  },

  /**
   * 读取chatgpt角色
   * @returns
   */
  read (data = {}) {
    return request({
      url: 'ai/chatgptPrompts/read',
      method: 'get',
      data
    })
  },

  /**
   * 将chatgpt角色删除，有软删除则移动到回收站
   * @returns
   */
  deletes (data) {
    return request({
      url: 'ai/chatgptPrompts/delete',
      method: 'delete',
      data
    })
  },


}