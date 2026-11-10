import Head from "next/head";
import Image from "next/image";
import { Header } from "@/components/Header/Header";
import { MainPart } from "@/components/MainPart/MainPart";
import { getPageData } from "./api/api";
import { useFirstSectionMargin } from "@/hooks/useFirstSectionMargin";
import { useModalScreen } from "@/hooks/useModalScreen";
import { Footer } from "@/components/Footer/Footer";

export default function Home({ pageData }) {
  const { headerRef, height } = useFirstSectionMargin();
  const requestClassic = {
    title: pageData.request_title,
    subtitle: pageData.request_subtitle,
  };
  const { modalId, changeModalId, closeModal } = useModalScreen();
  return (
    <>
      <Header
        src={pageData.logo[1].img.url}
        alt={pageData.logo[1].img.alt}
        homepage={pageData.homepage}
        menu={pageData.header_menu}
        messangers={pageData.messangers}
        marginRef={headerRef}
        onModalChange={changeModalId}
      ></Header>
      <MainPart
        marginFromHeader={height}
        presentationNotes={pageData.presentation_notes}
        presentationTitle={pageData.presentation_title}
        presentationTxt={pageData.presentation_txt}
        presentationImgSrc={pageData.presentation_img.url}
        presentationImgAlt={pageData.presentation_img.alt}
        presentationEmail={pageData.email}
        presentationTel={pageData.tel}
        advertisingLineTitle={pageData.advertising_line_title}
        advertisingLineBrands={pageData.advertising_line_brands}
        aboutTitle={pageData.about_title}
        aboutSubtitle={pageData.about_subtitle}
        aboutTxt={pageData.about_txt}
        aboutVideoPreviewUrl={pageData.about_video_preview.url}
        aboutVideoPreviewAlt={pageData.about_video_preview.alt}
        aboutVideo={pageData.about_video}
        servicesTitle={pageData.services_title}
        servicesCards={pageData.services_cards}
        advantagesTitle={pageData.advantages_title}
        advantagesCards={pageData.advantages_cards}
        opportunitiesTitle={pageData.opportunities_title}
        opportunitiesTxt={pageData.opportunities_txt}
        opportunitiesCards={pageData.opportunities_cards}
        opportunitiesVideoPreview={pageData.opportunities_video_preview}
        opportunitiesVideo={pageData.opportunities_video}
        manufactoringTitle={pageData.manufactoring_title}
        manufactoringCards={pageData.manufactoring_cards}
        manufactoringImg={pageData.manufactoring_img}
        manufactoringImgMobile={pageData.manufactoring_img_mobile}
        productionTitle={pageData.production_title}
        productionShowcase={pageData.production_showcase}
        termoTitle={pageData.termo_title}
        termoCategories={pageData.termo_categories}
        waterTitle={pageData.water_title}
        waterCards={pageData.water_cards}
        requestClassic={requestClassic}
        requestModal={{ title: pageData.request_title_modal }}
        modalId={modalId}
        onModalChange={changeModalId}
        closeModal={closeModal}
        sloganTitle={pageData.slogan_title}
      ></MainPart>
      <Footer
        footerAdvertLine={pageData.footer_advert_line}
        footerLogo={pageData.logo[0].img}
        footerTxt={pageData.footer_txt}
        messangers={pageData.messangers}
        onModalChange={changeModalId}
        footerNav={pageData.footer_nav}
        confidentialTitle={pageData.confidential_title}
        confidentialTxt={pageData.confidential_txt}
        publicOfferTitle={pageData.public_offer_title}
        publicOfferTxt={pageData.public_offer_txt}
        closeModal={closeModal}
        modalId={modalId}
        email={pageData.email}
        tel={pageData.tel}
        adress={pageData.adress}
      ></Footer>
    </>
  );
}

export async function getStaticProps() {
  const pageData = await getPageData("sample-page");
  return {
    props: {
      pageData,
    },
    revalidate: 3600, // необязательно, для ISR
  };
}
