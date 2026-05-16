import type { PlaybackQualityLevelDto, PlaybackSubtitleDto } from '#shared/types/playback'
import type { PlaybackAudioTrackDto } from '#shared/types/playback'

export interface PlaybackDto {
  id: number
  playlist: string
  quality_levels: PlaybackQualityLevelDto[]
  audio_tracks: PlaybackAudioTrackDto[]
  subtitles: PlaybackSubtitleDto[]
}
