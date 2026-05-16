import type {
  EpisodeModel,
  MediaListFiltersModel,
  MediaShortModel,
  SeasonModel
} from '#shared/types/media'
import type { CollectionShortModel } from '#shared/types/collection'

export const AppRoutes = {
  home: () => '/',

  // Movies
  filmy: (filters?: MediaListFiltersModel) => '/filmy',
  filmyPage: (page: number) => `/filmy/page/${page}`,

  // Series
  serialy: (filters?: MediaListFiltersModel) => '/serialy',
  serialyPage: (page: number) => `/serialy/page/${page}`,
  multserialy: (filters?: MediaListFiltersModel) => '/multserialy',

  // Watch
  watch: (id: number, slug: string) => `/watch/${id}-${slug}`,
  watchSeason: (id: number, slug: string, season: number) => `/watch/${id}-${slug}/sezon-${season}`,
  watchEpisode: (id: number, slug: string, season: number, episode: number) =>
    `/watch/${id}-${slug}/sezon-${season}/seriya-${episode}`,

  // Watch by model
  watchModel: (media: Pick<MediaShortModel, 'id' | 'slug'>) =>
    AppRoutes.watch(media.id, media.slug),

  watchSeasonModel: (
    media: Pick<MediaShortModel, 'id' | 'slug'>,
    season: Pick<SeasonModel, 'number'>
  ) => AppRoutes.watchSeason(media.id, media.slug, season.number),

  watchEpisodeModel: (
    media: Pick<MediaShortModel, 'id' | 'slug'>,
    season: Pick<SeasonModel, 'number'>,
    episode: Pick<EpisodeModel, 'number'>
  ) => AppRoutes.watchEpisode(media.id, media.slug, season.number, episode.number),

  // Embed
  embed: (id: number, slug: string) => `/embed/${id}-${slug}`,
  embedEpisode: (id: number, slug: string, season: number, episode: number) =>
    `/embed/${id}-${slug}/sezon-${season}/seriya-${episode}`,

  // Person
  director: (id: number, slug: string) => `/director/${id}-${slug}`,
  actor: (id: number, slug: string) => `/actor/${id}-${slug}`,

  // Collection
  collections: () => '/collections',
  collectionsPage: (page: number) => `/collections/page/${page}`,
  collection: (id: number, slug: string) => `/collections/${id}-${slug}`,
  collectionPage: (id: number, slug: string, page: number) =>
    `/collections/${id}-${slug}/page/${page}`,

  // Collection Model
  collectionModel: (model: Pick<CollectionShortModel, 'id' | 'slug'>) =>
    AppRoutes.collection(model.id, model.slug),
  collectionPageModel: (model: Pick<CollectionShortModel, 'id' | 'slug'>, page: number) =>
    AppRoutes.collectionPage(model.id, model.slug, page)
}
