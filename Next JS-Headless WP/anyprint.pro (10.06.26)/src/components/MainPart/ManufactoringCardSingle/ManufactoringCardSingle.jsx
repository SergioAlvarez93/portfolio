import { SmallTitle } from '@/components/common/SmallTitle/SmallTitle'
import styles from './ManufactoringCardSingle.module.scss'
import { ClassicTxt } from '@/components/common/ClassicTxt/ClassicTxt'

export const ManufactoringCardSingle = ({card, index}) => {
    return (
        <div className={styles['card-flex']}>       
            <div className={styles['card-left-line']} style={{backgroundColor: card.bg_color}}></div>
            <div className={styles['card-content']}>
                <div className={styles['card-content-flex']}>
                    <div className={styles['card-nubmer-wrap']} style={{backgroundColor: card.number_bg}}>
                        <span className={styles['card-number']} style={{color: card.bg_color}}>{index + 1}</span>
                    </div>
                    <div className={styles['card-title']}>
                        <SmallTitle title={card.title} />
                    </div>
                </div>
                <div className={styles['card-txt']}>
                    <ClassicTxt text={card.txt} />
                </div>
            </div>  
        </div>
    )
}