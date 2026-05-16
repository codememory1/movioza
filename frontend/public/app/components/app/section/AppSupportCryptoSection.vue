<template>
  <section class="app-support-crypto-section" :aria-labelledby="id">
    <h2 :id class="app-support-crypto-section__header">Поддержать проект</h2>
    <div class="app-support-crypto-section__descriptions">
      <p>
        {{ config.public.appShortTitle }} остаётся бесплатным и без рекламы благодаря поддержке
        пользователей.
      </p>
      <p>
        Если вам нравится сервис и вы хотите помочь развитию проекта, вы можете отправить
        криптовалюту на один из адресов ниже.
      </p>
    </div>
    <dl class="app-support-crypto-section__list">
      <div class="app-support-crypto-section__item">
        <dt>Bitcoin</dt>
        <dd>{{ config.public.btcAddress }}</dd>
      </div>
      <div class="app-support-crypto-section__item">
        <dt>Litecoin</dt>
        <dd>{{ config.public.ltcAddress }}</dd>
      </div>
      <div class="app-support-crypto-section__item">
        <dt>USDT(TRC20)</dt>
        <dd>{{ config.public.usdtTrc20Address }}</dd>
      </div>
    </dl>
    <div class="app-support-crypto-section__footer">
      <span>Спасибо за поддержку ❤️</span>
    </div>
  </section>
</template>

<script lang="ts" setup>
const config = useRuntimeConfig()
const id = useId()
</script>

<style lang="scss">
@use './styles/AppSupportCryptoSection.styles' as *;
</style>
