# API loja ABC
## Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Ferramentas Utilizadas](#ferramentas-utilizadas)
- [Instalação](#instalação)
  - [Instalação Local](#instalação-local)
  - [Instalação com o Docker Compose](#instalação-com-o-docker-compose)
- [Passos Para Executar os Testes](#passos-para-executar-os-testes)
- [Documentação](#documentação)

## Sobre o Projeto

A API foi desenvolvida com o intuíto de gerenciar as vendas da loja ABC LTDA. Na criação do projeto foram aplicados conceitos de Test-Driven Development (TDD), Domain-driven design (DDD), Clean Architecture, CI/CD, dentre outros.

## Ferramentas Utilizadas

A API foi desenvolvida utilizando as seguintes ferramentas e tecnologias:

- [Laravel](https://laravel.com/): Framework PHP para construção de aplicativos web.
- [MySQL](https://www.mysql.com/): Sistema de gerenciamento de banco de dados relacional.
- [GitHub Actions](https://github.com/features/actions): Para automação de CI/CD.
- [Docker](https://www.docker.com/): Para empacotamento e distribuição da aplicação em contêineres.
- [Postman](https://www.postman.com/): Para teste e documentação das API's

## Instalação

### Instalação local

Siga estas instruções para configurar e executar o Projeto localmente.

### Pré-requisitos

- PHP >= 8.2
- Composer
- MySQL
- Docker (opcional)
- Git

### Passos de Instalação

1. Clone o repositório:

    ```bash
    git clone https://github.com/gustavo-clemente/api-loja-abc.git
    ```

2. Dentro da pasta do projeto instale as dependências do Composer:

    ```bash
    composer install
    ```

3. Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente, incluindo as configurações do banco de dados.

4. Gere uma nova chave de aplicativo:

    ```bash
    php artisan key:generate
    ```

5. Execute as migrações do banco de dados:

    ```bash
    php artisan migrate
    ```
6. Execute as seeds para popular o banco com os dados iniciais para teste:

    ```bash
    php artisan db:seed
    ```

7. Inicie o servidor de desenvolvimento:

    ```bash
    php artisan serve
    ```

Agora você pode acessar o projeto em `http://localhost:8000`.

### Instalação com o Docker Compose

Se preferir, pode ser mais prático realizar a instalação utilizando o docker compose

#### Pré-requisitos

- Docker
- Docker Compose
- Git

### Passos de Instalação

1. Clone o repositório:

    ```bash
    git clone https://github.com/gustavo-clemente/api-loja-abc.git
    ```

2. Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente, incluindo as configurações do banco de dados.
    > ⚠️ **Nota Importante:**
    >
    > Esse passo é necessário pois o arquivo docker-compose.yaml está configurado para ler as variáveis de ambiente declaradas dentro do .env para configuração do banco de dados junto a API.

3. Execute o docker compose:
    ```bash
    docker-compose up -d
    ```
4. Gere uma nova chave de aplicativo dentro do container
    ```bash
    docker exec api-abc php artisan key:generate
    ```

5. Execute as migrações do banco de dados:
    
    ```bash
    docker exec api-abc php artisan migrate
    ```
6. Execute as seeds para popular o banco com os dados iniciais para teste:

    ```bash
    docker exec api-abc php artisan db:seed
    ```

Agora você pode acessar o projeto em `http://localhost:8000`.

### Passos Para Executar os Testes

1. Abra um terminal.
2. Navegue até o diretório do projeto.
3. Execute o seguinte comando para executar os testes:

    ```bash
    php artisan test # Ambiente local
    docker exec api-abc php artisan test # Ambiente Em Docker
    ```
4. Caso queira executar apenas suites específicos, isso pode ser feito passando o parâmetro `--testsuite`
Isso executará todos os testes definidos para o projeto e fornecerá feedback sobre o status dos testes.

    ```bash
    # Ambiente local
    php artisan test --testsuite=Unit # Testes unitários
    php artisan test --testsuite=Integration # Testes de integração
    php artisan test --testsuite=Feature # Testes ponta a ponta

    # Ambiente em Docker
    docker exec api-abc php artisan test --testsuite=Unit # Testes unitários
    docker exec api-abc php artisan test --testsuite=Integration # Testes de integração
    docker exec api-abc php artisan test --testsuite=Feature # Testes ponta a ponta
    ```

## Documentação

A documentação do projeto foi criada utilizando o Postman, e pode ser acessada [nesse link](https://www.postman.com/lunar-zodiac-111162/workspace/api-loja-abc/api/e0bcd391-6dd3-4aae-9a44-0e223ea16d83?action=share&creator=8836059&active-environment=8836059-8fc9a29c-6531-46b9-afd2-c140d544bb22).