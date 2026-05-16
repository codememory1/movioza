export interface BuildAggregateRatingSchemaInput {
  rating: number
  minRating?: number
  maxRating: number
  votes: number
}

export const buildAggregateRatingSchema = (input: BuildAggregateRatingSchemaInput) => ({
  '@type': 'AggregateRating',
  ratingValue: input.rating,
  worstRating: input.minRating ?? 1,
  bestRating: input.maxRating,
  ratingCount: input.votes
})
