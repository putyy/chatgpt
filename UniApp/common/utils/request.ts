class RequestService {
    private before: ptAny[];
    private after: ptAny[];

    constructor() {
        // @ts-ignore
        this.before = []
        this.after = []
    }

    static handleIntercept(handles: ptAny, data: utilsType.requestConfig | ptAny) {
        return handles.reduce((old: ptAny, current: ptAny) => {
            return current(old)
        }, data)
    }

    use(before: ptAny, after: ptAny) {
        typeof before === 'function' && this.before.push(before)
        typeof after === 'function' && this.after.push(after)
    }

    get(url: ptAny, data?: ptAny) {
        return this.request({
            url,
            data,
            method: 'GET'
        })
    }

    post(url: ptAny, data?: ptAny) {
        return this.request({
            url,
            data,
            method: 'POST'
        })
    }

    request(config: utilsType.requestConfig) {
        let _config = RequestService.handleIntercept(this.before, config)
        // @ts-ignore
        return new Promise((resolve, reject) => {
            // @ts-ignore
            uni.request({
                ..._config,
                ...{
                    success: (res: ptAny) => {
                        let response = RequestService.handleIntercept(this.after, res)
                        if (response && response.statusCode === 200) {
                            resolve(response.data)
                        } else {
                            reject(response.data)
                        }
                    },
                    fail: (err: any) => {
                        console.log("fail", err)
                        reject(err)
                    }
                }
            })
        })
    }
}

const request = new RequestService()
export default request