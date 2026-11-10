import SafeContent from '../SafeContent/SafeContent'
import styles from './GeneralTitle.module.scss'

export const GeneralTitle = ({title, className=""}) => {
    return (
        <SafeContent 
            html={title} 
            className={`${styles['general-title']} ${className}`}
            tag="h1"
        />
    )
}