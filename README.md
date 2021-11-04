# projeto php com symfony

## Descrição do Projeto
<p>1. A criação de uma rota que encontra um hash, de certo formato, para uma certa string fornecida como input.</p>
<p>2. A criação de um comando que consulta a rota criada e armazena os resultados na base de dados.</p>
<p>3. Criação de rota que retorne os resultados que foram gravados.</p>

### Features

- [x] Create hash com limite de requisição por minuto
- [x] List hashs com paginação
- [x] Commands

### Pré-requisitos

Antes de começar, você vai precisar ter instalado em sua máquina as seguintes ferramentas:
PHP, Composer. 



### 🎲 Rodando o Back End 

```
# Clone este repositório
$ git clone <https://github.com/sabrinasanhtos/teste>

# Install as depemdencias deste projeto
$ composer install

# Execução do Command
$ php bin/console app:create-hash "Ávato" -- 20

```

# Endpoint list
![1](https://user-images.githubusercontent.com/25057754/140247725-2c111e47-e86c-48c7-ae94-6cd3cdc0513d.png)

# Command 
![2](https://user-images.githubusercontent.com/25057754/140248353-4fc398bd-f135-441a-b903-00ed1617056c.png)


