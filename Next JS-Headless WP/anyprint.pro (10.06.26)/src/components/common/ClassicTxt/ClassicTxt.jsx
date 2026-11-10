import SafeContent from '../SafeContent/SafeContent'
import styles from './ClassicTxt.module.scss'

export const ClassicTxt = ({text}) => {
    return (
        <SafeContent html={text} tag='p' className={styles['text']}></SafeContent>
    )
}