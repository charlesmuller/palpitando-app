<p align="center">
  <h1>⚽ Palpitando - Copa do Mundo Betting App</h1>
</p>

<p align="center">
  Uma aplicação web moderna para gerenciar bolões da Copa do Mundo, permitindo que usuários façam palpites sobre os resultados dos jogos.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.15.0-FF2D20?style=flat-square&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/Docker-Compose-2496ED?style=flat-square&logo=docker" alt="Docker">
  <img src="https://img.shields.io/badge/MySQL-8.0-00758F?style=flat-square&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=flat-square" alt="License">
</p>

---

## 📋 Sobre o Palpitando

**Palpitando** é uma plataforma interativa desenvolvida com Laravel 13 para gerenciar bolões da Copa do Mundo. A aplicação permite que usuários se autentiquem, visualizem os jogos disponíveis e façam seus palpites sobre os resultados.

### Características Principais

- ✅ **Autenticação de usuários** com Laravel Breeze
- ✅ **Gerenciamento de jogos** da Copa do Mundo (integração com Football Data API)
- ✅ **Sistema de palpites** para cada jogo
- ✅ **Painel administrativo** com Filament
- ✅ **Interface responsiva** para dispositivos móveis
- ✅ **Arquitetura escalável** com Docker
- ✅ **Banco de dados MySQL** com PHPMyAdmin
- ✅ **Cache com Redis** para melhor performance
- ✅ **Segurança** contra ataques CSRF, XSS e SQL Injection

---

## 🚀 Tecnologias Utilizadas

### Backend
- **Laravel 13** - Framework PHP moderno
- **PHP 8.3** - Linguagem de programação
- **MySQL 8.0** - Banco de dados
- **Redis** - Cache e sessões
- **Filament** - Painel administrativo
- **Laravel Breeze** - Autenticação simples

### Frontend
- **Blade** - Template engine do Laravel
- **Tailwind CSS** - Utilitários CSS
- **Alpine.js** - JavaScript reativo
- **Livewire** (opcional) - Componentes dinâmicos

### DevOps
- **Docker** - Containerização
- **Docker Compose** - Orquestração de containers
- **Nginx** - Servidor web
- **PHPMyAdmin** - Gerenciamento de banco de dados

---

## 📦 Instalação e Setup

### Pré-requisitos
- Docker e Docker Compose instalados
- Git configurado
- SSH ou HTTPS para clonar repositórios

### Passos para Configurar Localmente

1. **Clone o repositório:**
```bash
git clone git@github.com:charlesmuller/palpitando-app.git
cd palpitando-app