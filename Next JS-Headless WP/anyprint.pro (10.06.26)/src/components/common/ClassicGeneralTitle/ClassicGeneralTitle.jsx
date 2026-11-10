import SafeContent from '../SafeContent/SafeContent'
import styles from './ClassicGeneralTitle.module.scss'

export const ClassicGeneralTitle = ({title}) => {
    return (
        <SafeContent html={title} className={styles['title']} tag='h2'>
        </SafeContent>
    )
}