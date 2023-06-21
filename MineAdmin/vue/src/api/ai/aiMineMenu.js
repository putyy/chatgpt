import { request } from '@/utils/request.js'

/**
 * 个人中心菜单 API JS
 */

export default {

  /**
   * 获取个人中心菜单分页列表
   * @returns
   */
  getList (params = {}) {
    return request({
      url: 'ai/mineMenu/index',
      method: 'get',
      params
    })
  },

  /**
   * 添加个人中心菜单
   * @returns
   */
  save (data = {}) {
    return request({
      url: 'ai/mineMenu/save',
      method: 'post',
      data
    })
  },

  /**
   * 更新个人中心菜单数据
   * @returns
   */
  update (id, data = {}) {
    return request({
      url: 'ai/mineMenu/update/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 读取个人中心菜单
   * @returns
   */
  read (data = {}) {
    return request({
      url: 'ai/mineMenu/read',
      method: 'get',
      data
    })
  },

  /**
   * 将个人中心菜单删除，有软删除则移动到回收站
   * @returns
   */
  deletes (data) {
    return request({
      url: 'ai/mineMenu/delete',
      method: 'delete',
      data
    })
  },


}