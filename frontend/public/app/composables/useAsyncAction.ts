export const useAsyncAction = (fn: () => Promise<void>) => {
  const pending = ref<boolean>(false)

  const run = async () => {
    pending.value = true

    try {
      await fn()
    } finally {
      pending.value = false
    }
  }

  return {
    pending,
    run
  }
}
