import Link from "next/link"
import styles from "./PresentationContact.module.scss"

export const PresentationContact = ({presentationEmail, presentationTel, className=""}) => {
    return (
        <Link href={presentationEmail ? `mailto:${presentationEmail}` : `tel:${presentationTel.replace(/[^0-9+]/g, '')}`} className={`${styles.contact} ${className}`}>{presentationEmail || presentationTel}</Link>
    )
}