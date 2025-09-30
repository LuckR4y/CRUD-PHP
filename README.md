# 📌 Atividade Parcial – CRUD (PHP POO)

**Professor:** Luciano Albuquerque Lima Saraiva  
**Aluno:** Arthur Vital Fontana – 839832  

---

## 🎯 Descrição do Projeto
Sistema **CRUD completo** em **PHP Orientado a Objetos** com **MariaDB/MySQL**, desenvolvido a partir do diagrama de classes fornecido.  
O projeto possui **frontend simplificado** em página única, com foco principal no **backend e na persistência de dados**.  

---

## 🧭 Como Utilizar
1. **Selecione a Entidade** no menu superior.  
2. **Preencha o formulário** e clique em **Salvar** (Create/Update).  
3. Abaixo, visualize a **Lista de Registros**, com opções para **Editar** e **Excluir**.  
4. Campos *_id utilizam **combobox** no formato `ID – Rótulo` (ex.: `1 – SP`) para facilitar a seleção das chaves estrangeiras (FKs).  

---

## 📂 Entidades Implementadas
- Pessoa (Física / Jurídica)  
- Curso, Disciplina, Turma  
- Aluno, Professor, Funcionário, Gerente  
- UF, Cidade, Bairro, Endereço  
- Fatura, Pagamento  

---

## 🗄️ Banco de Dados e Seeds
- **db/schema.sql** → Criação das tabelas e chaves estrangeiras.  
- **db/seed.sql** → Inserção de dados coerentes:  
  - Pessoas (Física e Jurídica)  
  - Endereços (UF, Cidade, Bairro)  
  - Estrutura acadêmica (Cursos, Disciplinas, Turmas, Alunos, Professores, Funcionários)  
  - Módulo Financeiro (Gerente, Faturas, Pagamentos)  
