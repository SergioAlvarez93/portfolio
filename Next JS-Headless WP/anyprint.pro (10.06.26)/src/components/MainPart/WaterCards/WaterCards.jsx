import { ClassicTxt } from '@/components/common/ClassicTxt/ClassicTxt'
import styles from './WaterCards.module.scss'
import Image from 'next/image'

export const WaterCards = ({data}) => {
    return (
        <div className={styles['water-cards']}>
            {data.map((item, index) => {
                return (
                    <div key={index} className={styles['water-card']}>
                        <div className={styles['water-card-img-wrap']}>
                            <Image src={item.img.url} fill sizes='319px' className={styles['water-card-img']} alt={item.img.alt} />
                        </div>
                        <div className={styles['water-card-txt']}>
                            <ClassicTxt text={item.txt} />
                        </div>
                    </div>
                )
            })}            
        </div>
    )
}