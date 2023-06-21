import request from './utils/services'

export function getLogin(data: ptAny) {
    return request.get('login/index', data)
}

export function getInit() {
    return request.get('public/init')
}

export function getMine() {
    return request.get('user/mine')
}

export function getFriends(data: ptAny) {
    return request.get('user/friends', data)
}

export function postUserDataInfo(data: ptAny) {
    return request.post('user/edit', data)
}

export function getQiniuToken(scenes: string) {
    return request.get('public/upload-token', {scenes: scenes})
}

export function getAiRoles() {
    return request.get('chat/roles')
}

export function getAiModelList() {
    return request.get('chat/model-list')
}

export function getAiSession(data: ptAny) {
    return request.get('chat/session', data)
}

export function getAiSessionHistory(data: ptAny) {
    return request.get('chat/session-history', data)
}

export function postAiSessionClose(data: ptAny) {
    return request.post('chat/session-close', data)
}

export function postAiSessionShare(data: ptAny) {
    return request.post('chat/session-share', data)
}

export function postAiSessionDelete(data: ptAny) {
    return request.post('chat/session-delete', data)
}

export function getAiSessionShareList(data: ptAny) {
    return request.get('chat/session-share-list', data)
}

export function getAiSessionShareMessageList(data: ptAny) {
    return request.get('chat/session-share-message-list', data)
}

export function getAiMessages(data: ptAny) {
    return request.get('chat/messages', data)
}

export function getAiQuickIssue() {
    return request.get('chat/quick-issue')
}

export function getVipConfig() {
    return request.get('public/vip-config')
}

export function getOrderList(data: ptAny) {
    return request.get('order/list', data)
}

export function postKamiOpenVip(data: ptAny) {
    return request.post('order/kami-open-vip', data)
}

export function getWalletInfo() {
    return request.get('wallet/index')
}

export function getChangeLogList(data: ptAny) {
    return request.get('wallet/change-log-list', data)
}

export function getWithdrawalList(data: ptAny) {
    return request.get('wallet/withdrawal-list', data)
}

export function postWithdrawal(data: ptAny) {
    return request.post('wallet/withdrawal', data)
}