import Image from "next/image";
import styles from './FooterAdvertLine.module.scss'

export const FooterAdvertLine = ({ brands }) => {
    return (
        brands.map((item, index) => {
            return (
                <div key={index} className={styles['brand-logo-wrapper']} style={{ width: item.img.width, aspectRatio: item.img.width / item.img.height}}>
                    <Image
                        src={item.img.url}
                        className={styles['brand-logo']}
                        fill
                        sizes="300px"
                        alt={item.img.alt}
                    />
                </div>
            )
        })
    );
};