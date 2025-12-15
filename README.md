# 🎥 YouPlay (Gerenciador de Vídeos MVC)

Um sistema web desenvolvido em **PHP Moderno** utilizando arquitetura **MVC** (Model-View-Controller). A aplicação permite gerenciar uma lista de vídeos, realizar uploads de capas (thumbnails), autenticação de usuários e disponibiliza uma API JSON.

## 🚀 Funcionalidades

- **CRUD de Vídeos:** Adicionar, editar, listar e remover vídeos.
- **Upload de Imagens:** Suporte para envio de capas personalizadas para os vídeos.
- **Reprodução:** Incorporação automática de vídeos (suporte a iframes).
- **Autenticação:** Sistema de login seguro com e-mail e senha.
- **API RESTful:** Endpoint `/videos-json` para consumo externo dos dados.
- **Segurança:**
    - Proteção contra SQL Injection (uso de Prepared Statements com PDO).
    - Validação de inputs (URL, Inteiros, etc).
    - Senhas salvas com hash seguro (`password_hash`).

## 🛠️ Tecnologias Utilizadas

- **PHP 8.2+**
- **SQLite** (Banco de dados)
- **HTML5 & CSS3** (Flexbox/Grid)
- **Composer** (Autoloading PSR-4)

## 📂 Estrutura do Projeto

O projeto segue o padrão MVC:
- `src/Controller`: Lógica das requisições.
- `src/Entity`: Modelos de dados.
- `src/Repository`: Acesso ao banco de dados.
- `public/`: Arquivos acessíveis via navegador (CSS, Imagens, Index).
- `views/`: Templates HTML.

## ⚙️ Configuração e Instalação

### 1. Pré-requisitos
Certifique-se de ter o PHP instalado (versão 8.2 ou superior) e a extensão `pdo_sqlite` habilitada.

### 2. Configurar o Banco de Dados
Antes de iniciar, é necessário criar o banco de dados e as tabelas:

```bash
php criar-banco.php
```
**Nota:** Se você já possui o banco, certifique-se de que a coluna `image_path` existe na tabela `videos`.

### 3. Configurar Pastas de uploads
O sistema precisa de uma pasta com permissão de escrita para salvar as imagens:
```bash
mkdir -p public/img/uploads
# No Linux/Mac, garanta as permissões se necessário:
# chmod -R 777 public/img/uploads
```

### 4. Como Executar
Para rodar o servidor embutido do PHP, utilize o comando abaixo apontando para a pasta public:
```
php -S localhost:8282 -t public/
```
Após iniciar, acesse no seu navegador:
👉 http://localhost:8282

## 🔐 Acesso ao Sistema
Para adicionar ou editar vídeos, é necessário estar logado.
Caso ainda não tenha um usuário cadastrado no banco, você precisará criar um script ou inserir manualmente no banco SQLite utilizando password_hash() para a senha.
Rota de Login: /login

## 📡 Documentação da API
O projeto expõe os dados dos vídeos em formato JSON.
Listar Vídeos
Retorna todos os vídeos cadastrados com URL, Título e caminho da imagem.
URL: /videos-json
Método: GET
Exemplo de Resposta:
code
JSON
```
[
  {
    "url": "https://www....",
    "title": "Título do Vídeo",
    "file_path": "/img/uploads/exemplo.jpg"
  }
]
```

Desenvolvido como parte dos estudos de PHP e Arquitetura MVC.