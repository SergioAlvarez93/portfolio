import styles from "./Messangers.module.scss"
import Link from "next/link"
import Image from "next/image"

export const Messangers = ({messangers}) => {
    return (
        <>
            {messangers.map((item, index) => {
            return <Link key={index} className={styles['socials-link']} href={item.link}><Image src={item.icon.url} className={styles['socials-icon']} fill alt={item.icon.alt}></Image></Link>
            })}
        </>
    )
}