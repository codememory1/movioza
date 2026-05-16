import { routes } from './app/router'

export default defineNuxtConfig({
  modules: ['@nuxt/fonts', '@nuxt/image', '@pinia/nuxt', '@nuxt/eslint'],
  plugins: ['~/plugins/api.ts'],
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  pinia: {
    storesDirs: ['./app/costores/*']
  },
  css: ['~/assets/scss/main.scss', 'keen-slider/keen-slider.min.css'],
  fonts: {
    families: [
      {
        name: 'Inter',
        provider: 'google',
        weights: [400, 700, 900]
      }
    ]
  },
  image: {
    domains: ['image.tmdb.org']
  },
  runtimeConfig: {
    public: {
      appUrl: process.env.NUXT_PUBLIC_APP_URL,
      appShortTitle: process.env.NUXT_PUBLIC_APP_SHORT_TITLE,
      appHeaderLogo: process.env.NUXT_PUBLIC_APP_HEADER_LOGO,
      btcAddress: process.env.NUXT_PUBLIC_APP_BTC_ADDRESS,
      ltcAddress: process.env.NUXT_PUBLIC_APP_LTC_ADDRESS,
      usdtTrc20Address: process.env.NUXT_PUBLIC_APP_USDT_TRC20_ADDRESS,
      apiUrl: process.env.NUXT_PUBLIC_API_URL,
      appFullTitle: process.env.NUXT_PUBLIC_APP_FULL_TITLE,
      appDescription: process.env.NUXT_PUBLIC_APP_DESCRIPTION
    }
  },
  app: {
    // cdnURL: 'http://127.0.0.1:48080',
    head: {
      htmlAttrs: {
        lang: process.env.NUXT_PUBLIC_APP_LANG
      },
      title: process.env.NUXT_PUBLIC_APP_FULL_TITLE,
      meta: [
        {
          name: 'description',
          content: process.env.NUXT_PUBLIC_APP_DESCRIPTION
        },
        {
          name: 'keywords',
          content: process.env.NUXT_PUBLIC_APP_KEYWORDS
        },
        {
          name: 'robots',
          content: process.env.NUXT_PUBLIC_APP_ROBOTS
        },
        {
          name: 'viewport',
          content: 'width=device-width, initial-scale=1.0'
        },
        {
          name: 'apple-mobile-web-app-title',
          content: process.env.NUXT_PUBLIC_APPLE_MOBILE_WEB_APP_TITLE
        },
        {
          name: 'application-name',
          content: process.env.NUXT_PUBLIC_APP_NAME
        },
        {
          name: 'referrer',
          content: process.env.NUXT_PUBLIC_REFERRER
        },
        {
          'http-equiv': 'x-dns-prefetch-control',
          content: process.env.NUXT_PUBLIC_X_DNS_PREFETCH_CONTROL
        },
        {
          name: 'msapplication-TileColor',
          content: process.env.NUXT_PUBLIC_MSAPPLICATION_TILE_COLOR
        },
        {
          name: 'msapplication-tile-image',
          content: process.env.NUXT_PUBLIC_MSAPPLICATION_TILE_IMAGE
        },
        {
          name: 'theme-color',
          content: process.env.NUXT_PUBLIC_THEME_COLOR
        },
        {
          name: 'google-site-verification',
          content: process.env.NUXT_PUBLIC_GOOGLE_SITE_VERIFICATION
        }
      ],
      link: [
        {
          rel: 'search',
          type: 'application/opensearchdescription+xml',
          href: process.env.NUXT_PUBLIC_OPENSEARCH_URL,
          title: process.env.NUXT_PUBLIC_OPENSEARCH_TITLE
        },
        {
          rel: 'alternate',
          type: 'application/rss+xml',
          href: process.env.NUXT_PUBLIC_RSS
        },
        {
          rel: 'manifest',
          href: process.env.NUXT_PUBLIC_MANIFEST
        },
        {
          rel: 'shortcut icon',
          type: process.env.NUXT_PUBLIC_SHORTCUT_ICON_TYPE,
          href: process.env.NUXT_PUBLIC_SHORTCUT_ICON
        },
        {
          rel: 'icon',
          sizes: '16x16',
          href: process.env.NUXT_PUBLIC_ICON_16
        },
        {
          rel: 'icon',
          sizes: '32x32',
          href: process.env.NUXT_PUBLIC_ICON_32
        },
        {
          rel: 'apple-touch-icon',
          sizes: '180x180',
          href: process.env.NUXT_PUBLIC_APPLE_TOUCH_ICON_180
        },
        {
          rel: 'mask-icon',
          href: process.env.NUXT_PUBLIC_MASK_ICON
        },
        ...(process.env.NUXT_PUBLIC_DNS_PREFETCH ?? '').split(',')!.map((url) => ({
          rel: 'dns-prefetch',
          href: url.trim()
        }))
      ]
    }
  },

  vite: {
    optimizeDeps: {
      include: ['qs', 'lodash', '@vueuse/core', 'fast-blurhash']
    }
  },
  hooks: {
    'pages:extend'(pages) {
      routes.forEach((route) => {
        pages.push(route)
      })
    }
  }
})
