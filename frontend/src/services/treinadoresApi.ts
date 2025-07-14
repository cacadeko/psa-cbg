import api from './api'

export interface Treinador {
  id?: number
  nome: string
  email: string
  telefone?: string
  especialidade?: string
  data_contratacao?: string
  nivel?: string
  senha?: string
  ativo?: boolean
  observacoes?: string
  usuario_id?: number
}

export const treinadoresApi = {
  // Listar todos os treinadores
  listar: async (): Promise<Treinador[]> => {
    const response = await api.get('/treinadores')
    return response.data
  },

  // Obter treinador por ID
  obter: async (id: number): Promise<Treinador> => {
    const response = await api.get(`/treinadores/${id}`)
    return response.data
  },

  // Criar novo treinador
  criar: async (treinador: Treinador): Promise<Treinador> => {
    const response = await api.post('/treinadores', treinador)
    return response.data
  },

  // Atualizar treinador
  atualizar: async (id: number, treinador: Treinador): Promise<Treinador> => {
    const response = await api.put(`/treinadores/${id}`, treinador)
    return response.data
  },

  // Excluir treinador
  excluir: async (id: number): Promise<void> => {
    await api.delete(`/treinadores/${id}`)
  }
} 