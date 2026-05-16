import type { PlaybackQualityLevelModel } from '#shared/types/playback'
import type { PlaybackAudioTrackModel } from '#shared/types/playback'
import type { PlaybackSubtitleModel } from '#shared/types/playback'

export interface PlaybackModel {
  id: number
  playlist: string
  qualityLevels: PlaybackQualityLevelModel[]
  audioTracks: PlaybackAudioTrackModel[]
  subtitles: PlaybackSubtitleModel[]
}
