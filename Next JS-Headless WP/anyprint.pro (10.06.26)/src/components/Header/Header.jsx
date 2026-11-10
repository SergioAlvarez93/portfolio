import styles from "./Header.module.scss"
import { Logo } from "./Logo/Logo"
import Link from "next/link"
import { Nav } from "./Nav/Nav"
import { Messangers } from "../common/Messangers/Messangers"
import { RequestBtn } from "../common/RequestBtn/RequestBtn"
import { Burger } from "./Burger/Burger"
import toggleBurgerMenu from "../../hooks/toggleBurgerMenu"

export const Header = ({src, alt, homepage, menu, messangers, marginRef, onModalChange}) => {
    const { state, initToggleState, gridRef } = toggleBurgerMenu();
    return (
        <header className={state ? `${styles.header} ${styles['header-active']}` : styles.header} ref={marginRef}>
            <div className={styles.container}>
                <div className={styles['header-grid']} ref={gridRef}>
                    <Link href={homepage} className={styles['logo-chunk']}>
                        <Logo src={src} alt={alt}></Logo>
                    </Link>
                    <Nav list={menu} onClick={initToggleState} className={styles['header-nav']}></Nav>
                    <div className={styles['header-socials']}>
                        <Messangers messangers={messangers}></Messangers>
                    </div>
                    <div className={styles['header-request']}>
                        <RequestBtn onModalChange={onModalChange}></RequestBtn>
                    </div>
                    <Burger onClick={initToggleState} className={styles['burger-btn']}></Burger>
                </div>
            </div>
        </header>
    )  
}