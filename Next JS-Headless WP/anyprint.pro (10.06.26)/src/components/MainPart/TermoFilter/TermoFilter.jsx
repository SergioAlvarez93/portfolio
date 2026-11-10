import { ClassicSlider } from '@/components/common/ClassicSlider/ClassicSlider';
import styles from './TermoFilter.module.scss'
import { TermoFilterBtn } from '../TermoFilterBtn/TermoFilterBtn';
import { useState } from 'react';

export const TermoFilter = ({cat}) => {
    const catTitles = cat.map((item) => item.termo_cat_name);
    const [activeTab, setActiveTab] = useState(0);
    return (
        <div className={styles['termo-slider-box']}>
            <ClassicSlider key={activeTab} className={styles['termo-slider']} sliderData={cat[activeTab].termo_variation} aspectRatio='469 / 331' />
            <div className={styles['termo-slider-filter']}>
                {catTitles.map((title, catTitleIndex) => {
                    return (
                        <TermoFilterBtn key={catTitleIndex} onClick={() => setActiveTab(catTitleIndex)} isActiveTab={catTitleIndex === activeTab}>{title}</TermoFilterBtn>
                    )
                })}
            </div>
        </div>
    )
}