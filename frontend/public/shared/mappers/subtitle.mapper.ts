import type { PlaybackSubtitleDto, PlaybackSubtitleModel } from '#shared/types/playback'

export const subtitleMapper = {
  toModel(dto: PlaybackSubtitleDto): PlaybackSubtitleModel {
    return {
      lang: dto.lang,
      label: dto.label
    }
  }
}
