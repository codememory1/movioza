import type { PlaybackDto, PlaybackModel } from '#shared/types/playback'
import { qualityLevelMapper } from '#shared/mappers/quality-level.mapper'
import { audioTrackMapper } from '#shared/mappers/audio-track.mapper'
import { subtitleMapper } from '#shared/mappers/subtitle.mapper'

export const playbackMapper = {
  toModel(dto: PlaybackDto): PlaybackModel {
    return {
      id: dto.id,
      playlist: dto.playlist,
      qualityLevels: dto.quality_levels.map(qualityLevelMapper.toModel),
      audioTracks: dto.audio_tracks.map(audioTrackMapper.toModel),
      subtitles: dto.subtitles.map(subtitleMapper.toModel)
    }
  }
}
