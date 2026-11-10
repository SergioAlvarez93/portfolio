import styles from './PresentationNotes.module.scss'

export const PresentationNotes = ({color, title, subtitle}) => {
    return (
        <div className={styles['card']}>
            <div className={styles['card-line']} style={{backgroundColor: color}}></div>
            <div className={styles['card-content-box']}>
                <div className={styles['card-content-title']} style={{color: color}}>{title}</div>
                <div className={styles['card-content-subtitle']}>{subtitle}</div>
            </div> 
        </div>
    )
}