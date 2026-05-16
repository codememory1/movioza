<template>
  <article class="comment-item" :class="{ replies: comment.repliesCount > 0 }">
    <div class="comment-item__avatar">
      <span class="comment-item__avatar-char">{{ comment.senderName[0] }}</span>
    </div>

    <div class="comment-item__body">
      <header class="comment-item__header">
        <span class="comment-item__name">{{ comment.senderName }}</span>
        <span class="comment-item__date">
          {{ ago(comment.createdAt) }}
        </span>
      </header>

      <div class="comment-item__content">
        <p class="comment-item__text">{{ comment.message }}</p>
      </div>

      <footer class="comment-item__footer">
        <button
          type="button"
          class="comment-item__action-btn like"
          :class="{ selected: comment.liked }"
          :aria-label="likeAriaLabel"
          @click="emit('likeToggle', comment.id)"
        >
          <ALikeOutlineIcon /> {{ comment.likesCount }}
        </button>
        <button
          type="button"
          class="comment-item__action-btn dislike"
          :aria-label="dislikeAriaLabel"
          :class="{ selected: comment.disliked }"
          @click="emit('dislikeToggle', comment.id)"
        >
          <ADislikeOutline /> {{ comment.dislikesCount }}
        </button>
        <button type="button" class="comment-item__action-btn reply" aria-label="Ответить">
          <AReplyOutlineIcon /> <span>Ответить</span>
        </button>
      </footer>

      <div v-if="repliesShow" class="comment-item__replies">
        <CommentItem
          v-for="reply in replies"
          :key="reply.id"
          :comment="reply"
          @like-toggle="emit('likeToggle', $event)"
          @dislike-toggle="emit('dislikeToggle', $event)"
        />
      </div>

      <div v-if="comment.repliesCount > 0" class="comment-item__replies-toggle">
        <button
          type="button"
          class="comment-item__replies-toggle-btn"
          :class="{ loading: isLoadingReplies }"
          :aria-label="repliesToggleLabel"
          @click="onRepliesToggle"
        >
          {{ repliesToggleLabel }}
          <ALoaderCircleIcon v-if="isLoadingReplies" />
          <AChevronDownIcon v-else-if="!repliesShow" />
          <AChevronUpIcon v-else />
        </button>
      </div>
    </div>
  </article>
</template>

<script lang="ts" setup>
import { ago } from '~/utils/ago.utils'
import ALikeOutlineIcon from '~/components/ui/icon/ALikeOutlineIcon.vue'
import AReplyOutlineIcon from '~/components/ui/icon/AReplyOutlineIcon.vue'
import ADislikeOutline from '~/components/ui/icon/ADislikeOutline.vue'
import AChevronDownIcon from '~/components/ui/icon/AChevronDownIcon.vue'
import AChevronUpIcon from '~/components/ui/icon/AChevronUpIcon.vue'
import ALoaderCircleIcon from '~/components/ui/icon/ALoaderCircleIcon.vue'
import type { CommentModel } from '#shared/types/comment'
import { useCommentReplies } from '~/composables/useCommentReplies'

export interface CommentItemProps {
  comment: CommentModel
}

export interface CommentItemEmits {
  likeToggle: [id: string | number]
  dislikeToggle: [id: string | number]
  loadReplies: [id: string | number]
}

const props = defineProps<CommentItemProps>()
const emit = defineEmits<CommentItemEmits>()
const id = computed<string>(() => props.comment.id)
const { data: replies, pending: isLoadingReplies, execute: loadReplies } = useCommentReplies(id)
const repliesShow = ref<boolean>(false)
const isRepliesLoaded = ref<boolean>(false)
const likeAriaLabel = computed<string>(() =>
  props.comment.liked ? 'Снять лайк' : 'Поставить лайк'
)
const dislikeAriaLabel = computed<string>(() =>
  props.comment.liked ? 'Снять дизлайк' : 'Поставить дизлайк'
)
const repliesToggleLabel = computed<string>(() => {
  if (repliesShow.value) {
    return 'Скрыть ответы'
  }

  return `Показать ${props.comment.repliesCount} ${repliesPlural(props.comment.repliesCount)}`
})

watch(
  () => isRepliesLoaded.value,
  (loaded) => {
    if (loaded) {
      repliesShow.value = true
    }
  }
)

const repliesPlural = (num: number): string => plural(num, ['ответ', 'ответа', 'ответов'])

const onRepliesToggle = async (): Promise<void> => {
  if (repliesShow.value) {
    repliesShow.value = false

    return
  }

  if (isRepliesLoaded.value) {
    repliesShow.value = true

    return
  }

  if (!isLoadingReplies.value) {
    await loadReplies()

    isRepliesLoaded.value = true
  }
}
</script>

<style lang="scss">
@use './styles/CommentItem.styles' as *;
</style>
