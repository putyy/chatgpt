import ai from "@/api/ai/aiSetting";
import * as qiniu from "qiniu-js";


let uploadQiniu  = (blob,key,token)=>{
    return new Promise(function (resolve, reject) {
        let observable = qiniu.upload(blob, key, token)
        let observer = {
            next (res) {
            },
            error (err) {
                reject(err)
            },
            complete (data) {
                resolve(data)
            }
        }
        observable.subscribe(observer)
    })
}

export const uploadQiniuHandler = async (files) => {
    let sceneArr = []
    for (let dataIndex in files) {
        sceneArr.push(files[dataIndex].ptScene)
    }

    if (sceneArr.length <= 0) {
        return true
    }

    // 获取token
    let sceneResArr = []
    await ai.getQiniuToken(sceneArr.join("-")).then((res)=>{
        sceneResArr = res.data
    })
    for (let key in sceneResArr) {
        for (let dataIndex in files) {
            if (sceneResArr[key].scene === files[dataIndex].ptScene) {
                await uploadQiniu(files[dataIndex].file, sceneResArr[key].path,sceneResArr[key].token).then((res) => {
                    files[dataIndex].source_url = res.url
                })
            }
        }
    }
    return files
}