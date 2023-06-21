import { request } from '@/utils/request.js'

/**
 * openai_key API JS
 */

export default {

  /**
   * 获取openai_key分页列表
   * @returns
   */
  getList (params = {}) {
    return request({
      url: 'ai/openKey/index',
      method: 'get',
      params
    })
  },

  /**
   * 添加openai_key
   * @returns
   */
  save (data = {}) {
    return request({
      url: 'ai/openKey/save',
      method: 'post',
      data
    })
  },

  /**
   * 更新openai_key数据
   * @returns
   */
  update (id, data = {}) {
    return request({
      url: 'ai/openKey/update/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 读取openai_key
   * @returns
   */
  read (data = {}) {
    return request({
      url: 'ai/openKey/read',
      method: 'get',
      data
    })
  },

  /**
   * 将openai_key删除，有软删除则移动到回收站
   * @returns
   */
  deletes (data) {
    return request({
      url: 'ai/openKey/delete',
      method: 'delete',
      data
    })
  },

  batchAdd (data) {
    return request({
      url: 'ai/openKey/batch-add',
      method: 'post',
      data
    })
  },

  refreshCache () {
    return request({
      url: 'ai/openKey/refresh-cache-list',
      method: 'post'
    })
  },

}