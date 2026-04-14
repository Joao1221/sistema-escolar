Atue como um Engenheiro Mestre Sênior em PHP 8.2 e Laravel 11. 
O objetivo é reestruturar a etapa de "Cadastro de Alunos" do meu sistema atual, que está migrando os campos de um sistema legado para o Laravel. Quero portar todas as informações do perfil do estudante, mas ignorar propositalmente qualquer coisa relacionada estritamente a "Matrícula", "Série", "Turma" e "Transporte", pois estes são tratados em outra área do sistema.

A arquitetura inicial possuía apenas uma tabela `alunos`. Quero transformá-la num sistema profissional de tabelas ramificadas vinculadas via foreign key `aluno_id`, sem gambiarras, seguindo as melhores práticas do Laravel 11 e PHP 8.2.

**1. AÇÕES DE BANCO DE DADOS (Migrations e Models)**
Crie 5 novas tabelas filhas (com chaves estrangeiras amarradas ao `aluno_id` via `onDelete('cascade')`) e ajuste a modelagem base.
- `alunos`: Mantenha como a tabela principal apenas com os dados cadastrais essenciais: Nome, Sexo, CPF, NIS, RGM (Registro de Matrícula do Sistema), Data de Nascimento, e os dados de Endereço completos.
- `aluno_documentos`: RG completo (número, órgão, UF, data_expedicao), Passaporte, País de Origem, Nacionalidade, Raça. Crie também dados detalhados da Certidão Civil (tipo [nascimento, casamento, nao], modelo [antigo, novo], termo, folha, livro, matricula_certidao, municipio, uf, nome_cartorio, data_emissao).
- `aluno_historicos`: Dados da escola de origem (nome, inep, cidade_uf, rede), ano letivo e última série cursada, situação na origem (concluiu, transferido, cursando, desistente), data_transferencia e pendencias_docs (string ou boolean).
- `aluno_saudes`: Tipo Sanguíneo, Alergias, Medicações Contínuas (boolean e descrição da medicação), Restrições Alimentares. JSON (json object/array) para 'deficiencias' e 'transtornos', contato Médico de Emergência (nome, parentesco, fone), e Altas Habilidades (boolean).
- `aluno_familias`: Dados Mãe e Pai (nome, profissão), Responsável Legal da Criança (nome, profissão, cpf, telefone fixo, celular), Recebe Bolsa Família (boolean), e Cartão do Bolsa.
- `aluno_autorizacoes`: Campos LGPD todos booleanos: uso_imagem, tratamento_dados. Passeios externos (sim, nao, parcial) e autorizacao_saida_com_terceiros.

Nos Modelos (Models), crie os relacionamentos `hasOne`/`belongsTo`. No **Laravel 11**, é ESTRITAMENTE OBRIGATÓRIO utilizar o novo método `protected function casts(): array` para realizar o "cast" de colunas como as datas reais e propriedades JSON. Use a tipagem de campos nativa no back-end.

**2. VALIDAÇÃO E SANITIZAÇÃO (FormRequests e Back-End)**
- Crie ou edite `StoreAlunoRequest` e `UpdateAlunoRequest`.
- É ESTRITAMENTE OBRIGATÓRIO utilizar o método `prepareForValidation()` dentro da FormRequest. Este método deve usar expressões regulares (ex: `preg_replace('/[^0-9]/', '', $value)`) para interceptar CPF, CEP e Telefones provenientes do frontend e varrer suas "máscaras". O framework deve validar e salvar no banco ESTRITAMENTE numerais limpos, mitigando falhas e injeções.
- Utilize regras robustas nativas array do Laravel na FormRequest (`string`, `max:255`, `boolean`, `integer`, `date`, `email`).
- No serviço (`AlunoService.php`), execute o fluxo inteiro de salvamento das 6 tabelas filhas a partir do request sanitizado de forma atômica utilizando EXCLUSIVAMENTE `DB::transaction()`. Use tipagem extrema (arrays ou DTOs nos parâmetros).

**3. INTERFACE DE USUÁRIO (Blade, TailwindCSS e JS)**
- Remodele as views `resources/views/secretaria-escolar/alunos/create.blade.php` e `edit.blade.php`.
- O layout final deve possuir um Design Premium "Aesthetics" feito com Tailwind CSS, sem atalhos visuais feios. A página inteira forma um único `<form>`, porém visualmente quebrada em "cards" ou "seções" limpas para abranger as diferentes tabelas (Saúde, Documentos, LGPD...).
- Integrações OBRIGATÓRIAS no Front-end:
  - Aplicar as diretivas `@error` do Laravel abaixo dos campos para validação de erros do servidor (com bordas vermelhas e mensagens amigáveis) e retenção de inputs com `old()`.
  - Javascript Vanilla para criar máscaras dinâmicas instantâneas nos atributos visuais do usuário para CPF, CEP e Telefones.
  - Javascript Vanilla para funções reativas (ex.: ao escolher "SIM" para Uso de Medicação Contínua, abre a div perguntando "Qual o remédio?". Caso não, esconde.).
  - Incluir garantias de HTML5 como `required`, `minlength`, e `type="date"` limitando datas de nascimentos não incoerentes.

Revise esse plano passo-a-passo. Entendendo totalmente os requisitos, gere os códigos completos de todas as migrations das tabelas descritas, ajuste os Models correspondentes com o Laravel 11, entregue os requests refatorados com a sanitização, mostre como o Service será implementado na Transaction e estruture o rascunho de como seria o corpo premium desse layout Blade. 
