<template>
	<view class="bt-container" :style="[containerStyle]">
		<!-- #ifdef APP-VUE || MP-WEIXIN || MP-QQ || H5 -->
		<view class="mainContent" data-type="image" @touchstart="wxsModule.touchStart" @touchmove="wxsModule.touchMove" @touchend="wxsModule.touchEnd">
		<!-- #endif -->
		<!-- #ifndef APP-VUE || MP-WEIXIN || MP-QQ || H5 -->
		<view class="mainContent" data-type="image" @touchstart="touchStart" @touchmove="touchMove" @touchend="touchEnd">
		<!-- #endif -->
			<template v-if="imageRect && cropperRect">
				<!-- #ifdef APP-VUE || MP-WEIXIN || MP-QQ || H5 -->
				<image mode="aspectFit" :src="imageSrc" class="image" :change:imageRect="wxsModule.changeImageRect" :imageRect="imageRect" :class="{ anim }">
				<!-- #endif -->
				<!-- #ifndef APP-VUE || MP-WEIXIN || MP-QQ || H5 -->
				<image mode="aspectFit" :src="imageSrc" class="image" :style="[imageStyle]" :class="{ anim }">
				<!-- #endif -->
				</image>
				<!-- #ifdef APP-VUE || MP-WEIXIN || MP-QQ || H5 -->
				<view class="cropper" :class="{ anim }" :change:cropperRect="wxsModule.changeCropper" :cropperRect="cropperRect" :change:ratio="wxsModule.changeRatio" :ratio="ratio" >
				<!-- #endif -->
				<!-- #ifndef APP-VUE || MP-WEIXIN || MP-QQ || H5 -->
				<view class="cropper" :class="{ anim }"  :style="[cropperStyle]">
				<!-- #endif -->
					<image class="mask" :src="mask"></image>
					<template v-if="showGrid">
						<view class="line row row1"></view>
						<view class="line row row2"></view>
						<view class="line col col1"></view>
						<view class="line col col2"></view>
					</template>
					<!-- #ifdef APP-VUE || MP-WEIXIN || MP-QQ || H5  -->
					<view class="controller vertical left" @touchstart="wxsModule.touchStart" data-type="controller" />
					<view class="controller vertical right" @touchstart="wxsModule.touchStart" data-type="controller" />
					<view class="controller horizon top" @touchstart="wxsModule.touchStart" data-type="controller" />
					<view class="controller horizon bottom" @touchstart="wxsModule.touchStart" data-type="controller" />
					<view class="controller left top" @touchstart="wxsModule.touchStart" data-type="controller" />
					<view class="controller left bottom" @touchstart="wxsModule.touchStart" data-type="controller" />
					<view class="controller right top" @touchstart="wxsModule.touchStart" data-type="controller" />
					<view class="controller right bottom" @touchstart="wxsModule.touchStart" data-type="controller" />
					<!-- #endif -->
					<!-- #ifndef APP-VUE || MP-WEIXIN || MP-QQ || H5  -->
					<view class="controller vertical left" @touchstart.stop="touchStart(-1, 0, $event)" />
					<view class="controller vertical right" @touchstart.stop="touchStart(1, 0, $event)" />
					<view class="controller horizon top" @touchstart.stop="touchStart(0, -1, $event)" />
					<view class="controller horizon bottom" @touchstart.stop="touchStart(0, 1, $event)" />
					<view class="controller left top" @touchstart.stop="touchStart(-1, -1, $event)" />
					<view class="controller left bottom" @touchstart.stop="touchStart(-1, 1, $event)" />
					<view class="controller right top" @touchstart.stop="touchStart(1, -1, $event)" />
					<view class="controller right bottom" @touchstart.stop="touchStart(1, 1, $event)" />
					<!-- #endif -->
				</view>
			</template>
		</view>
			<canvas v-if="type2d" type="2d" class="bt-canvas" :width="target.width" :height="target.height"></canvas>
			<canvas
				v-else
				:canvas-id="canvasId"
				class="bt-canvas"
				:style="{
					width: target.width + 'px',
					height: target.height + 'px'
				}"
				:width="target.width * pixel"
				:height="target.height * pixel"
			></canvas>
	</view>
</template>

<script>
// #ifndef APP-VUE || MP-WEIXIN || MP-QQ || H5
import touches from './js/touchs.js';
// #endif
import { parseUnit,sleep } from './utils/tools.js';
/**
 * better-cropper 图片裁切插件
 */
export default {
	name: 'bt-cropper',
	props: {
		// 图片路径，支持网络路径和本地路径
		imageSrc: {
			type: String,
			default: '',
			required: true
		},
		mask: {
			type: String,
			default: ''
		},
		// 手动指定容器的大小
		containerSize: {
			type: Object,
			default: null
		},
		// 输出图片的格式，默认jpg
		fileType: {
			type: String,
			default: 'png'
		},
		// 生成的图片的宽度,不传或者传0表示按照原始分辨率裁切
		dWidth: Number,
		maxWidth: {
			type: Number,
			default: 2000
		},
		// 裁切比例，0表示自由
		ratio: {
			type: Number,
			default: 0,
			validator(value) {
				if (typeof value === 'number') {
					if (value < 0) {
						return false;
					}
				} else {
					return false;
				}
				return true;
			}
		},
		// 是否展示网格
		showGrid: {
			type: Boolean,
			default: false
		},
		// 图片质量，0-1 越大质量越好
		quality: {
			type: Number,
			default: 1
		},
		canvas2d: {
			type: Boolean,
			default: false
		},
		// 初始的图片位置
		initPosition: {
			type: Object,
			default() {
				return null;
			}
		},
		// 是否开启操作结束后自动放大
		autoZoom: {
			type: Boolean,
			default: true
		}
	},
	// #ifndef APP-VUE || MP-WEIXIN || MP-QQ || H5
	mixins: [touches],
	// #endif
	data() {
		return {
			canvasId: 'bt-cropper',
			containerRect: null,
			imageInfo: null,
			operationHistory: [],
			operationIndex: 0,
			anim: false,
			timer: null,
			// 是否使用2D canvas
			type2d: false,
			pixel: 1,
			imageRect: null,
			cropperRect: null,
			target:{
				width:0,
				height:0
			}
		};
	},
	watch: {
		imageSrc: {
			handler(src) {
				if (typeof src === 'string' && src !== '') {
					this.imageInit(src);
				} else {
					this.imageInfo = null;
				}
			},
			immediate: true
		},
		ratio() {
			if (this.ratio != 0) {
				this.startAnim();
				this.init();
			}
		}
	},
	computed: {
		containerStyle() {
			if (this.containerSize && this.containerRect) {
				return {
					width: this.containerRect.width + 'px',
					height: this.containerRect.height + 'px'
				};
			}
			return {};
		}
	},
	methods: {
		startAnim() {
			this.stopAnim();
			this.anim = true;
			this.timer = setTimeout(() => {
				this.anim = false;
			}, 200);
		},
		stopAnim() {
			this.anim = false;
			clearTimeout(this.timer);
		},
		imageInit(src) {
			uni.showLoading({
				title: '载入中...'
			});
			uni.getImageInfo({
				src,
				success: res => {
					this.imageInfo = res;
					this.$nextTick(() => {
						this.getContainer().then(rect => {
							this.containerRect = rect;
							this.init();
						});
					});
				},
				fail: (err) => {
					this.$emit('loadFail',err);
					uni.showToast({
						title: '图片下载失败!',
						icon: 'none'
					});
				},
				complete(res) {
					uni.hideLoading();
				}
			});
		},
		initCropper() {
			const imageRate = this.imageInfo.width / this.imageInfo.height;
			const containerRate = this.containerRect.width / this.containerRect.height;
			const imageRect = {};
			let cropperRate = this.ratio;
			if (cropperRate == 0) {
				if (this.cropperRect) {
					cropperRate = this.cropperRect.width / this.cropperRect.height;
				} else {
					cropperRate = 1;
				}
			}
			const cropperRect = {};
			if (containerRate > cropperRate) {
				cropperRect.height = this.containerRect.height * 0.85;
				cropperRect.width = cropperRect.height * cropperRate;
			} else {
				cropperRect.width = this.containerRect.width * 0.85;
				cropperRect.height = cropperRect.width / cropperRate;
			}
			if (cropperRate > imageRate) {
				imageRect.width = cropperRect.width;
				imageRect.height = imageRect.width / imageRate;
			} else {
				imageRect.height = cropperRect.height;
				imageRect.width = imageRect.height * imageRate;
			}
			imageRect.left = (this.containerRect.width - imageRect.width) / 2;
			imageRect.top = (this.containerRect.height - imageRect.height) / 2;
			cropperRect.left = imageRect.left + (imageRect.width - cropperRect.width) / 2;
			cropperRect.top = imageRect.top + (imageRect.height - cropperRect.height) / 2;
			return {
				imageRect,
				cropperRect
			};
		},
		init() {
			const {imageRect,cropperRect} = this.initCropper()
			if(this.initPosition){
				const scale = this.imageInfo.width/imageRect.width;
				const {left,top,width,height} = this.initPosition;
				if(left!==undefined&&top!==undefined&&width!==undefined&&height!==undefined){
					cropperRect.width = width/scale;
					cropperRect.height = height/scale;
					cropperRect.left = left/scale;
					cropperRect.top = top/scale;
					this.$nextTick(this.zoomToFill);
				}
			}
			this.imageRect = imageRect
			this.cropperRect = cropperRect
			this.operationHistory = [{imageRect,cropperRect}];
			this.operationIndex = 0;
			this.setTarget();
			// #ifdef MP-WEIXIN
			const systemInfo = uni.getSystemInfoSync();
			if (this.canvas2d === false || systemInfo.platform === 'windows' || systemInfo.platform === 'mac') {
				this.type2d = false;
			} else {
				this.type2d = true;
				this.pixel = systemInfo.pixelRatio;
			}
			// #endif
			//非微信小程序端强制关闭canvas2d模式
			// #ifndef MP-WEIXIN
			this.type2d = false;
			// #endif
			// #ifdef  MP-TOUTIAO || MP-LARK || MP-ALIPAY
			this.type2d = this.canvas2d;
			// #endif
		},
		// 设置目标图像的大小
		setTarget(){
			const ratio = this.cropperRect.width / this.cropperRect.height;
			if (!!this.dWidth) {
				this.target = {
					width: this.dWidth,
					height: this.dWidth / (ratio || 1)
				}
			} else {
				const width = Math.min(this.maxWidth, this.cropperRect.width * (this.imageInfo.width / this.imageRect.width));
				this.target = {
					width,
					height: width / (ratio || 1)
				}
			}
		},
		addHistory({imageRect,cropperRect}){
			if(this.operationIndex!==this.operationHistory.length-1){
				this.operationHistory = this.operationHistory.slice(0,this.operationIndex)
			}
			this.operationHistory.push({
				imageRect,
				cropperRect
			});
			if (this.operationHistory.length > 10) {
				this.operationHistory.shift();
			}
			this.operationIndex = this.operationHistory.length - 1;
		},
		updateData(data) {
			this.imageRect = data.imageRect
			this.cropperRect = data.cropperRect
			this.addHistory(data);
			this.setTarget();
			if (this.autoZoom) {
				this.timer = setTimeout(() => {
					this.zoomToFill();
				}, 600);
			}
			const {imageRect,cropperRect} = data
			const scale = imageRect.width/this.imageInfo.width
			this.$emit('change', {
				left: (cropperRect.left - imageRect.left) /scale,
				top: (cropperRect.top - imageRect.top) / scale,
				width: cropperRect.width / scale,
				height: cropperRect.height / scale
			});
		},
		getContainer() {
			if (this.containerSize !== null && typeof this.containerSize == 'object') {
				const { width, height } = this.containerSize;
				return Promise.resolve({
					width: parseUnit(width),
					height: parseUnit(height)
				});
			} else {
				return new Promise(resolve => {
					const query = uni.createSelectorQuery().in(this);
					query
						.select('.mainContent')
						.boundingClientRect(rect => {
							resolve(rect);
						})
						.exec();
				});
			}
		},
		zoomToFill() {
			this.startAnim();
			const beforeCropper = {
				...this.cropperRect
			};
			const operation = {
				imageRect:this.imageRect,
				cropperRect:this.cropperRect
			};
			this.cropperRect = this.initCropper().cropperRect;
			const scale = this.cropperRect.width / beforeCropper.width;
			const ox = beforeCropper.left - this.imageRect.left;
			const oy = beforeCropper.top - this.imageRect.top;
			this.imageRect = {
				width: this.imageRect.width * scale,
				height: this.imageRect.height * scale,
				left: this.imageRect.left + (this.cropperRect.left - beforeCropper.left) - (scale - 1) * ox,
				top: this.imageRect.top + (this.cropperRect.top - beforeCropper.top) - (scale - 1) * oy
			};
		},
		onTouchStart() {
			this.stopAnim();
		},
		// 撤销
		undo() {
			if (this.operationIndex > 0) {
				this.operationIndex--;
				this.imageRect = this.operationHistory[this.operationIndex].imageRect;
				this.cropperRect = this.operationHistory[this.operationIndex].cropperRect;
				return true;
			}
			return false;
		},
		// 重做
		resume() {
			if (this.operationIndex < this.operationHistory.length - 1) {
				this.operationIndex++;
				this.imageRect = this.operationHistory[this.operationIndex].imageRect;
				this.cropperRect = this.operationHistory[this.operationIndex].cropperRect;
				return true;
			}
			return false;
		},
		async drawImage(ctx,image,x,y,w,h){
			if (this.type2d) {
				await new Promise(resolve => (image.onload = resolve));
				ctx.drawImage(image, x * this.pixel, y * this.pixel, w * this.pixel, h * this.pixel);
			} else {
				const path = await new Promise((resolve)=>{
					uni.getImageInfo({
						src:image,
						success({path}){
							resolve(path)
						}
					})
				})
				ctx.drawImage(path, x * this.pixel, y * this.pixel, w * this.pixel, h * this.pixel);
				await new Promise((resolve) => ctx.draw(false,resolve));
			}
		},
		async crop() {
			let ctx;
			let canvas;
			this.$emit('cropStart')
			this.setTarget()
			if (this.type2d) {
				const query = uni.createSelectorQuery().in(this);
				canvas = await new Promise(resolve =>
					query
						.select('.bt-canvas')
						.node(({ node }) => resolve(node))
						.exec()
				);
				canvas.width = this.target.width * this.pixel;
				canvas.height = this.target.height * this.pixel;
				ctx = canvas.getContext('2d');
				// #ifdef MP-TOUTIAO
				if(this.type2d){
					console.warn("请注意：目前头条系小程序暂时无法使用2d canvas保存图片，建议换成V1版本")
				}
				// #endif
			} else {
				ctx = uni.createCanvasContext(this.canvasId,this);
			}
			const scale = this.cropperRect.width / this.target.width;
			const dx = (this.cropperRect.left - this.imageRect.left) / scale;
			const dy = (this.cropperRect.top - this.imageRect.top) / scale;
			let image;
			if(this.type2d){
				image = canvas.createImage()
				image.src = this.imageSrc;
			}else{
				image = this.imageSrc;
			}
			await this.drawImage(ctx,image, -dx, -dy, this.imageRect.width / scale, this.imageRect.height / scale);
			if (this.mask !== '') {
				let imageData;
				if (this.type2d) {
					imageData = ctx.getImageData(0, 0, this.target.width, this.target.height);
				} else {
					imageData = await new Promise(resolve => {
						uni.canvasGetImageData({
							canvasId: this.canvasId,
							x: 0,
							y: 0,
							width: this.target.width,
							height: this.target.height,
							success(res) {
								resolve(res);
							}
						},this);
					});
				}
				ctx.clearRect(0, 0, this.target.width, this.target.height);
				if(this.type2d){
					image.src = this.mask;
				}else{
					image = this.mask;
				}
				await this.drawImage(ctx,image, 0, 0, this.target.width, this.target.height);
				let maskData;
				if (this.type2d) {
					maskData = ctx.getImageData(0, 0, this.target.width, this.target.height);
				} else {
					maskData = await new Promise((resolve,reject) => {
						uni.canvasGetImageData({
							canvasId: this.canvasId,
							x: 0,
							y: 0,
							width: this.target.width,
							height: this.target.height,
							success(res) {
								resolve(res);
							}
						},this);
					});
				}
				ctx.clearRect(0, 0, this.target.width, this.target.height);
				for (let index = 3; index < maskData.data.length; index += 4) {
					const alpha = maskData.data[index];
					if (alpha !== 0) {
						imageData.data[index] = 0;
					}
				}
				if (this.type2d) {
					ctx.putImageData(imageData, 0, 0);
				} else {
					await new Promise(resolve => {
						uni.canvasPutImageData({
							canvasId: this.canvasId,
							x: 0,
							y: 0,
							width: imageData.width,
							height: imageData.height,
							data: imageData.data,
							complete: res => {
								resolve(res);
							}
						},this);
					});
				}
			}
			return new Promise(resolve => {
				const params = {};
				if (this.type2d) {
					params.canvas = canvas;
				} else {
					params.canvasId = this.canvasId;
				}
				
				uni.canvasToTempFilePath({
					...params,
					destWidth: this.target.width,
					destHeight: this.target.height,
					quality: Number(this.quality) || 1,
					fileType: this.fileType,
					success: ({ tempFilePath }) => {
						// #ifdef H5
						var arr = tempFilePath.split(',');
						var mime = arr[0].match(/:(.*?);/)[1];
						var bstr = atob(arr[1]);
						var n = bstr.length;
						var u8arr = new Uint8Array(n);
						for (var i = 0; i < n; i++) {
							u8arr[i] = bstr.charCodeAt(i);
						}
						var url = URL || webkitURL;
						resolve(
							url.createObjectURL(
								new Blob([u8arr], {
									type: mime
								})
							)
						);
						// #endif
						resolve(tempFilePath);
					},
					fail(err) {
						console.log(err);
						resolve('tempFilePath');
					}
				},this);
			});
		}
	}
};
</script>
<!-- #ifdef APP-VUE || MP-WEIXIN || MP-QQ || H5 -->
<script module="wxsModule" lang="wxs">
var startTouchs = [];
var startDistance = 0;
var touchCenter = [];
var ratio = 0;
var imageInstance = null;
var cropperInstance = null;
var touchType = "";
var touchInstance = null;
var cropperRect = null;
var imageRect = null;
// 操作时改变的对象
var changes = {
	imageRect: null,
	cropperRect: null
}

function updateImageStyle() {
	var imageRect = changes.imageRect
	imageInstance.setStyle({
		left: imageRect.left + 'px',
		top: imageRect.top + 'px',
		width: imageRect.width + 'px',
		height: imageRect.height + 'px'
	})
}

function updateCopperStyle() {
	var cropperRect = changes.cropperRect
	cropperInstance.setStyle({
		left: cropperRect.left + "px",
		top: cropperRect.top + "px",
		width: cropperRect.width + "px",
		height: cropperRect.height + "px"
	})
}

function imageScale(scaleRate) {
	var cw = imageRect.width * (scaleRate - 1)
	var ch = imageRect.height * (scaleRate - 1)
	changes.imageRect = {
		width: imageRect.width + cw,
		height: imageRect.height + ch,
		left: imageRect.left - cw * (touchCenter[0]),
		top: imageRect.top - ch * (touchCenter[1])
	}
}
module.exports = {
	touchStart: function (ev, oi) {
		// #ifdef H5
		ev.preventDefault();
		ev.stopPropagation();
		// #endif
		touchInstance = ev.instance;
		var dataSet = ev.instance.getDataset()
		touchType = dataSet.type;
		startTouchs = ev.touches;
		oi.callMethod('onTouchStart')
		if (startTouchs.length == 2) {
			touchType = "image"
			var x1 = startTouchs[0].clientX
			var y1 = startTouchs[0].clientY
			var x2 = startTouchs[1].clientX
			var y2 = startTouchs[1].clientY
			var distance = Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2)
			startDistance = Math.sqrt(distance)
			var leftPercent = ((x1 + x2) / 2 - imageRect.left) / imageRect.width
			var topPercent = ((y1 + y2) / 2 - imageRect.top) / imageRect.height
			touchCenter = [leftPercent, topPercent]
		}
		return false;
	},
	touchMove: function (ev, io) {
		if (touchType == "") return false
		// #ifdef H5
		ev.preventDefault();
		ev.stopPropagation();
		// #endif
		var touches = ev.touches;
		var changeX1 = touches[0].clientX - startTouchs[0].clientX;
		var changeY1 = touches[0].clientY - startTouchs[0].clientY;
		if (startTouchs.length == 1) {
			if (touchType === 'image') {
				changes.imageRect.left = imageRect.left + changeX1;
				changes.imageRect.top = imageRect.top + changeY1;
				updateImageStyle()
			} else if (touchType === 'controller') {
				var directionX = 0;
				if (touchInstance.hasClass('left')) {
					directionX = -1;
				}
				if (touchInstance.hasClass('right')) {
					directionX = 1;
				}
				var directionY = 0;
				if (touchInstance.hasClass('top')) {
					directionY = -1
				}
				if (touchInstance.hasClass('bottom')) {
					directionY = 1
				}
				var changeX = changeX1 * directionX;
				var changeY = changeY1 * directionY;
				// 比例缩放控制
				if (ratio !== 0) {
					if (directionX * directionY !== 0) {
						if (changeX / ratio > changeY) {
							changeY = changeX / ratio
							changeX = changeY * ratio
						} else {
							changeX = changeY * ratio
							changeY = changeX / ratio
						}
					} else {
						if (directionX == 0) {
							changeX = changeY * ratio
						} else {
							changeY = changeX / ratio
						}
					}
				}

				var width = cropperRect.width + changeX
				var height = cropperRect.height + changeY
				var imageRight = imageRect.left + imageRect.width
				var imageBottom = imageRect.top + imageRect.height
				if (directionX != -1) {
					if (cropperRect.left + width > imageRight) {
						width = imageRight - cropperRect.left
						if (ratio !== 0) {
							height = width / ratio
						}
					}
				} else {
					var cLeft = cropperRect.left - changeX
					if (cLeft < imageRect.left) {
						width = cropperRect.left + cropperRect.width - imageRect.left
						if (ratio !== 0) {
							height = width / ratio
						}
					}
				}
				// 判断是否触底
				if (directionY != -1) {
					if (cropperRect.top + height > imageBottom) {
						height = imageBottom - cropperRect.top
						if (ratio !== 0) {
							width = height * ratio
						}
					}
				} else {
					var cTop = cropperRect.top - changeY
					if (cTop < imageRect.top) {
						height = cropperRect.top + cropperRect.height - imageRect.top
						if (ratio !== 0) {
							width = height * ratio
						}
					}
				}
				if (directionX == -1) {
					changes.cropperRect.left = cropperRect.left + cropperRect.width - width
				}
				if (directionY == -1) {
					changes.cropperRect.top = cropperRect.top + cropperRect.height - height
				}
				// 边界控制
				changes.cropperRect.width = width
				changes.cropperRect.height = height
				updateCopperStyle()
			}
		} else if (touches.length == 2 && startTouchs.length == 2) {
			var changeX2 = touches[0].clientX - touches[1].clientX;
			var changeY2 = touches[0].clientY - touches[1].clientY;
			var distance = Math.pow(changeX2, 2) + Math.pow(changeY2, 2)
			distance = Math.sqrt(distance)
			// 放大比例
			var scaleRate = distance / startDistance
			imageScale(scaleRate)
			updateImageStyle()
		}
		return false;
	},
	touchEnd: function (ev, oi) {
		if (touchType === "image") {
			var cropperLeft = cropperRect.left
			var cropperRight = cropperRect.left + cropperRect.width
			var cropperTop = cropperRect.top
			var cropperBottom = cropperTop + cropperRect.height
			var rate = changes.imageRect.width / changes.imageRect.height
			var cropperRate = cropperRect.width / cropperRect.height
			if (changes.imageRect.width < cropperRect.width || changes.imageRect.height < cropperRect.height) {
				var scale = 1
				if (rate < cropperRate) {
					scale = cropperRect.width / changes.imageRect.width
				} else {
					scale = cropperRect.height / changes.imageRect.height
				}
				imageRect.width = changes.imageRect.width
				imageRect.height = changes.imageRect.height
				imageScale(scale)
			}
			// 边界控制start
			if (cropperLeft < changes.imageRect.left) {
				changes.imageRect.left = cropperLeft
			}
			if (cropperRight > changes.imageRect.left + changes.imageRect.width) {
				changes.imageRect.left = cropperRight - changes.imageRect.width
			}
			if (cropperTop < changes.imageRect.top) {
				changes.imageRect.top = cropperTop
			}
			if (cropperBottom > changes.imageRect.top + changes.imageRect.height) {
				changes.imageRect.top = cropperBottom - changes.imageRect.height
			}
			// 边界控制end
			updateImageStyle()
		}
		oi.callMethod('updateData', {
			cropperRect: changes.cropperRect,
			imageRect: changes.imageRect,
		})
		touchType = ""
		startTouchs = []
		return false;
	},
	// 将逻辑层的图像变换同步过来
	// 裁剪比例变化
	changeRatio: function (value) {
		ratio = value
	},
	changeImageRect: function (value, oldValue, oi) {
		if (value) {
			imageRect = value;
			changes.imageRect = {
				left: value.left,
				top: value.top,
				width: value.width,
				height: value.height
			};
			// #ifndef MP-WEIXIN || MP-QQ
			setTimeout(function() {
				imageInstance = oi.selectComponent('.mainContent > .image')
				updateImageStyle();
			});
			// #endif
			// #ifdef MP-WEIXIN || MP-QQ
			imageInstance = oi.selectComponent('.mainContent > .image')
			updateImageStyle();
			// #endif
		}
	},
	changeCropper: function (value, oldValue, oi) {
		if (value) {
			cropperRect = value
			changes.cropperRect = {
				left: value.left,
				top: value.top,
				width: value.width,
				height: value.height
			}
			// #ifdef H5 || APP-VUE
			setTimeout(function() {
			// #endif
				cropperInstance = oi.selectComponent('.mainContent > .cropper')
				updateCopperStyle()
			// #ifdef H5 || APP-VUE
			});
			// #endif
		}
	}
}
</script>
<!-- #endif -->
<style lang="scss" scoped>
.bt-container {
	// display: flex;
	// flex-direction: column;
	// justify-content: space-between;
	height: 100%;
	box-sizing: border-box;
	// background-color: #0e1319;
	position: relative;
	overflow: hidden;

	.bt-canvas {
		position: fixed;
		left: 100%;
		top: 0;
	}

	.mainContent {
		// flex: 1;
		// flex-shrink:0;
		// margin: 60rpx;
		width: 100%;
		height: 100%;
		position: relative;
		display: flex;
		justify-content: center;
		align-items: center;
		// touch-action: none;

		.image {
			position: absolute;
			// will-change: transform;
			// transform-origin: center center;
			width: 85%;
			height: 85%;
			will-change: left, top, width, height;
		}

		.controller {
			position: absolute;
			z-index: 99;
			padding: 10rpx;
			$offset: -20rpx;

			&::after {
				display: block;
				content: '';
				filter: drop-shadow(0 0px 10rpx rgba(0, 0, 0, 0.3));
			}

			&.vertical {
				top: calc(50% - 30rpx);
			}

			&.horizon {
				left: calc(50% - 30rpx);
			}

			&.left {
				&::after {
					height: 40rpx;
					border-left: 10rpx solid #fff;
				}

				left: $offset;
			}

			&.right {
				&::after {
					height: 40rpx;
					border-right: 10rpx solid #fff;
				}

				right: $offset;
			}

			&.top {
				top: $offset;

				&::after {
					width: 40rpx;
					border-top: 10rpx solid #fff;
				}
			}

			&.bottom {
				bottom: $offset;

				&::after {
					width: 40rpx;
					border-bottom: 10rpx solid #fff;
				}
			}

			&.left.bottom,
			&.right.bottom,
			&.left.top,
			&.left.bottom {
				&::after {
					width: 30rpx;
					height: 30rpx;
					background-color: transparent;
				}
			}
		}

		.cropper {
			position: absolute;
			border: 1px solid #eee;
			box-sizing: content-box;
			// transform-origin: center center;
			outline: 999px solid rgba(0, 0, 0, 0.5);
			will-change: left, top, width, height;

			// display: contain;
			// pointer-events: none;
			.mask {
				position: absolute;
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				opacity: 0.5;
			}

			.line {
				position: absolute;
				// background-color: #eee;
			}

			.row {
				width: 100%;
				height: 0px;
				left: 0;
				border-top: 1px dashed #007aff;
			}

			.col {
				height: 100%;
				width: 0px;
				border-left: 1px dashed #007aff;
			}

			.row1 {
				top: 33%;
			}

			.row2 {
				top: 66%;
			}

			.col1 {
				left: 33%;
			}

			.col2 {
				left: 66%;
			}
		}
	}

	// .slot {
	// 	position: relative;
	// 	padding-top: 20rpx;
	// }
}

.anim {
	transition: 0.2s;
}
</style>
