type ptAny = any

declare namespace utilsType {
    interface result{
        code: number;
        data: any;
        message: string;
    }

    interface requestConfig {
        url: string
        header?: Record<string, string>
        data?: any
        method: 'GET' | 'POST' | 'PUT' | 'DELETE'
    }

    interface qiniuInfo {
        domain: string
        service_url: string
        scene: string | number
        token: string
        path: string
    }
}