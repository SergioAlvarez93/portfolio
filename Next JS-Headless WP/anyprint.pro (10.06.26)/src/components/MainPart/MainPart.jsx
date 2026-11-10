import styles from "./MainPart.module.scss"
import Image from "next/image"
import { PresentationNotes } from "./PresentationNotes/PresentationNotes"
import { GeneralTitle } from "../common/GeneralTitle/GeneralTitle"
import { PresentationTxt } from "./PresentationTxt/PresentationTxt"
import { PresentationContact } from "./PresentationContact/PresentationContact"
import { RequestBtn } from "../common/RequestBtn/RequestBtn"
import { CalculatorBtn } from "../common/CalculatorBtn/CalculatorBtn"
import { PresentationImage } from "./PresentationImage/PresentationImage"
import { ClassicGeneralTitle } from "../common/ClassicGeneralTitle/ClassicGeneralTitle"
import { useViewportWidth } from "@/hooks/useViewportWidth"
import { AdvertisingLine } from "./AdvertisingLine/AdvertisingLine"
import { ClassicTxt } from "../common/ClassicTxt/ClassicTxt"
import { LazyVideo } from "../common/LazyVideo/LazyVideo"
import { Services } from "./Services/Services"
import { AdvantagesCards } from "./AdvantagesCards/AdvantagesCards"
import { OpportunatiesCards } from "./OpportunatiesCards/OpportunatiesCards"
import { ManufactoringCardSingle } from "./ManufactoringCardSingle/ManufactoringCardSingle"
import { ProductionShowcaseSlider } from "./ProductionShowcaseSlider/ProductionShowcaseSlider"
import { TermoFilter } from "./TermoFilter/TermoFilter"
import { WaterCards } from "./WaterCards/WaterCards"
import { RequestForm } from "../common/RequestForm/RequestForm"
import { ClassicModalWrap } from "../common/ClassicModalWrap/ClassicModalWrap"
import { FormRequestMessage } from "../common/FormRequestMessage/FormRequestMessage"

export const  MainPart = ({marginFromHeader, presentationNotes, presentationTitle, presentationTxt, presentationImgSrc, presentationImgAlt, presentationEmail, presentationTel, advertisingLineTitle, advertisingLineBrands, aboutTitle, aboutSubtitle, aboutTxt, aboutVideoPreviewUrl, aboutVideoPreviewAlt, aboutVideo, servicesTitle, servicesCards, advantagesTitle, advantagesCards, opportunitiesTitle, opportunitiesTxt, opportunitiesCards, opportunitiesVideoPreview, opportunitiesVideo, manufactoringTitle, manufactoringCards, manufactoringImg, manufactoringImgMobile, productionTitle, productionShowcase, termoTitle, termoCategories, waterTitle, waterCards, requestClassic, requestModal, modalId, onModalChange, closeModal, sloganTitle}) => {
    const {width} = useViewportWidth();
    return (
        <>
            <main>
                <section className={styles['presentation']} id="presentation" style={{marginTop: `${marginFromHeader}px`}}>
                    <div className={styles['presentation-container']}>
                        <div className={styles['presentation-grid']}>
                            <div className={styles['presentation-notes']}>
                                {presentationNotes.map((item, index) => {
                                    return <PresentationNotes key={index} color={item.color} title={item.title} subtitle={item.subtitle}></PresentationNotes>
                                })}
                            </div>
                            <div className={styles['presentation-content']}>
                                <GeneralTitle className={styles['presentation-title']} title={presentationTitle}></GeneralTitle>
                                <PresentationTxt text={presentationTxt} className={styles['presentation-txt']}></PresentationTxt>
                                <PresentationContact presentationEmail={presentationEmail} className={styles['presentation-email']}></PresentationContact>
                                <PresentationContact presentationTel={presentationTel} className={styles['presentation-tel']}></PresentationContact>
                                <div className={styles['presentation-btn-box']}>
                                    <RequestBtn onModalChange={onModalChange}></RequestBtn>
                                </div>
                            </div>
                            <div className={styles['presentation-img']}>
                                <PresentationImage src={presentationImgSrc} alt={presentationImgAlt}></PresentationImage>
                            </div>
                        </div>
                    </div>
                </section>
                <section className={styles['advertising-line']}>
                        <div className={styles['advertising-line-title']}>
                            <ClassicGeneralTitle title={advertisingLineTitle}></ClassicGeneralTitle>
                        </div>
                        <div className={styles['advertising-line-container']}>
                            {width === "desktop" ? (
                                <div className={styles['track-wrapper']}>
                                    <div className={styles['brands-track']}>
                                        <AdvertisingLine brands={advertisingLineBrands} />
                                        <AdvertisingLine brands={advertisingLineBrands} />
                                    </div>
                                </div>
                            ) : (                                
                                <div className={styles['brands-grid']}>
                                    <AdvertisingLine brands={advertisingLineBrands} />
                                </div>
                            )}
                        </div>
                </section>
                <section className={styles['about']} id="about">
                    <div className={styles['about-container']}>
                        <div className={styles['about-flex']}>
                            <div className={styles['about-flex-content']}>
                                <div className={styles['about-title']}>
                                    <ClassicGeneralTitle title={aboutTitle}></ClassicGeneralTitle>
                                </div>
                                <div className={styles['about-subtitle']}>
                                    <ClassicTxt text={aboutSubtitle}></ClassicTxt>
                                </div>
                                <div className={styles['about-txt']}>
                                    <ClassicTxt text={aboutTxt}></ClassicTxt>
                                </div>
                            </div>
                            <div className={styles['about-video-box']}>
                                <LazyVideo className={styles['about-video-preview-img-wrap']} poster={aboutVideoPreviewUrl} posterAlt={aboutVideoPreviewAlt} video={aboutVideo}></LazyVideo>
                            </div>
                        </div>
                    </div>
                </section>
                <section className={styles['services']} id="services">
                    <div className={styles['services-container']}>
                        <div className={styles['services-title']}>
                            <ClassicGeneralTitle title={servicesTitle} />
                        </div>
                        <Services services={servicesCards}></Services>
                    </div>
                </section>
                <section className={styles['advantages']}>
                    <div className={styles['advantages-container']}>
                        <div className={styles['advantages-title']}>
                            <ClassicGeneralTitle title={advantagesTitle} />  
                        </div>
                        <AdvantagesCards cards={advantagesCards} />
                    </div>        
                </section>
                <section className={styles['opportunaties']} id='opportunaties'>
                    <div className={styles['opportunaties-container']}>
                        <div className={styles['opportunaties-flexbox']}>
                            <div className={styles['opportunaties-content']}>
                                <div className={styles['opportunaties-title']}>
                                    <ClassicGeneralTitle title={opportunitiesTitle} />
                                </div>
                                <div className={styles['opportunaties-txt']}>
                                    <ClassicTxt text={opportunitiesTxt} />
                                </div>
                                <OpportunatiesCards cards={opportunitiesCards}/>
                            </div>
                            <div className={styles['opportunaties-video_wrap']}>
                                <LazyVideo className={styles['opportunaties-video'] } video={opportunitiesVideo} poster={opportunitiesVideoPreview.url} posterAlt={opportunitiesVideoPreview.alt}/>
                            </div>
                        </div>
                    </div>
                </section>
                <section className={styles['manufactoring']} id="manufactoring">
                    <div className={styles['manufactoring-container']}>
                        <div className={styles['manufactoring-title']}>
                            <ClassicGeneralTitle title={manufactoringTitle} />
                        </div>
                        <div className={styles['manufactoring-grid']}>
                            <div className={styles['manufactoring-cards']}>
                                {manufactoringCards.map((card, index) => {
                                    return (
                                        <ManufactoringCardSingle key={index} card={card} index={index} />
                                    )
                                })}
                            </div>
                            <div className={styles['manufactoring-img-wrap']}>
                                {width === 'desktop' ? (
                                    <Image src={manufactoringImg.url} fill sizes="800px" className={styles['manufactoring-img']} alt={manufactoringImg.alt}></Image>
                                ) : (
                                    <Image src={manufactoringImgMobile.url} fill sizes="800px" className={styles['manufactoring-img']} alt={manufactoringImgMobile.alt}></Image>
                                )}
                                
                            </div>
                        </div>
                    </div>
                </section>
                <section className={styles['productrion']} id="production">
                    <div className={styles['productrion-container']}>
                        <div className={styles['productrion-title']}>
                            <ClassicGeneralTitle title={productionTitle} />
                        </div>
                        <ProductionShowcaseSlider data={productionShowcase} />
                    </div>
                </section>
                <section className={styles['termo']}>
                    <div className={styles['termo-container']}>
                        <div className={styles['termo-title']}>
                            <ClassicGeneralTitle title={termoTitle} />
                        </div>
                        <TermoFilter cat={termoCategories} />
                    </div>
                </section>
                <section className={styles['water']}>
                    <div className={styles['water-container']}>
                        <div className={styles['water-title']}>
                            <ClassicGeneralTitle title={waterTitle} />
                        </div>
                        <div className={styles['water-cards-box']}>
                            <WaterCards data={waterCards} />
                        </div>
                    </div>
                </section>
                <section className={styles['request']} id="request">
                    <div className={styles['request-container']}>
                        <div className={styles['requset-subtitle']}>
                            <ClassicTxt text={requestClassic.subtitle} />
                        </div>
                        <div className={styles['requset-title']}>
                            <ClassicGeneralTitle title={requestClassic.title} />
                        </div>
                        {modalId === 'modalRequest' && 
                            <ClassicModalWrap maxWidth='450' closeModal={closeModal}>
                                    <RequestForm modal={true} modalTitle={requestModal.title} formId="modalRequestForm" onModalChange={onModalChange}></RequestForm>
                            </ClassicModalWrap>
                        }
                        {(modalId === 'formSuccessful' || modalId === 'formError') && 
                            <FormRequestMessage modalId={modalId} closeModal={closeModal} />
                        }
                        <RequestForm modal={false} modalTitle={requestModal.title} maxWidth="450" formId="genaralRequestForm" onModalChange={onModalChange}></RequestForm>
                    </div>
                </section>
                <section className={styles['slogan']}>
                    <div className={styles['slogan-container']}>
                        <div className={styles['slogan-box']}>
                            <div className={styles['slogan-title']}>
                                <ClassicGeneralTitle title={sloganTitle} />
                            </div>
                            <RequestBtn onModalChange={onModalChange}></RequestBtn>
                        </div>
                    </div>
                </section>
            </main>
        </>
    )
}