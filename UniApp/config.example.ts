let config = [
    {
        wsUrl: 'ws://开发环境的域名ws/ws-chat',
        baseURL: 'https://开发环境的域名/api/ai/api/'
    },
    {
        wsUrl: 'ws://线上域名/ws/ws-chat',
        baseURL: 'https://线上域名/api/ai/api/'
    }
]

// @ts-ignore
let index = process.env.NODE_ENV === 'development' ? 0 : 1

export const getConfig = () => {
    return config[index]
}