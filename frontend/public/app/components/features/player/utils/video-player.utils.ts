import { type Level } from 'hls.js'

export const getResolutionLabel = (level: Level): string => {
  const height = level.height
  const fps = level.frameRate
  let label = `${height}p`

  if (fps && fps > 0) {
    label += `${Math.round(fps)}`
  }

  return label
}

export const getDynamicRangeLabel = (level: Level): string | null => {
  const attrs = (level as any)._attrs?.[0]

  if (!attrs) {
    return null
  }

  const videoRange = attrs['VIDEO-RANGE'] || attrs['VIDEO_RANGE']

  if (!videoRange) {
    return null
  }

  if (videoRange.includes('HDR')) {
    return 'HDR'
  }

  if (videoRange.includes('PQ')) {
    return 'HDR'
  }

  if (videoRange.includes('HLG')) {
    return 'HLG'
  }

  return null
}

export const getQualityTag = (level: Level): string | null => {
  const { width, height } = level

  if (width >= 3840 || height >= 2160) {
    return '4K'
  }

  if (width >= 2560 || height >= 1440) {
    return '2K'
  }

  if (height >= 1080) {
    return 'Full HD'
  }

  if (height >= 720) {
    return 'HD'
  }

  return null
}
