import styles from './PresentationTxt.module.scss'

export const PresentationTxt = ({text, className=""}) => {
    return (
        <p className={`${styles['txt']} ${className}`}>{text}</p>
    )
}