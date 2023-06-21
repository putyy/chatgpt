import { request } from '@/utils/request.js'

/**
 * 图片素材 API JS
 */

export default {

  /**
   * 获取图片素材分页列表
   * @returns
   */
  getList (params = {}) {
    return request({
      url: 'ai/imageMaterial/index',
      method: 'get',
      params
    })
  },

  /**
   * 添加图片素材
   * @returns
   */
  save (data = {}) {
    return request({
      url: 'ai/imageMaterial/save',
      method: 'post',
      data
    })
  },

  /**
   * 更新图片素材数据
   * @returns
   */
  update (id, data = {}) {
    return request({
      url: 'ai/imageMaterial/update/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 读取图片素材
   * @returns
   */
  read (data = {}) {
    return request({
      url: 'ai/imageMaterial/read',
      method: 'get',
      data
    })
  },

  /**
   * 将图片素材删除，有软删除则移动到回收站
   * @returns
   */
  deletes (data) {
    return request({
      url: 'ai/imageMaterial/delete',
      method: 'delete',
      data
    })
  },


}