<template>
  <section class="app-platform-benefits" :aria-labelledby="id">
    <h2 :id class="visually-hidden">Преимущества онлайн-кинотеатра</h2>

    <ul class="app-platform-benefits__list">
      <li class="app-platform-benefits__item app-platform-benefits__item--free">
        <div class="app-platform-benefits__item-icon" aria-hidden="true">
          <ACheckIcon />
        </div>
        <div class="app-platform-benefits__item-content">
          <strong class="app-platform-benefits__item-title">100% Бесплатно</strong>
          <span class="app-platform-benefits__item-text">Вся библиотека</span>
        </div>
      </li>
      <li class="app-platform-benefits__item app-platform-benefits__item--no-register">
        <div class="app-platform-benefits__item-icon" aria-hidden="true">
          <ALightningOutlineIcon />
        </div>
        <div class="app-platform-benefits__item-content">
          <strong class="app-platform-benefits__item-title">Без регистрации</strong>
          <span class="app-platform-benefits__item-text">Смотрите сразу</span>
        </div>
      </li>
      <li class="app-platform-benefits__item app-platform-benefits__item--no-ads">
        <div class="app-platform-benefits__item-icon" aria-hidden="true">
          <AShieldIcon />
        </div>
        <div class="app-platform-benefits__item-content">
          <strong class="app-platform-benefits__item-title">Без рекламы</strong>
          <span class="app-platform-benefits__item-text">Чистый просмотр</span>
        </div>
      </li>
    </ul>
  </section>
</template>

<script lang="ts" setup>
import ACheckIcon from '~/components/ui/icon/ACheckIcon.vue'
import ALightningOutlineIcon from '~/components/ui/icon/ALightningOutlineIcon.vue'
import AShieldIcon from '~/components/ui/icon/AShieldIcon.vue'

const id = useId()
</script>

<style lang="scss">
@use './styles/AppPlatformBenefits.styles' as *;
</style>
