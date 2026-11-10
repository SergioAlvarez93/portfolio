import { ClassicSlider } from '@/components/common/ClassicSlider/ClassicSlider';
import styles from './ProductionShowcaseSlider.module.scss'
import { usePrevNextSlide } from '@/hooks/usePrevNextSlide';

export const ProductionShowcaseSlider = ({data}) => {
    const quantity = data.length;
    const {changeIndex, index, prev, next} = usePrevNextSlide(quantity);
    return (
        <>
            <div className={styles['production-showcase-nav']}>
                <span ref={prev} act="prev" onClick={changeIndex} className={styles['production-showcase-nav-left']}></span>
                <span ref={next} act="next" onClick={changeIndex} className={styles['production-showcase-nav-right']}></span>
            </div>            
                <div className={styles['production-showcase']} style={{backgroundColor: data[index].production_showcase_bg_color}}>
                    {data[index].production_example.map((variation, variationIndex) => {
                        return (
                            <ClassicSlider key={`${index}-${variationIndex}`} className={styles['production-showcase-variation']} sliderData={variation.production_example_variation} aspectRatio='5 / 4' />
                        )
                    })}
                </div> 
        </>
    );  
}