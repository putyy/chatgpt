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
      url: 'ai/user/index',
      method: 'get',
      params
    })
  },

  /**
   * 更新用户主表数据
   * @returns
   */
  update (id, data = {}) {
    return request({
      url: 'ai/user/update/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 读取用户主表
   * @returns
   */
  read (data = {}) {
    return request({
      url: 'ai/user/read',
      method: 'get',
      data
    })
  },

  /**
   * 将用户主表删除，有软删除则移动到回收站
   * @returns
   */
  deletes (data) {
    return request({
      url: 'ai/user/delete',
      method: 'delete',
      data
    })
  },


  /**
   * 将用户主表删除，有软删除则移动到回收站
   * @returns
   */
  openVip(id, data) {
    return request({
      url: 'ai/user/open-vip/' + id,
      method: 'post',
      data
    })
  },

  /**
   * 锁定 or 解锁
   * @returns
   */
  lock(id) {
    return request({
      url: 'ai/user/lock/' + id,
      method: 'post'
    })
  },

}