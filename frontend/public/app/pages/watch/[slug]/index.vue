<template>
  <!-- Block with service benefits -->
  <AContainer class="a-section-mt--sm">
    <AppPlatformBenefits />
  </AContainer>

  <!-- Displaying an Intro that includes both hero media and the video player itself -->
  <AContainer v-if="media" class="a-section-mt--sm">
    <MediaIntro>
      <MediaHeroContent :media="media" />
      <ClientOnly>
        <template v-if="playback">
          <SeriesVideoPlayer
            v-if="season && episode"
            :season
            :media
            :episode
            :playback
            @update-episode="onUpdateEpisode"
          />
          <MovieVideoPlayer v-else :media :playback class="embed-player" />
        </template>
      </ClientOnly>
    </MediaIntro>
  </AContainer>

  <!-- A block for displaying seasons and episodes; it is only displayed for media with the "series" type. -->
  <AContainer v-if="media && season && resolvedEpisodeNumber" class="a-section-mt--md">
    <MediaSeasonsSection :season :media :selected-episode="resolvedEpisodeNumber" />
  </AContainer>

  <!-- Block displaying information about service support -->
  <AContainer class="a-section-mt--md">
    <AppSupportCryptoSection />
  </AContainer>

  <!-- Displaying collections from current media directors -->
  <AContainer
    v-for="directorMedia in allDirectorMedia"
    :key="directorMedia.director!.id"
    class="a-section-mt--md"
  >
    <AContentSection :title="`От режиссёра «${directorMedia.director!.name}»`">
      <ASlider :slides="directorMedia.items" id-key="id">
        <template #default="{ slide }">
          <MediaCard :media="slide" />
        </template>
      </ASlider>
    </AContentSection>
  </AContainer>

  <!-- Block with detailed information about media -->
  <AContainer class="a-section-mt--md">
    <AboutMediaSection v-if="media" :media="media" />
  </AContainer>

  <!-- Comments -->
  <AContainer class="a-section-mt--lg">
    <CommentsSection
      :show-load-more="hasMoreComments"
      :load-more-is-loading="isPendingMoreComments"
      @load-more="onLoadMoreComments"
    >
      <template #comments>
        <CommentItem v-for="comment in comments" :key="comment.id" :comment />
      </template>
    </CommentsSection>
  </AContainer>
</template>

<script lang="ts" setup>
import AContainer from '~/components/ui/container/AContainer.vue'
import AppPlatformBenefits from '~/components/app/platform-benefits/AppPlatformBenefits.vue'
import { useMediaDetail } from '~/composables/useMediaDetail'
import MediaHeroContent from '~/components/features/media/MediaHeroContent.vue'
import MediaIntro from '~/components/features/media/MediaIntro.vue'
import MediaSeasonsSection from '~/components/features/media/MediaSeasonsSection.vue'
import { useMediaSeason } from '~/composables/useMediaSeason'
import SeriesVideoPlayer from '~/components/features/player/SeriesVideoPlayer.vue'
import MovieVideoPlayer from '~/components/features/player/MovieVideoPlayer.vue'
import type { EpisodeModel } from '#shared/types/media'
import { useMediaPlayback } from '~/composables/useMediaPlayback'
import AppSupportCryptoSection from '~/components/app/section/AppSupportCryptoSection.vue'
import AboutMediaSection from '~/components/features/media/AboutMediaSection.vue'
import ASlider from '~/components/ui/slider/ASlider.vue'
import MediaCard from '~/components/features/media/MediaCard.vue'
import AContentSection from '~/components/ui/section/AContentSection.vue'
import { usePersonMediaList } from '~/composables/usePersonMediaList'
import type { PersonModel } from '#shared/types/person'
import CommentsSection from '~/components/features/comment/CommentsSection.vue'
import CommentItem from '~/components/features/comment/CommentItem.vue'
import { useMediaCommentList } from '~/composables/useMediaCommentList'
import { useSlugParser } from '~/composables/useSlugParser'
import { toNumber } from '#shared/utils/normalize.utils'
import { useWatchSeo } from '~/composables/useWatchSeo'

const route = useRoute()

// Parsing the slug and segments of the current routing for id, season, and episode
const { id } = useSlugParser(String(route.params.slug), 'Media')
const routeSeasonNumber = computed<number | undefined>(() => toNumber(route.params.season))
const routeEpisodeNumber = computed<number | undefined>(() => toNumber(route.params.episode))
const { data: media, error: mediaError } = await useMediaDetail(id)

if (mediaError.value || !media.value) {
  throw createError({ statusCode: 404, message: 'Media not found.' })
}

// Season state that will allow you to change the season dynamically
const currentSeasonNumber = ref<number | undefined>(routeSeasonNumber.value)
const resolvedSeasonNumber = computed<number | undefined>(() => {
  if (!media.value) {
    return undefined
  }

  if (isSeries(media.value.type)) {
    return currentSeasonNumber.value || 1
  }

  return undefined
})

// An episode state that will allow the episode to be changed dynamically
const currentEpisodeNumber = ref<number | undefined>(routeEpisodeNumber.value)
const resolvedEpisodeNumber = computed<number | undefined>(() => {
  if (!media.value) {
    return undefined
  }

  if (isSeries(media.value.type)) {
    return currentEpisodeNumber.value || 1
  }

  return undefined
})

const { data: season, error: seasonError } = await useMediaSeason(id, resolvedSeasonNumber)

if (seasonError.value && !season.value) {
  throw createError({ statusCode: 404, message: 'Media not found.' })
}

// Getting an active episode from an active season
const episode = computed<EpisodeModel | undefined>(() => {
  if (!media.value) {
    return undefined
  }

  return season.value?.episodes.find((episode) => episode.number === resolvedEpisodeNumber.value)
})

// Defining the playback ID for further playback request in the API
const resolvedPlaybackId = computed<number | undefined>(() => {
  return episode.value?.playbackId || media.value?.playbackId || undefined
})

if (!resolvedPlaybackId.value) {
  throw createError({ statusCode: 404, message: 'Media not found.' })
}

const { data: playback } = await useMediaPlayback(resolvedPlaybackId)
const {
  items: comments,
  hasMore: hasMoreComments,
  isPendingMore: isPendingMoreComments,
  loadMore: loadMoreComments
} = await useMediaCommentList(id)

const directors = computed<PersonModel[]>(() => media.value?.directors || [])

// Create an array of promises to then make a media request for each director
const directorMediaPromises = directors.value.map((director) => usePersonMediaList(director.id))
const directorMediaResults = await Promise.all(directorMediaPromises)

// Mapping media directors into a convenient array of objects
const allDirectorMedia = computed(() =>
  directorMediaResults.map((result, i) => ({
    director: directors.value[i],
    items: result.items.value
  }))
)

const onUpdateEpisode = (episode: number): void => {
  currentEpisodeNumber.value = episode
}

const onLoadMoreComments = async (): Promise<void> => {
  await loadMoreComments()
}

useWatchSeo({
  media,
  season,
  episode,
  routeSeasonNumber,
  routeEpisodeNumber
})
</script>
