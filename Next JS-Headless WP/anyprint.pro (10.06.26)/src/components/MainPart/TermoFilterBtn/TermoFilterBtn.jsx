import styles from './TermoFilterBtn.module.scss'

export const TermoFilterBtn = ({children, onClick}) => {
    return (
        <button onClick={onClick} className={styles['termo-filter-btn']}>{children}</button>
    )
}