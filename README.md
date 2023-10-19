
# MINI 4.0

Esta e uma versão do projeto original panique/mini (link nas referencias) com um estrutura um pouco mais robusta

* O projeto utiliza phinx migration para sincronizar as atualizações do banco de dados e github actions para atualizar o FTP

## Referência

 - [Mini Original (Base para o mini 4.0)](https://github.com/panique/mini)
 - [PHP 8.2](https://www.php.net/releases/8.2/en.php)
 - [Phinx Migrate](https://phinx.org/)


## Instalação

Após clonar o projeto você pode rodar:

```bash
  composer i
```

Dentro da pasta "db/" existe uma base do banco de dados ou você pode copiar direto da produção.

Copie o arquivo config.php.example e renomeie para config.php, o arquivo é intuitivo leia os nomes e realize as devidas configurações para o seu ambiente e para testes na produção.

Para rodar o server do chat você pode rodar o comando direto na pasta raiz do projeto

```bash
  php chat-server.php
```


    
## Deploy

Para fazer o deploy desse projeto basta verificar as configurações do arquivo .github/workflows/Main.yml

```bash
    server: O FTP Server da Produção
    username: Username de acesso ao ftp
    password: Senha de acesso do ftp
```
// O recomendado é que este elemento fique armazenado e utilizado como uma key do github actions para evitar que estejam commitadas no

Ajustadas as configurações basta commitar na branch main para atualizar o projeto automaticamente

Para realizar deploy do banco de dados você pode rodar a query manualmente na produção ou criar um phinx migrate 

```bash
    composer m:create NomeDoMigrate
```

Finalize seu migration e logo em seguida envie o migration para a produção, em config.php troque o 'ENVIRONMENT' para produção e rode o comando

```bash
    composer migrate
```

Logo em seguida retorne seu ENVIRONMENT para local para evitar alterações indesejadas

* Algumas pastas são ignoradas como a pasta vendor e precisam ser transferidas manualmente via FTP caso sejam alteradas no próprio arquivo você consegue conferir quais são


## Autores

- [@luan098](https://github.com/luan098)

