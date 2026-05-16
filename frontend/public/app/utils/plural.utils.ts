const pluralRules = new Intl.PluralRules('ru-RU')

export const plural = (num: number, words: [string, string, string]): string => {
  const ldml = pluralRules.select(num)

  switch (ldml) {
    case 'one':
      return words[0]
    case 'few':
      return words[1]
    case 'many':
      return words[2]
    default:
      return words[2]
  }
}
