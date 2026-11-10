import SafeContent from '../SafeContent/SafeContent'
import styles from './SmallTitle.module.scss'

export const SmallTitle = ({title, className=""}) => {
    return (
        <SafeContent html={title} tag='h3' className={`${className} ${styles['title']}`} />
    )
}