<h1>Orçanet - PI III 2025</h1>

## Descrição do projeto

<p align="justify">
  Aplicação de melhorias na plataforma de cálculo de orçamento de produtos, o Orçanet, facilitando o trabalho e reduzindo erros no ambiente corporativo da empresa “Dinâmica Soluções em Telecom”. A Melhoria na automatização do processo na plataforma será baseada em uma aplicação web para preenchimento de dados, consulta para aprovação de orçamentos utilizando API, acrescentando relatórios e envio de e-mail, usando tecnologias como SQL para armazenar dados é uma aplicação com vários produtos, onde serão realizadas melhorias no preenchimento dos cálculos sobre a venda, consultas automáticas de CNPJ dos clientes na Receita Federal (RFB) através da API, etc.
</p>

## Funcionalidades

:heavy_check_mark: Sistema de autenticação

:heavy_check_mark: Cadastro de produtos

:heavy_check_mark: Casdatro de clientes/empresas

:heavy_check_mark: Criar orçamentos

:heavy_check_mark: Consulta de CNPJ

## Demo:dash:

> Link do deploy da aplicação: https://orcanet.connectip.app.br/

Login: ```demo@univesp.br```

Senha: ```demo123```
...

## Pré-requisitos

:warning: PHP 8.1+ – Necessário para rodar o Laravel

:warning: Composer – Gerenciador de dependências do PHP

:warning: Laravel 10 – Framework backend

:warning: MySQL 5.7+ ou 8.0+ – Banco de dados relacional

:warning: Node.js 16.0+ – Necessário para rodar o Vite

:warning: NPM – Já vem incluso com o Node.js

:warning: Vite + Vue 3 – Ambiente frontend moderno

:warning: Servidor HTTP (Apache ou Nginx) - Pode usar o XAMPP se escolher o Apache
...

## Como rodar a aplicação :arrow_forward:

### Clonando o Repositório

Para clonar este repositório, utilize o seguinte comando:

```bash
git clone https://github.com/stephanie0503/Orcanet_PI2025
```

## Banco de Dados

### Criando o Banco de Dados e Usuário MySQL

- **Acesse seu MySQL via terminal ou phpMyAdmin e execute os seguintes comandos**

```sql

CREATE DATABASE orcanet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'orcanet_user'@'localhost' IDENTIFIED BY 'senha_segura';

GRANT ALL PRIVILEGES ON orcanet.* TO 'orcanet_user'@'localhost';

FLUSH PRIVILEGES;

```

:warning: Importante: anote o nome do banco, usuário e senha para configurar o .env do Laravel.

### Importando o Arquivo .sql

:warning: O arquivo esta na raiz do projeto, na pasta database, ajuste o caminho conforme necessário.

```sql
mysql -u orcanet_user -p orcanet < database/orcanet.sql

```

## Configurando o Backend

### Configurando Variáveis de Ambiente

Navegue até a pasta de nome backend e execute no terminal.

```bash
cp .env.example .env

```

Edite com as credenciais criadas:

DB_DATABASE=orcanet
DB_USERNAME=orcanet_user
DB_PASSWORD=senha_segura

### Instalar e verificar dependencias

Ainda na pasta backend, no terminal use os comandos:

```bash
composer install
php artisan key:generate
php artisan serve
```

## Configurando o Frontend

### Configurando Variáveis de Ambiente

O Vue está dentro da pasta frontend. Copie e edite o arquivo .env-ex:

```bash
cp frontend/.env-ex frontend/.env
```

Edite a URL da API (ajuste a porta se necessário):

```bash
VITE_VUE_APP_API_BACKEND_URL=http://localhost:8000/api

```

### Instalar e verificar dependencias

Rode as dependências e inicie a aplicação, no terminal use os comandos:

```bash
cd frontend
npm install
npm run dev
```

### Usuários:

## Linguagens, dependencias e libs utilizadas :books:

:books: Linguagens, dependências e libs utilizadas

### Backend (Laravel)

- Laravel 11 – Framework PHP para construção da API backend.

- Laravel Passport – Implementa autenticação baseada em JWT via tokens, protegendo as rotas da API de forma segura.

- Webklex Laravel IMAP – Biblioteca para interação com caixas de e-mail IMAP (usada para possíveis integrações ou leituras de e-mail automatizadas).

### Frontend (Vue 3 + Vite)

- Vue 3 – Framework progressivo para construção da interface do usuário.

- Vite – Bundler moderno para projetos frontend, utilizado para desenvolvimento e build da aplicação.

- Element Plus – Biblioteca de componentes UI baseada em Vue 3, utilizada para montar a interface da aplicação.

- Pinia – Gerenciador de estado oficial do Vue 3, usado para controlar dados como o usuário autenticado.

- Vue Router – Biblioteca de rotas oficial do Vue.

- Axios – Cliente HTTP utilizado para fazer requisições à API Laravel.

- Consultar CNPJ – Biblioteca para realizar consultas públicas de CNPJ diretamente da Receita Federal.

- ApexCharts + Vue3 ApexCharts – Usada para gerar gráficos interativos no dashboard.

- Day.js – Manipulação de datas e horários com uma API enxuta e compatível com Moment.js.

- Moment.js – Também utilizado para manipulação de datas, em pontos mais específicos do sistema.

- Feather Icons / Vue Feather – Conjunto leve de ícones vetoriais.

- Animate.css – Biblioteca CSS para animações simples na interface.

- Bootstrap 5 – Utilizado pontualmente para estrutura e estilo.

- Crypto-JS – Para codificação e decodificação segura de dados.

- Vue3 Signature – Captura de assinaturas manuais dentro da interface (em formulários, por exemplo).

- Vue Slide Up Down – Animação de transição vertical para exibir ou ocultar seções com suavidade.

- jsPDF – Geração de arquivos PDF diretamente do navegador.

- JSONP – Utilizado para requisições JSONP em contextos específicos onde o CORS impede requisições diretas.

- dotenv – Utilizado para manipular variáveis de ambiente durante o desenvolvimento.

## Telas do Sistema

### Login Inicial e Dashboard 

![image](https://github.com/user-attachments/assets/86bd679b-dd22-4256-b3ab-d378ed8fc9d9)

![image](https://github.com/user-attachments/assets/156dea79-8928-4e7b-9f1c-9369fa1e6115)

### Cadastro de Cliente Pesquisando o CNPJ 

![image](https://github.com/user-attachments/assets/e2d69c39-7303-4b57-aefb-fc6fab33ed00)

### Cadastro Preenchido Automaticamente 

![image](https://github.com/user-attachments/assets/8b5268ef-f936-4d64-96dd-0c0153b6d4d4)

### Criando Proposta para o cliente inserido 

![image](https://github.com/user-attachments/assets/ad536f74-b03e-4ef4-9eb6-06ae0d7c296b)

### Produto Inserido na Proposta 

![image](https://github.com/user-attachments/assets/ff2a76d1-9362-4c09-998e-42066c17a143)

### Resumo da Proposta 

![image](https://github.com/user-attachments/assets/5cd6d7a8-c1be-4b5e-a305-dff16b2892de)









## Tarefas em aberto

- **WebMail**: O projeto conta com um módulo de WebMail que está em desenvolvimento.
A funcionalidade tem como objetivo permitir o envio e recebimento de e-mails diretamente pela interface da aplicação, integrando-se com caixas de e-mail via IMAP utilizando a biblioteca webklex/laravel-imap.

- **Relatórios** (Financeiro e Vendas):
A aplicação terá um módulo dedicado à geração de relatórios detalhados, contemplando dados financeiros e de vendas.
A ideia é permitir a visualização de métricas importantes do negócio com filtros por período, cliente, produto e outros critérios relevantes.
A estrutura inicial está em esboço, mas ainda precisa ser implementada tanto no backend quanto no frontend. A biblioteca de gráficos ApexCharts será utilizada para visualização dos dados.


- **Módulo de Estoque** O sistema contará com um controle de estoque para acompanhar a entrada, saída e saldo de produtos cadastrados.
Esse módulo integrará diretamente com a criação de orçamentos e vendas, garantindo o controle em tempo real do inventário.
A estrutura do banco de dados está sendo planejada, mas ainda não foi implementada a lógica de controle nem as telas do frontend.

## Desenvolvedores/Contribuintes :octocat:

Alessandro Costa Ferreira

Alexandre Timóteo da Luz

Aurineide de Jesus Viana

Bruno Yoshiyo Kodama Wadamori

Daniel Vrena Alves

Felipe Ribeiro Naves

Maria Lucineide Rodrigues Paulo

Stephanie de Oliveira Souza
