export const byNavigateTo = (page: string, params?: ptAny) => {
    // @ts-ignore
    uni.navigateTo({
        url: page
    }).then((r: ptAny) => {
    })
}

export const byRedirectTo = (page: string, params?: ptAny) => {
    // @ts-ignore
    uni.redirectTo({
        url: page
    }).then((r: ptAny) => {
    })
}

export const byReLaunch = (page: string, params?: ptAny) => {
    // @ts-ignore
    uni.reLaunch({
        url: page
    }).then((r: ptAny) => {
    })
}

export const byNavigateBack = (delta: number) => {
    // @ts-ignore
    uni.navigateBack({
        delta: delta
    }).then((r: ptAny) => {
    })
}

