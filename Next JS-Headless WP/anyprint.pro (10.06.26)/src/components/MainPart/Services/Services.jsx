import { SmallTitle } from '@/components/common/SmallTitle/SmallTitle'
import styles from './Services.module.scss'
import Link from 'next/link'
import Image from 'next/image'

export const Services = ({services}) => {
    return (
        <div className={styles['cards']}>
            {services.map((card, index) => {
                return (
                <div key={index} className={styles['card']}>
                    <div className={styles['card-img-wrap']}>
                        <Image src={card.img.url} fill sizes='600px' alt={card.img.alt} className={styles['card-img']}></Image>  
                    </div>
                    <div className={styles['card-content-container']}>  
                        <SmallTitle title={card.title} className={styles['card-title']} />
                        <p className={styles['card-txt']}>{card.txt}</p>
                        <Link href={card.link} className={styles['card-link']}>
                            Посмотреть образцы →
                        </Link>
                    </div>  
                </div>
                )
            })}
        </div>
    )
}