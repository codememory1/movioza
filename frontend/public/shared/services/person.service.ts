import { useNuxtApp } from '#imports'
import { personMediaMapper } from '#shared/mappers/person-media.mapper'

export const personService = {
  async getMedia(personId: number, page: number = 1, limit: number = 20) {
    const { $api } = useNuxtApp()
    const response = await $api(`/persons/${personId}/media?page=${page}&limit=${limit}`)

    return personMediaMapper.toListModel(response)
  }
}
