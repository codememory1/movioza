import type { ImageDto, ImageModel } from '#shared/types/common/image.type'

export const imageMapper = {
  toModel(dto: ImageDto): ImageModel {
    return {
      url: dto.url,
      width: dto.width,
      height: dto.height,
      blurHash: dto.blur_hash
    }
  }
}
