import { getPages } from "./api/api";

export default function Test({ pages }) {
  return <pre>{JSON.stringify(pages, null, 2)}</pre>;
}

export async function getStaticProps() {
  const pages = await getPages();
  return { props: { pages } };
}
