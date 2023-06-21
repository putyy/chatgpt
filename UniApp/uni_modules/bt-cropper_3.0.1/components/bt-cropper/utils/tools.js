export function getTouchPoints(touchs) {
	return Array.from(touchs).map(ev => {
		return [ev.clientX, ev.clientY]
	})
}
// 函数防抖
export function debounce(fn, wait = 200) {
	var timer = null;
	return function (){
		if (timer !== null) {
			clearTimeout(timer);
		}
		timer = setTimeout(fn.bind(this), wait);
	}
}

/**
 * @description 睡眠
 * @param {number} time 等待时间毫秒数
 */
export function sleep(time = 200) {
	return new Promise(resolve => {
		setTimeout(resolve, time)
	})
}
const systemInfo = uni.getSystemInfoSync();

export function parseUnit(size){
	if(typeof size == 'number' || !isNaN(Number(size))){
		return uni.upx2px(size)
	}else if(typeof size === 'string') {
		if(size.endsWith('rpx')){
			return parseUnit(size.replace('rpx',''))
		}else if(size.endsWith('px')){
			return Number(size.replace('px',''))
		}else if(size.endsWith('vw')){
			return Number(size.replace('vw',''))*systemInfo.screenWidth/100
		}else if(size.endsWith('vh')){
			return Number(size.replace('vh',''))*systemInfo.screenHeight/100
		}
	}
	return 0
}
