import isPermission from '@/utils/auth'
import Vue from 'vue'
import SvgIcon from '@/components/SvgIcon'
import CurdCard from '@/components/Curd/Card'
import Curd from '@/components/Curd'
import DialogForm from '@/components/DialogForm'
import UploadImage from '@/components/AttachmentLibrary/image'
import UploadFile from '@/components/AttachmentLibrary/file'
import UploadVideo from '@/components/AttachmentLibrary/video'
import Editor from '@/components/Editor/index'

Vue.component('SvgIcon', SvgIcon)
Vue.component('CurdCard', CurdCard)
Vue.component('Curd', Curd)
Vue.component('DialogForm', DialogForm)
Vue.component('UploadImage', UploadImage)
Vue.component('UploadFile', UploadFile)
Vue.component('UploadVideo', UploadVideo)
Vue.component('Editor', Editor)


//自定义指令
Vue.directive('permission', {
  inserted(el, binding, vNode) {
    const {value} = binding
    if (!isPermission(value)) {
      el.parentNode && el.parentNode.removeChild(el)
    }
  }
})
