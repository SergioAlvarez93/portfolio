import styles from "./Logo.module.scss"
import Image from "next/image"

export const Logo = ({src, alt}) => {
    return (
        <Image src={src} className={styles.logo} fill alt={alt}></Image>
    )
}