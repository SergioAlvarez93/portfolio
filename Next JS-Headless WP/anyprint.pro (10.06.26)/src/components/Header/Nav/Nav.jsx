import styles from "./Nav.module.scss"
import Link from "next/link"
import { useViewportWidth } from "@/hooks/useViewportWidth"

export const Nav = ({list, onClick, className=""}) => {
    const {width} = useViewportWidth();
    return (
        <nav className={className}>
            <ul className={styles['nav-ul']}>
                {list.map((item, index) => {
                    return <li key={index}><Link href={item.link} onClick={width === 'mobile' && onClick} className={styles['nav-link']}>{item.name}</Link></li>
                })}
            </ul>
        </nav>
    )
}