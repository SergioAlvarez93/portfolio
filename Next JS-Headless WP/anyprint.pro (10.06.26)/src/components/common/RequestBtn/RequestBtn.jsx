import styles from "./RequestBtn.module.scss"

export const RequestBtn = ({onModalChange}) => {    
    return (
        <button className={styles['request-btn']} onClick={() => onModalChange('modalRequest')}>Оставить заявку</button>
    )
}