import { request } from '@/utils/request.js'

/**
 * 订单表 API JS
 */

export default {

  /**
   * 获取订单表分页列表
   * @returns
   */
  getList (params = {}) {
    return request({
      url: 'ai/order/index',
      method: 'get',
      params
    })
  },

  /**
   * 读取订单表
   * @returns
   */
  read (data = {}) {
    return request({
      url: 'ai/order/read',
      method: 'get',
      data
    })
  },

  /**
   * 将订单表删除，有软删除则移动到回收站
   * @returns
   */
  deletes (data) {
    return request({
      url: 'ai/order/delete',
      method: 'delete',
      data
    })
  },


}