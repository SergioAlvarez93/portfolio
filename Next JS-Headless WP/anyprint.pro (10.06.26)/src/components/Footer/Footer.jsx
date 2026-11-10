import { ClassicTxt } from '../common/ClassicTxt/ClassicTxt'
import { RequestBtn } from '../common/RequestBtn/RequestBtn'
import styles from './Footer.module.scss'
import { FooterAdvertLine } from './FooterAdvertLine/FooterAdvertLine'
import Image from 'next/image'
import { useViewportWidth } from '@/hooks/useViewportWidth'
import { Messangers } from '../common/Messangers/Messangers'
import Link from 'next/link'
import { FooterNavCompoments } from './FooterNavComponents/FooterNavComponents'

export const Footer = ({footerAdvertLine, footerLogo, footerTxt, onModalChange, messangers, footerNav, confidentialTitle, confidentialTxt, publicOfferTitle, publicOfferTxt, closeModal, modalId, email, tel, adress}) => {
    const {width} = useViewportWidth();

    const privatePolicy = {
        title: confidentialTitle, text: confidentialTxt
    }
    
    const PublicOffer = {
        title: publicOfferTitle, text: publicOfferTxt
    }

    const privatePolicyAndPublicOffer = {
        privatePolicy: { ...privatePolicy },
        publicOffer: { ...PublicOffer }
    };

    const privatePolicyAndPublicOfferData = [privatePolicyAndPublicOffer] 

    const contacts = [
        {
            email: email,
            tel: tel,
            adress: adress
        }
    ];
    
    return (
        <footer className={styles['footer']} id='footer'>
            <div className={styles['footer-container']}>
                <div className={styles['advertising-line-container']}>   
                    <div className={styles['track-wrapper']}>
                        <div className={styles['brands-track']}>
                            <FooterAdvertLine brands={footerAdvertLine} />
                            <FooterAdvertLine brands={footerAdvertLine} />
                            <FooterAdvertLine brands={footerAdvertLine} />
                            <FooterAdvertLine brands={footerAdvertLine} />
                        </div>
                    </div>
                </div>
                <div className={styles['footer-inner-container']}>
                    <div className={styles['footer-flex']}>
                        <div className={styles['logo-wrap']}>
                            <Image src={footerLogo.url} className={styles['logo']} fill sizes='300px' alt={footerLogo.alt} />
                        </div>
                        <div className={styles['footer-txt']}>
                            <ClassicTxt text={footerTxt} />
                        </div>
                        <div className={styles['footer-btn']}>
                            <RequestBtn onModalChange={onModalChange} />
                        </div>
                        {width === 'desktop' && 
                            <div className={styles['footer-socials']}>
                                <Messangers messangers={messangers}></Messangers>
                            </div>
                        }
                    </div>
                    <div className={styles['footer-nav']}>
                        {width === 'desktop' && 
                            <div className={styles['footer-nav-desktop-top']}>
                                {footerNav.map((item, index) => {
                                    return (
                                        <div key={index} className={styles['footer-nav-desktop-top-item']}>
                                            <Link href={item.main_nav_part_link} className={styles['footer-nav-desktop-top-item-link']}>{item.main_nav_part}</Link>
                                        </div>
                                    )
                                })}
                                <div className={styles['footer-nav-desktop-top-item']}>
                                    <span className={styles['footer-nav-desktop-top-item-link']}>СЕРТИФИКАТЫ</span>
                                </div>
                                <div className={styles['footer-nav-desktop-top-item']}>
                                    <span className={styles['footer-nav-desktop-top-item-link']}>КОНТАКТЫ</span>
                                </div>
                            </div>
                        }
                        <div className={styles['footer-nav-bottom']}>
                            <FooterNavCompoments data={footerNav} state='default' width={width} containerWidth='50%' elementWidth="50%"/>
                            <FooterNavCompoments data={privatePolicyAndPublicOfferData} state='privateDocs' width={width} containerWidth='25%' elementWidth="100%" onModalChange={onModalChange} closeModal={closeModal} modalId={modalId}/>
                            <FooterNavCompoments data={contacts} state='contacts' width={width} containerWidth='25%' elementWidth="100%"/>
                        </div>
                        {width === 'mobile' && 
                            <div className={styles['footer-socials']}>
                                <Messangers messangers={messangers}></Messangers>
                            </div>
                        }
                    </div>
                </div>
            </div>
        </footer>
    )
}