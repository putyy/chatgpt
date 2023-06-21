/**
 * uploadCos.
 * 上传到七牛云
 * @param {any} qiniuInfo 七牛云预生成的信息
 * @param {String} filePath 文件的临时路径
 * @returns {Promise<any>}
 */
export function uploadQiniu(qiniuInfo: utilsType.qiniuInfo, filePath: string) {
    return new Promise(async (resolve, reject) => {
        // @ts-ignore
        await uni.uploadFile({
            url: qiniuInfo.service_url,
            filePath: filePath,
            name: 'file',
            formData: {
                token: qiniuInfo.token,
                key: qiniuInfo.path
            },
            success: (res: ptAny) => {
                resolve(JSON.parse(res.data))
            },
            fail: (err: ptAny) => {
                reject(err)
            }
        })
    })
}