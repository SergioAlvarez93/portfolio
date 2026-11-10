import { useSmoothScroll } from "@/hooks/useSmoothScroll";
import "@/styles/globals.scss";

export default function App({ Component, pageProps }) {
  useSmoothScroll();
  return <Component {...pageProps} />;
}
