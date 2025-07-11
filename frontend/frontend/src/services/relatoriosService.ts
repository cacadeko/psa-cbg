import api from './api';

export interface DadosDistribuicaoSemanal {
  tipo: string;
  percentual: number;
  minutos: number;
}

export interface DadosPSE {
  dia: string;
  pfg: number;
  pfe: number;
  tecnico: number;
}

export interface DadosCargaAgudoCronica {
  cargaCronica: number;
  cargaAguda: number;
  data: string;
}

export interface DadosFadiga {
  dia: string;
  nivel: number;
}

export interface DadosCargaSemanal {
  semana: string;
  carga: number;
}

export interface MediasPSE {
  pfg: number;
  pfe: number;
  tecnico: number;
}

class RelatoriosService {
  // Dados mockados baseados nos arquivos PHP
  async getDistribuicaoSemanal(): Promise<DadosDistribuicaoSemanal[]> {
    return [
      { tipo: 'PFE', percentual: 23.51, minutos: 790 },
      { tipo: 'PFS', percentual: 16.38, minutos: 550 },
      { tipo: 'Técnico Misto', percentual: 22.30, minutos: 750 },
      { tipo: 'Técnico Fita', percentual: 17.92, minutos: 602 },
      { tipo: 'Físico-Técnico', percentual: 17.32, minutos: 582 },
      { tipo: 'Intervalo', percentual: 2.03, minutos: 68 }
    ];
  }

  async getDadosPSE(dataInicio: string, dataFim: string): Promise<DadosPSE[]> {
    return [
      { dia: 'Seg', pfg: 3, pfe: 5, tecnico: 4 },
      { dia: 'Ter', pfg: 4, pfe: 6, tecnico: 5 },
      { dia: 'Qua', pfg: 5, pfe: 7, tecnico: 6 },
      { dia: 'Qui', pfg: 4, pfe: 6, tecnico: 5 },
      { dia: 'Sex', pfg: 6, pfe: 8, tecnico: 7 },
      { dia: 'Sáb', pfg: 3, pfe: 5, tecnico: 4 },
      { dia: 'Dom', pfg: 4, pfe: 6, tecnico: 5 }
    ];
  }

  async getMediasPSE(): Promise<MediasPSE> {
    return {
      pfg: 4,
      pfe: 6,
      tecnico: 5
    };
  }

  async getCargaAgudoCronica(): Promise<DadosCargaAgudoCronica[]> {
    return [
      { cargaCronica: 200, cargaAguda: 150, data: '2025-01-20' },
      { cargaCronica: 300, cargaAguda: 200, data: '2025-01-21' },
      { cargaCronica: 400, cargaAguda: 250, data: '2025-01-22' },
      { cargaCronica: 500, cargaAguda: 300, data: '2025-01-23' },
      { cargaCronica: 600, cargaAguda: 350, data: '2025-01-24' }
    ];
  }

  async getDadosFadiga(): Promise<DadosFadiga[]> {
    return [
      { dia: 'Seg', nivel: 3 },
      { dia: 'Ter', nivel: 5 },
      { dia: 'Qua', nivel: 7 },
      { dia: 'Qui', nivel: 6 },
      { dia: 'Sex', nivel: 8 },
      { dia: 'Sáb', nivel: 4 },
      { dia: 'Dom', nivel: 2 }
    ];
  }

  async getCargaSemanal(): Promise<DadosCargaSemanal[]> {
    return [
      { semana: 'Semana 1', carga: 250 },
      { semana: 'Semana 2', carga: 300 },
      { semana: 'Semana 3', carga: 280 },
      { semana: 'Semana 4', carga: 320 }
    ];
  }

  // Métodos para buscar dados reais da API (quando implementados)
  async getDistribuicaoSemanalAPI(dataInicio: string, dataFim: string): Promise<DadosDistribuicaoSemanal[]> {
    try {
      const response = await api.get(`/relatorios/distribuicao-semanal?dataInicio=${dataInicio}&dataFim=${dataFim}`);
      return response.data;
    } catch (error) {
      console.error('Erro ao buscar dados de distribuição semanal:', error);
      return this.getDistribuicaoSemanal();
    }
  }

  async getDadosPSEAPI(dataInicio: string, dataFim: string, tipo: string): Promise<DadosPSE[]> {
    try {
      const response = await api.get(`/relatorios/pse?dataInicio=${dataInicio}&dataFim=${dataFim}&tipo=${tipo}`);
      return response.data;
    } catch (error) {
      console.error('Erro ao buscar dados PSE:', error);
      return this.getDadosPSE(dataInicio, dataFim);
    }
  }
}

export default new RelatoriosService(); 