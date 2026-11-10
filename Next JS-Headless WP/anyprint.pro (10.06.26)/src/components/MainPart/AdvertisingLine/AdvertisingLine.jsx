import Image from "next/image";
import styles from './AdvertisingLine.module.scss'
import { useViewportWidthPixel } from "@/hooks/useViewportWidthPixel";

export const AdvertisingLine = ({ brands }) => {
    const { width } = useViewportWidthPixel();
    const isMobile = width !== null && width < 768;

    function adaptiveMinus(
    smallScreenValue,
    largeScreenValue,
    smallWidth = 360,
    largeWidth = 767
) {
    smallScreenValue = Number(smallScreenValue);
    largeScreenValue = Number(largeScreenValue);
    
    const addValue = largeScreenValue - smallScreenValue;
    const addWidth = largeWidth - smallWidth;
    const vw = window.innerWidth;

    return Math.round(
        Math.max(
            smallScreenValue,
            Math.min(
                largeScreenValue,
                smallScreenValue + addValue * ((vw - smallWidth) / addWidth)
            )
        )
    );
}
    return brands.map((item, index) => {
        const logoWidth = isMobile
            ? `${adaptiveMinus(item.mob_width, item.brand_logo.width)}px`
            : `${item.brand_logo.width}px`;

        return (
            <div key={index} className={styles['brand-logo-wrapper']} style={{ width: logoWidth, aspectRatio: item.brand_logo.width / item.brand_logo.height}}>
                <Image
                    src={item.brand_logo.url}
                    className={styles['brand-logo']}
                    fill
                    sizes="300px"
                    alt={item.brand_logo.alt}
                />
            </div>
        );
    });
};