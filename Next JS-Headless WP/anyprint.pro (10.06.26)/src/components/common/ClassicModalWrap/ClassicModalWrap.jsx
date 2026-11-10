import styles from './ClassicModalWrap.module.scss'

export const ClassicModalWrap = ({children, maxWidth, padding, closeModal}) => {
    return (
        <div className={styles['modal']} data-modal-overlay>
            <div className={styles['modal-container']} style={{maxWidth: `${maxWidth}px`}}>
                <span className={styles['modal-close']} onClick={closeModal}></span>
                <div className={styles['modal-content']} style={padding}>
                    {children}
                </div>
            </div>
        </div>
    )
}