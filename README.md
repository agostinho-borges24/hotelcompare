# 🏨 HotelCompare Benguela

> Plataforma web para gestão e comparação de hotéis com actualizações em tempo real, desenvolvida como Trabalho de Fim de Curso no Instituto Politécnico da Universidade Katyavala Bwila — Benguela, Angola.

---

## 📋 Sobre o Projecto

O **HotelCompare** é uma plataforma web moderna que permite aos turistas pesquisar, comparar e avaliar hotéis na província de Benguela em tempo real. Os gestores de hotéis podem registar os seus estabelecimentos, actualizar disponibilidade e responder a avaliações directamente pelo painel de gestão.

### 🎯 Objectivos

- Digitalizar o sector hoteleiro de Benguela
- Permitir comparação de hotéis com informações actualizadas em tempo real
- Proporcionar uma ferramenta moderna para turistas e empresas hoteleiras
- Implementar um sistema de avaliações com moderação e respostas

---

## ✨ Funcionalidades

### Para Turistas
- 🔍 Pesquisa avançada com filtros (estrelas, preço, comodidades, zona)
- ⚖️ Comparação de até 3 hotéis lado a lado
- ⭐ Sistema de avaliações com classificação por categorias
- 🗺️ Mapa interactivo de localização de cada hotel
- 📱 Interface responsiva para todos os dispositivos

### Para Gestores de Hotéis
- 📊 Painel de gestão completo
- 🔄 Actualização de disponibilidade em **tempo real** via WebSocket
- 🖼️ Gestão de galeria de imagens
- 💬 Resposta a avaliações dos hóspedes
- 📈 Estatísticas do hotel (avaliação média, quartos disponíveis)

### Para Administradores
- 🏨 Gestão completa de hotéis (criar, editar, aprovar, suspender)
- 👥 Gestão de utilizadores e perfis
- ✅ Moderação de avaliações (aprovar/rejeitar)
- 🛡️ Controlo total da plataforma

---

## 🛠️ Tecnologias Utilizadas

| Categoria | Tecnologia |
|---|---|
| Backend | Laravel 12 (PHP 8.2) |
| Frontend | Bootstrap 5 + Blade Templates |
| Base de dados | MySQL |
| Tempo real | Laravel Reverb (WebSockets) |
| Autenticação | Laravel Jetstream + Fortify |
| Mapas | Leaflet.js + OpenStreetMap |
| Build tool | Vite |
| Controlo de versão | Git + GitHub |

---

## 🚀 Instalação e Configuração

### Pré-requisitos

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL
- Git

### Passos

**1. Clonar o repositório**
```bash
git clone https://github.com/agostinho-borges24/hotelcompare.git
cd hotelcompare
```

**2. Instalar dependências PHP**
```bash
composer install
```

**3. Instalar dependências JavaScript**
```bash
npm install
```

**4. Configurar o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

**5. Configurar o `.env`**
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotelcompare
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY=your-app-key
VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
```

**6. Criar a base de dados e executar migrações**
```bash
php artisan migrate --seed
```

**7. Criar link de armazenamento**
```bash
php artisan storage:link
```

**8. Compilar os assets**
```bash
npm run build
```

### Iniciar o servidor de desenvolvimento

Abrir **3 terminais** em simultâneo:

```bash
# Terminal 1 — Servidor Laravel
php artisan serve

# Terminal 2 — Servidor Reverb (WebSockets)
php artisan reverb:start

# Terminal 3 — Vite (desenvolvimento)
npm run dev
```

Aceder em: **http://localhost:8000**

---

## 👤 Contas de Teste

Após executar o seeder, as seguintes contas são criadas automaticamente:

| Perfil | Email | Palavra-passe |
|---|---|---|
| Administrador | admin@hotelcompare.ao | Admin@1234 |
| Gestor de Hotel | gestor@hotelcompare.ao | Gestor@1234 |

---

## 🗂️ Estrutura do Projecto

```
hotelcompare/
├── app/
│   ├── Actions/Fortify/          # Lógica de registo de utilizadores
│   ├── Events/                   # Eventos WebSocket (Reverb)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # Controllers do painel admin
│   │   │   ├── Manager/          # Controllers do painel gestor
│   │   │   └── Public/           # Controllers públicos
│   │   └── Middleware/           # Middleware de roles e segurança
│   └── Models/                   # Models Eloquent
├── database/
│   ├── migrations/               # Migrações da base de dados
│   └── seeders/                  # Seeders (admin + comodidades)
├── resources/
│   ├── js/                       # JavaScript + Echo (WebSocket)
│   └── views/
│       ├── admin/                # Views do painel admin
│       ├── auth/                 # Login e registo
│       ├── errors/               # Páginas de erro (403, 404)
│       ├── layouts/              # Layouts base
│       ├── manager/              # Views do painel gestor
│       ├── partials/             # Componentes reutilizáveis
│       └── public/               # Páginas públicas
└── routes/
    └── web.php                   # Rotas organizadas por perfil
```

---

## 🔐 Sistema de Perfis

| Perfil | Descrição | Acesso |
|---|---|---|
| **Admin** | Administrador da plataforma | `/admin/*` |
| **Gestor de Hotel** | Gere um hotel específico | `/gestor/*` |
| **Hóspede** | Turista registado | Avaliações |
| **Visitante** | Sem registo | Pesquisa e comparação |

---

## 📡 Tempo Real (WebSockets)

A plataforma usa **Laravel Reverb** para actualizações em tempo real. Quando um gestor altera a disponibilidade de um quarto, todos os visitantes a ver a página desse hotel recebem a actualização instantaneamente via WebSocket, sem necessidade de recarregar a página.

```
Gestor → PATCH /gestor/quartos/{id}/disponibilidade
       → broadcast(RoomAvailabilityUpdated)
       → Reverb WebSocket Server
       → Canal: hotel.{hotel_id}
       → Todos os browsers a ver o hotel
```

---

## 📸 Capturas de Ecrã

| Página | Descrição |
|---|---|
| Homepage | Hero com pesquisa, zonas, destaques e FAQ |
| Listagem | Hotéis com filtros avançados e paginação |
| Detalhe | Galeria, quartos, mapa e avaliações |
| Comparação | Tabela lado a lado com até 3 hotéis |
| Painel Gestor | Dashboard com estatísticas e gestão de quartos |
| Painel Admin | Gestão completa da plataforma |

---

## 👨‍💻 Autor

**Agostinho António Lourenço Borges**
Estudante do Instituto Politécnico
Universidade Katyavala Bwila — Benguela, Angola

---

## 🏛️ Instituição

**Universidade Katyavala Bwila — Instituto Politécnico**
Departamento de Ensino e Investigação de Mecânica e Informática
Complexo Universitário da Cambanda — Benguela, Angola

---

## 📄 Licença

Este projecto foi desenvolvido para fins académicos como Trabalho de Fim de Curso.

---

<div align="center">
    <strong>HotelCompare Benguela</strong> · Benguela, Angola · 2026
</div>
