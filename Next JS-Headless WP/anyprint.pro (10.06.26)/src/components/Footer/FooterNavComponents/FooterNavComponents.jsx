import { useState, useEffect } from 'react'
import styles from './FooterNavComponents.module.scss'
import Link from 'next/link';
import { ClassicModalWrap } from '@/components/common/ClassicModalWrap/ClassicModalWrap';
import { ClassicGeneralTitle } from '@/components/common/ClassicGeneralTitle/ClassicGeneralTitle';
import { ClassicTxt } from '@/components/common/ClassicTxt/ClassicTxt';

export const FooterNavCompoments = ({data, state, width, containerWidth, elementWidth, onModalChange, closeModal, modalId}) => {
    const [currState, setState] = useState(null);
         useEffect(() => {
            if (currState !== state) setState(state);
        }, [state]);
    return (
        <div className={styles['footer-nav-component']} style={{flex: `0 0 ${containerWidth}`}}>
            {data.map((item, itemIndex) => {
                return (
                    <div key={itemIndex} className={styles['footer-nav-component-links']} style={{flex: `0 0 ${elementWidth}`}}>
                        {state === 'default' && (
                            <>
                            {width === 'mobile' && ( 
                                <div className={styles['footer-nav-component-main-link-wrap']}>
                                    <Link href={item.main_nav_part_link} className={styles['footer-nav-component-link']}>{item.main_nav_part}</Link>
                                </div>
                            )}
                            {item.sub_links_menu.map((link, linkIndex) => {
                                return (
                                    <div key={linkIndex} className={styles['footer-nav-component-link-wrap']}>
                                        <Link href={link.sub_links_menu} className={styles['footer-nav-component-link']}>{link.sub_links_menu_txt}</Link>
                                    </div>
                                )    
                            })}
                            </>
                        )}
                        {state === 'privateDocs' && (
                            <>
                            {width === 'mobile' && ( 
                                <div className={styles['footer-nav-component-main-link-wrap']}>
                                    <span className={styles['footer-nav-component-link']}>СЕРТИФИКАТЫ</span>
                                </div>
                            )}
                                <div className={styles['footer-nav-component-link-wrap']}>
                                    <span className={styles['footer-nav-component-link']} onClick={() => onModalChange('privatePolicy')}>Политика конфиденциальности</span>
                                </div>
                                <div className={styles['footer-nav-component-link-wrap']}>
                                    <span className={styles['footer-nav-component-link']} onClick={() => onModalChange('publicOffer')}>Оферта</span>
                                </div>
                            </>
                        )}
                        {state === 'contacts' && (
                           <>
                            {width === 'mobile' && ( 
                                <div className={styles['footer-nav-component-main-link-wrap']}>
                                    <span className={styles['footer-nav-component-link']}>КОНТАКТЫ</span>
                                </div>
                            )}
                                <div className={styles['footer-nav-component-link-wrap']}>
                                    <Link href={`mailto:${item.email}`} className={styles['footer-nav-component-link']}>{item.email}</Link>
                                </div>
                                <div className={styles['footer-nav-component-link-wrap']}>
                                    <Link href={`tel:${item.tel.replace(/[^0-9+]/g, '')}`} className={styles['footer-nav-component-link']}>{item.tel}</Link>
                                </div>
                                <div className={styles['footer-nav-component-link-wrap']}>
                                    <span className={`${styles['footer-nav-component-link']} ${styles['no-link-hover']}`}>{item.adress}</span>
                                </div>
                            </>                         
                        )}
                    </div>
                )
            })}
            {modalId === 'privatePolicy' && 
                <ClassicModalWrap maxWidth='1600' closeModal={closeModal} padding={{padding: `40px 25px`}}>
                    <div className={styles['modal-title']}>
                        <ClassicGeneralTitle title={data[0].privatePolicy.title} />
                    </div>
                    <div className={styles['modal-txt']}>
                        <ClassicTxt text={data[0].privatePolicy.text} />
                    </div>
                </ClassicModalWrap>  
            }
            {modalId === 'publicOffer' && 
                <ClassicModalWrap maxWidth='1600' closeModal={closeModal} padding={{padding: `40px 25px`}}>
                    <div className={styles['modal-title']}>
                        <ClassicGeneralTitle title={data[0].publicOffer.title} />
                    </div>
                    <div className={styles['modal-txt']}>
                        <ClassicTxt text={data[0].publicOffer.text} />
                    </div>
                </ClassicModalWrap>  
            }
        </div>
        
    )
}