import path from "path";
/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  allowedDevOrigins: ["localhost", "127.0.0.1", "192.168.56.1"],
  sassOptions: {
    additionalData: `@use "@/styles/mixins" as *;`,
  },
  images: {
    remotePatterns: [
      {
        protocol: "https",
        hostname: "anyprint.pro",
        port: "",
        pathname: "/wp/wp-content/uploads/**",
      },
    ],
  },
};

export default nextConfig;
