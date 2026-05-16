export const abort404 = (): void => {
  throw createError({
    status: 404,
    statusText: 'Страница не найдена',
    fatal: true
  })
}
