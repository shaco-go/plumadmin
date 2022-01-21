import {title} from '@/config'
import {cloneDeep} from 'lodash'

//当前标题
export default function getPageTitle(pageTitle) {
    if (pageTitle) {
        return `${pageTitle} - ${title}`
    }
    return `${title}`
}

//判断是不是外链
export function isExternal(path) {
    return /^(https?:|mailto:|tel:)/.test(path)
}

//在树状数据中,找到父级数组
export function findLinks(data, func, links = []) {
    for (const e of data) {
        let item = cloneDeep(e)
        delete item.children
        links.push(item)
        if (func(e)) {
            return links
        }
        if (e.children && e.children.length > 0) {
            const findLinksList = findLinks(e.children, func, links)
            if (findLinksList.length > 0) {
                return findLinksList
            }
        }
        links.pop()
    }
    return []
}
