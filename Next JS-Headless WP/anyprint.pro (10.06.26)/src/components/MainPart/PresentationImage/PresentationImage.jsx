import styles from './PresentationImage.module.scss'
import Image from 'next/image'

export const PresentationImage = ({src, alt}) => {
    return (
        <Image src={src} className={styles['img']} fill alt={alt}></Image>  
    )
}