var startTouchs = [];
var touchType = ''
var startDistance = 0;
var touchCenter = [];
var cropperRect = null;
var imageRect = null;
var directionX = 0;
var directionY = 0;
var ratio = 0;
// 操作时改变的对象
var changes = {
	imageRect: null,
	cropperRect: null
}
export default {
	computed: {
		imageStyle() {
			const imageRect = this.imageRect
			if (imageRect) {
				return {
					left: imageRect.left + 'px',
					top: imageRect.top + 'px',
					width: imageRect.width + 'px',
					height: imageRect.height + 'px'
				}
			} else {
				return {}
			}
		},
		cropperStyle() {
			const cropperRect = this.cropperRect
			if (cropperRect) {
				return {
					left: cropperRect.left + 'px',
					top: cropperRect.top + 'px',
					width: cropperRect.width + 'px',
					height: cropperRect.height + 'px'
				}
			} else {
				return {}
			}
		}
	},
	methods: {
		touchStart() {
			let ev;
			if (arguments.length == 3) {
				directionX = arguments[0];
				directionY = arguments[1];
				ev = arguments[2];
				touchType = "controller";
			} else {
				touchType = "image";
				ev = arguments[0];
			}
			startTouchs = ev.touches;
			changes = {
				imageRect: this.imageRect,
				cropperRect: this.cropperRect
			};
			ratio = this.ratio;
			cropperRect = {
				...changes.cropperRect
			}
			imageRect = {
				...changes.imageRect
			}
			if (startTouchs.length == 2) {
				const imageRect = this.imageRect
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
		},
		touchMove(ev) {
			if(startTouchs.length!==ev.touches.length) return
			var touches = ev.touches;
			var changeX1 = touches[0].clientX - startTouchs[0].clientX;
			var changeY1 = touches[0].clientY - startTouchs[0].clientY;
			if (startTouchs.length == 1) {
				if (touchType === 'image') {
					changes.imageRect.left = imageRect.left + changeX1;
					changes.imageRect.top = imageRect.top + changeY1;
					// console.log(startTouchs.length,ev.touches.length)
				} else if (touchType === 'controller') {
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
				}
			} else if (touches.length == 2 && startTouchs.length == 2) {
				var changeX2 = touches[0].clientX - touches[1].clientX;
				var changeY2 = touches[0].clientY - touches[1].clientY;
				var distance = Math.pow(changeX2, 2) + Math.pow(changeY2, 2)
				distance = Math.sqrt(distance)
				// 放大比例
				var scaleRate = distance / startDistance
				this.imageScale(scaleRate)
			}
		},
		touchEnd(ev) {
			// console.log('end',ev)
			if(ev.touches.length!==0) return
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
					this.imageScale(scale)
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
			}
			this.updateData({
				cropperRect: changes.cropperRect,
				imageRect: changes.imageRect,
			})
			touchType = ""
			startTouchs = []
			return false;
		},
		imageScale(scaleRate) {
			var cw = imageRect.width * (scaleRate - 1)
			var ch = imageRect.height * (scaleRate - 1)
			changes.imageRect = {
				width: imageRect.width + cw,
				height: imageRect.height + ch,
				left: imageRect.left - cw * (touchCenter[0]),
				top: imageRect.top - ch * (touchCenter[1])
			}
		}
	}
}
