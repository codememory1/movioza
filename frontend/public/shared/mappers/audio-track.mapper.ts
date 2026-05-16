import type { PlaybackAudioTrackDto, PlaybackAudioTrackModel } from '#shared/types/playback'

export const audioTrackMapper = {
  toModel(dto: PlaybackAudioTrackDto): PlaybackAudioTrackModel {
    return {
      lang: dto.lang,
      label: dto.label
    }
  }
}
