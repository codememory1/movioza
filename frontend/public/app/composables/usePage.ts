export interface UsePageStat {
  label: string
  value: string | number
}

export const usePage = () => {
  const state = useState('page', () => ({
    stats: [] as UsePageStat[]
  }))

  const setStats = (stats: UsePageStat[]): void => {
    state.value.stats = stats
  }

  onUnmounted(() => {
    state.value.stats = []
  })

  return {
    state,
    setStats
  }
}
