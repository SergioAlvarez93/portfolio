import { SmallTitle } from '@/components/common/SmallTitle/SmallTitle'
import styles from './AdvantagesCards.module.scss'
import Image from 'next/image'
import { ClassicTxt } from '@/components/common/ClassicTxt/ClassicTxt'

export const AdvantagesCards = ({cards}) => {
    return (
        <div className={styles['cards']}>
            {cards.map((card, index) => {
                return (
                <div key={index} className={styles['card']} style={{backgroundColor: card.bg_color}}>
                    <div className={styles['card-top-line']} style={{backgroundColor: card.line_color}}></div>
                    <div className={styles['card-top-content']}>
                        <div className={styles['card-img-wrap']}>
                            <Image src={card.icon.url} fill sizes='58.26px' className={styles['card-img']} alt={card.icon.alt}></Image> 
                        </div>
                        <SmallTitle className={styles['card-title']} title={card.title} />
                        <div className={styles['card-txt']}>
                            <ClassicTxt text={card.txt} />
                        </div>
                    </div>
                </div>
            )
            })}
        </div>
    )
}