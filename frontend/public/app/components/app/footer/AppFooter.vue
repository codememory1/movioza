<template>
  <footer class="app-footer">
    <AContainer class="app-footer__container">
      <div class="app-footer__top">
        <section class="app-footer__brand" :aria-label="`О ${appTitle}`">
          <NuxtLink to="/" class="app-footer__logo" :aria-label="`${appTitle} — на главную`">
            <img src="/images/logo.png" :alt="appTitle" />
            <span>{{ appTitle }}</span>
          </NuxtLink>

          <p>
            Смотреть фильмы и сериалы онлайн в хорошем качестве. Новинки, популярные жанры и
            подборки для удобного поиска.
          </p>
        </section>

        <AppFooterNavigation
          header="Навигация"
          :items="[
            {
              to: AppRoutes.serialy(),
              text: 'Сериалы'
            },
            {
              to: AppRoutes.filmy(),
              text: 'Фильмы'
            },
            {
              to: AppRoutes.collections(),
              text: 'Коллекции'
            }
          ]"
        />
        <AppFooterNavigation
          header="Жанры"
          :items="
            genres.map((genre) => ({
              to: `/filters/genre/${genre.slug}`,
              text: genre.name
            }))
          "
        />
        <AppFooterNavigation
          header="Подборки"
          :items="[
            {
              to: '/',
              text: 'Фильмы 2026'
            },
            {
              to: '/',
              text: 'Топ-100'
            },
            {
              to: '/',
              text: 'Топ-100'
            },
            {
              to: '/',
              text: 'Новинки'
            },
            {
              to: '/',
              text: 'Марвел'
            },
            {
              to: '/',
              text: 'Ужасы'
            }
          ]"
        />
        <AppFooterNavigation
          header="Информация"
          :items="[
            {
              to: '/about',
              text: 'О сервисе'
            },
            {
              to: '/feedback',
              text: 'Обратная связь'
            },
            {
              to: '/copyright',
              text: 'Правообладателям'
            },
            {
              to: '/privacy',
              text: 'Политика конфиденциальности'
            },
            {
              to: '/terms',
              text: 'Условия использования'
            }
          ]"
        />
      </div>
      <div class="app-footer__bottom">
        <p>
          © {{ new Date().getFullYear() }} {{ appTitle }}. Все права на материалы принадлежат их
          владельцам.
        </p>
      </div>
    </AContainer>
  </footer>
</template>

<script lang="ts" setup>
import AContainer from '~/components/ui/container/AContainer.vue'
import AppFooterNavigation from '~/components/app/footer/AppFooterNavigation.vue'
import type { GenreShortModel } from '#shared/types/genre'
import { AppRoutes } from '#shared/router/routes'

export interface AppFooterProps {
  genres: GenreShortModel[]
}

defineProps<AppFooterProps>()

const config = useRuntimeConfig()
const appTitle = computed<string>(() => config.public.appShortTitle)
</script>

<style lang="scss">
@use './styles/AppFooter.styles' as *;
</style>
