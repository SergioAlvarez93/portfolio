import { ClassicTxt } from '@/components/common/ClassicTxt/ClassicTxt'
import styles from './OpportunatiesCards.module.scss'
import Image from 'next/image'

export const OpportunatiesCards = ({cards}) => {
    return (
        <div className={styles['cards']}>
            {cards.map((card, index) => {
                return (
                    <div key={index} className={styles['card']}>
                        <div className={styles['card-icon-wrap']}>
                            <Image src={card.icon.url} fill sizes='38.06px' className={styles['card-icon']} alt={card.icon.alt}></Image>
                        </div>
                        <div className={styles['card-txt']}>
                            <ClassicTxt text={card.txt} />
                        </div>
                    </div>
                )
            })}
        </div>
    )
}