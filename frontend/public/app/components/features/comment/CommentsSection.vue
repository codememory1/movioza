<template>
  <AContentSection title="Комментарии" class="comments-section">
    <template #beforeHeader>
      <div class="comments-section__form">
        <div class="comments-section__form-wrapper">
          <ATextarea
            v-model="comment"
            name="comment"
            resize="none"
            placeholder="Добавить комментарий..."
            :rows="4"
            class="comments-section__form-textarea"
          >
            <template #afterTextarea>
              <button
                type="button"
                aria-label="Добавить комментарий"
                class="comments-section__add-btn"
              >
                Добавить комментарий
              </button>
            </template>
          </ATextarea>
        </div>
      </div>
    </template>

    <template #appendHeader>
      <ASelect
        v-model="sort"
        placeholder="Сортировать"
        :options="sortOptions"
        option-label="label"
        option-value="value"
        class="comments-section__sort-select"
      />
    </template>
    <div class="comments-section__list">
      <slot name="comments" />
    </div>
    <div class="comments-section__load-more-wrapper">
      <ALoaderCircleIcon v-if="loadMoreIsLoading" />

      <template v-else>
        <button
          v-if="showLoadMore"
          type="button"
          class="comments-section__load-more-btn"
          :class="{ loading: loadMoreIsLoading }"
          aria-label="Загрузить больше комментариев"
          @click="emit('loadMore')"
        >
          Загрузить больше комментариев
        </button>
      </template>
    </div>
  </AContentSection>
</template>

<script lang="ts" setup>
import AContentSection from '~/components/ui/section/AContentSection.vue'
import ALoaderCircleIcon from '~/components/ui/icon/ALoaderCircleIcon.vue'
import ATextarea from '~/components/ui/textarea/ATextarea.vue'
import ASelect from '~/components/ui/select/ASelect.vue'

interface SortOption {
  label: string
  value: string
}

export interface ACommentsSectionProps {
  showLoadMore?: boolean
  loadMoreIsLoading?: boolean
}

export interface ACommentsSectionEmits {
  loadMore: []
}

defineProps<ACommentsSectionProps>()

const emit = defineEmits<ACommentsSectionEmits>()
const sort = ref<string>('popularity')
const sortOptions = computed<SortOption[]>(() => [
  {
    label: 'Сначала новые',
    value: 'new'
  },
  {
    label: 'Сначала старые',
    value: 'old'
  },
  {
    label: 'Популярные',
    value: 'popularity'
  }
])
const comment = ref<string | null>(null)
</script>

<style lang="scss">
@use './styles/CommentsSection.styles' as *;
</style>
