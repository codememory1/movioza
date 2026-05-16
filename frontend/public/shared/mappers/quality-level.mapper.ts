import type { PlaybackQualityLevelDto, PlaybackQualityLevelModel } from '#shared/types/playback'

export const qualityLevelMapper = {
  toModel(dto: PlaybackQualityLevelDto): PlaybackQualityLevelModel {
    return {
      width: dto.width,
      height: dto.height,
      bitrate: dto.bitrate,
      codecs: dto.codecs,
      frameRate: dto.frame_rate
    }
  }
}
