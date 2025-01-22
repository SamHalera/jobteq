This is a [Symfony 7.2](https://symfony.com/) project bootstrapped with [`symfony new symfony-tasks --version="7.2" --webapp`](https://symfony.com/doc/current/setup.html).

# Jobteq

Jobteq is a jobboard application. Companies can publish offers ans candidates can find their favourite job offers and apply to them

## Requirements

To run this project, you'll need:

1. **PHP** 8.2 or higher
2. **Composer** installed globally which is tha package manager used to install all PHP packages
   [for more information](https://getcomposer.org/download/)

3. **Symfony CLI**
   [for more information](https://symfony.com/download)

4. **Docker**
   [for more information](https://symfony.com/doc/current/setup/docker.html)

   [more about requirements](https://symfony.com/doc/current/setup.html)

### Getting Started

1. First install dependances

Run

```bash
composer install

```

2. This application uses Asset/Mapper to handle Javascript and Css assets
   [more about](https://symfony.com/doc/current/frontend/asset_mapper.html)

To install all front-end dependencies of the project run :

```bash
php bin/console importmap:install

```

3. Run the development server :

```bash
symfony server:start

```

4. If you need to stop server, run :

```bash
symfony server:stop

```

#### NB:
