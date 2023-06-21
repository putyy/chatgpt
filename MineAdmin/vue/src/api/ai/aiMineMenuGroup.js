import { request } from '@/utils/request.js'

/**
 * 个人中心菜单分组 API JS
 */

export default {

  /**
   * 获取个人中心菜单分组分页列表
   * @returns
   */
  getList (params = {}) {
    return request({
      url: 'ai/mineMenuGroup/index',
      method: 'get',
      params
    })
  },

  /**
   * 添加个人中心菜单分组
   * @returns
   */
  save (data = {}) {
    return request({
      url: 'ai/mineMenuGroup/save',
      method: 'post',
      data
    })
  },

  /**
   * 更新个人中心菜单分组数据
   * @returns
   */
  update (id, data = {}) {
    return request({
      url: 'ai/mineMenuGroup/update/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 读取个人中心菜单分组
   * @returns
   */
  read (data = {}) {
    return request({
      url: 'ai/mineMenuGroup/read',
      method: 'get',
      data
    })
  },

  /**
   * 将个人中心菜单分组删除，有软删除则移动到回收站
   * @returns
   */
  deletes (data) {
    return request({
      url: 'ai/mineMenuGroup/delete',
      method: 'delete',
      data
    })
  },


}