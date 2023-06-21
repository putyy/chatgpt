<script>
	import {AppInitOptionCacheKey} from "./common/const"
	import {useIndexStore} from './store/index'
	export default {
		onLaunch: function(options) {
			console.log('App Launch')
			uni.setStorageSync(AppInitOptionCacheKey, options)
			useIndexStore().authorization(false)
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style>
	/*每个页面公共css */
  /* #ifdef H5 */
  uni-page-head {
    display: none;
  }
  /* #endif */
</style>
