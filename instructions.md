# Guia de Aprendizado de IA sobre o Sistema de Percepção Subjetiva - CBG

Este markdown serve como base para ensinar um modelo de IA (como uma assistente) sobre a estrutura, evolução e funcionamento do sistema `psa-cbg`.

---

## 🧠 Contexto

A Confederação Brasileira de Ginástica (CBG) necessita acompanhar indicadores subjetivos dos atletas para prevenção de lesões e avaliação do bem-estar. O sistema desenvolvido coleta informações diárias sobre:

- Qualidade do sono
- Fadiga
- Dor muscular
- Uso de medicação
- Recuperação geral

---

## 🏗️ Arquitetura

- **Padrão MVC**
  - Models: interagem com o banco (`QualidadeSono.php`, etc)
  - Controllers: orquestram as ações do usuário
  - Views: exibem os formulários, tabelas e gráficos

---

## 🛠️ Funcionalidades Atuais

1. Cadastro e edição de percepções subjetivas por data
2. Exportação para Excel/PDF
3. Visualização de dados em tabela
4. Controle de acesso com login

---

## 🔄 Evolutivas Planejadas

| Etapa | Descrição | Status |
|-------|-----------|--------|
| 1     | Gráficos comparativos (Highcharts / Chart.js) por período | 🔜 |
| 2     | Dashboard com média por atleta e alerta de risco | 🔜 |
| 3     | Integração com sensores externos (Polar, Garmin) | ⏳ Estudo |
| 4     | Análise de padrões usando IA (tendência de queda, sobrecarga) | 🧠 Em modelagem |
| 5     | Histórico completo exportável por atleta | ✅ Implementado parcialmente |

---

## 📤 Integração com IA

A IA poderá:

- Gerar alertas com base na recorrência de dores ou insônia
- Sugerir repousos ou treinos leves
- Classificar atletas por risco de lesão

---

## 🧠 Sugestões para Treino de IA

- Dataset com CSVs exportados por atleta (data, dor, sono, etc)
- Rotular manualmente registros de risco ou anomalias
- Aplicar modelos de classificação binária para prever sobrecarga

---

## ✍️ Anotações

- A IA deve compreender que os dados subjetivos são auto-relatados e variam de atleta para atleta.
- A consistência das respostas é tão importante quanto os valores brutos.

---

## 🤖 Prompt para IA

> “Com base nos dados dos últimos 7 dias, o atleta [NOME] demonstrou piora nos índices de recuperação, aumento na dor muscular e baixa qualidade do sono. Recomende ajustes na carga de treino.”

---

Com este documento, é possível treinar uma IA para aprender sobre a estrutura e lógica do sistema, acompanhando suas evoluções.
