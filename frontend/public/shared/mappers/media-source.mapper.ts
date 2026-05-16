import type { MediaSourceDto, MediaSourceModel } from '#shared/types/media-source'

export const mediaSourceMapper = {
  toModel(dto: MediaSourceDto): MediaSourceModel {
    return dto
  }
}
