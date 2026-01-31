# Nome do Projeto 
Sistema de Gerenciamento Financeiro

## O projeto tem que ter na raiz um arquivo de configuracao.ini

com os seguintes dados lebrando que o sistema roda utilizando o banco de dados mongodb

[DB]
db = "NOME_BANCO"
dns = "DNS DO SEU BANCO"

## Depois disso tem que criar as tabelas no banco de dados

contas
movimentacao
sistema

## validação da tabela contas
nome_conta: {
    $type: 'string'
  },
  saldo_conta: {
    $type: 'double'
  }


## validação da tabela movimentacao
conta: {
    $type: 'objectId'
  },
  tipo_lancamento: {
    $type: 'string'
  },
  valor_lancamento: {
    $type: 'double'
  },
  data_lancamento: {
    $type: 'date'
  },
  descricao: {
    $type: 'string'
  }
  
  ## validacao da tabela sistema
  versao_sistema: {
    $type: 'string'
  }

  ## Não esqueça de ficar sempre de olho por aqui, pois sempre irá possuir novidades no sistema