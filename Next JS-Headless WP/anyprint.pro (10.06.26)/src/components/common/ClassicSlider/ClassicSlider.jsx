import styles from './ClassicSlider.module.scss'
import Image from 'next/image'
import { usePrevNextSlide } from '@/hooks/usePrevNextSlide';
import { ClassicTxt } from '../ClassicTxt/ClassicTxt';

export const ClassicSlider = ({sliderData, aspectRatio="", className=""}) => {
    const quantity = sliderData.length;
    const { changeIndex, initPossibilityToMoveSides, index, prev, next } = usePrevNextSlide(quantity);
    return (
        <div className={className}>
            <div className={styles['img-and-nav-box']}>
                <div className={styles['slider-viewport']} style={{aspectRatio: aspectRatio}}>
                    <div 
                        className={styles['slider-track']} 
                        style={{ transform: `translateX(-${index * 100}%)` }}
                    >
                        {sliderData.map((item, slideIndex) => (
                        <div key={`${index}-${slideIndex}`} className={styles['slide']} ref={initPossibilityToMoveSides}>
                            {<Image src={item.img.url} fill sizes='1800px' className={styles['img']} alt={item.img.alt} style={{ pointerEvents: 'none' }}></Image>}
                        </div>
                        ))}
                    </div>
                </div>
                <div className={styles['nav-slider-box']}>
                    <span ref={prev} act="prev" onClick={changeIndex} className={styles['slider-nav-left']}></span>
                    <div className={styles['pagination']}>
                        {[...Array(quantity)].map((_, pagIndex) => (
                            <span
                                key={pagIndex}
                                className={`${styles['pagination-circle']} ${pagIndex === index ? styles.active : ''}`}
                                onClick={() => changeIndex(pagIndex)}
                            />
                        ))}
                    </div>
                    <span ref={next} act="next" onClick={changeIndex} className={styles['slider-nav-right']}></span>
                </div>
            </div>
            <div className={styles['text']}>
                <ClassicTxt text={sliderData[index].txt} />
            </div>
        </div>
    )
}