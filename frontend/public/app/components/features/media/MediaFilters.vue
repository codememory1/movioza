<template>
  <aside class="media-filters" aria-label="Фильтры каталога">
    <h2 class="media-filters__title">Фильтры</h2>

    <div class="media-filters__groups">
      <MediaFiltersGroup header="Жанры" class="media-filters-group--genres">
        <template #search>
          <AInput v-model="searchGenre" placeholder="Поиск жанров..." :prepend-icon="ASearchIcon" />
        </template>
        <template #list>
          <AVirtualScroll
            :items="filteredGenres"
            :options="{ itemHeight: 24 }"
            id-field="id"
            v-slot="{ item }: { item: GenreModel }"
            class="media-filters-group__options"
          >
            <ACheckbox
              :model-value="filters.genres.includes(item.slug)"
              :ariaLabel="item.name"
              :label="item.name"
              @update:model-value="onToggleGenre(item)"
            />
          </AVirtualScroll>
        </template>
      </MediaFiltersGroup>

      <div class="media-filters__divider" />

      <MediaFiltersGroup header="Страны" class="media-filters-group--countires">
        <template #search>
          <AInput
            v-model="searchCountry"
            placeholder="Поиск стран..."
            :prepend-icon="ASearchIcon"
          />
        </template>
        <template #list>
          <AVirtualScroll
            :items="filteredCountries"
            id-field="id"
            :options="{ itemHeight: 24 }"
            v-slot="{ item }: { item: CountryModel }"
            class="media-filters-group__options"
          >
            <ACheckbox
              :model-value="filters.countries.includes(item.code)"
              :ariaLabel="item.name"
              :label="item.name"
              @update:model-value="onToggleCountry(item)"
            />
          </AVirtualScroll>
        </template>
      </MediaFiltersGroup>
    </div>

    <div class="media-filters__actions">
      <button
        class="media-filters__action-btn media-filters__action-btn--reset"
        :class="{ 'media-filters__action-btn--loading': resetLoading }"
        type="button"
        :disabled="acceptLoading"
        @click="onReset"
      >
        <template v-if="!resetLoading">Сбросить</template>
        <ALoaderCircleIcon v-else />
      </button>
      <button
        class="media-filters__action-btn media-filters__action-btn--accept"
        :class="{ 'media-filters__action-btn--loading': acceptLoading }"
        type="button"
        :disabled="resetLoading"
        @click="onAccept"
      >
        <template v-if="!acceptLoading">Применить</template>
        <ALoaderCircleIcon v-else />
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import MediaFiltersGroup from '~/components/features/media/MediaFiltersGroup.vue'
import ACheckbox from '~/components/ui/checkbox/ACheckbox.vue'
import AInput from '~/components/ui/input/AInput.vue'
import { useCountries } from '~/composables/useCountries'
import { useGenres } from '~/composables/useGenres'
import ASearchIcon from '~/components/ui/icon/ASearchIcon.vue'
import type { GenreModel } from '#shared/types/genre'
import type { CountryModel } from '#shared/types/country'
import AVirtualScroll from '~/components/ui/scroll/AVirtualScroll.vue'
import type { MediaListFiltersModel } from '#shared/types/media'
import ALoaderCircleIcon from '~/components/ui/icon/ALoaderCircleIcon.vue'
import { toggleArrayValue } from '#shared/utils/array.utils'
import { useMediaFilters } from '~/composables/useMediaFilters'

export interface MediaFiltersProps {
  resetLoading?: boolean
  acceptLoading?: boolean
}

export interface MediaFiltersEmits {
  reset: []
  accept: [filters: MediaListFiltersModel]
}

defineProps<MediaFiltersProps>()

const emit = defineEmits<MediaFiltersEmits>()

const { data: genres } = await useGenres()
const { data: countries } = await useCountries()
const { filters, accept, reset } = useMediaFilters()

const searchGenre = ref<string | null>(null)
const filteredGenres = computed<GenreModel[]>(() => {
  const search = searchGenre.value?.trim().toLowerCase()
  const items = genres.value ?? []

  if (!search) {
    return items
  }

  return items.filter((item) => item.name.toLowerCase().includes(search))
})

const searchCountry = ref<string | null>(null)
const filteredCountries = computed<CountryModel[]>(() => {
  const search = searchCountry.value?.trim().toLowerCase()
  const items = countries.value ?? []

  if (!search) {
    return items
  }

  return items.filter((item) => item.name.toLowerCase().includes(search))
})

const onToggleGenre = (genre: GenreModel): void => {
  toggleArrayValue<string>(filters.value.genres, genre.slug)
}

const onToggleCountry = (country: CountryModel): void => {
  toggleArrayValue<string>(filters.value.countries, country.code)
}

const onReset = (): void => {
  reset()

  emit('reset')
}

const onAccept = (): void => {
  accept()

  emit('accept', filters.value)
}
</script>

<style lang="scss">
@use './styles/MediaFilters.styles' as *;
</style>
