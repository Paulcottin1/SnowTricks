# SnowTricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9b6e7d1a038d4c0f93da2c55cf1b946c)](https://app.codacy.com/gh/Paulcottin1/SnowTricks?utm_source=github.com&utm_medium=referral&utm_content=Paulcottin1/SnowTricks&utm_campaign=Badge_Grade)

Community snowboard trick sharing website

## Installation

Clone the repository Github

```
git clone https://github.com/Paulcottin1/SnowTricks.git
```

Create file `.env.local` at the root of the project by making a copy of the file `.env` in order to configure the environment variables.

Install dependencies

```
composer install
```

Create the database

```
php bin/console doctrine:database:create
```

Create the different tables with the migration

```
php bin/console doctrine:migrations:migrate
```

Install fixtures

```
php bin/console doctrine:fixtures:load
```

Install dependecies front-end of the project with Yarn

```
yarn install
```

Create an asset build (thanks to Webpack Encore) with Yarn

```
yarn encore dev
```

Run the project

```
symfony server:start
```

Use admin account to administrate the website

> login: admin@gmail.com
>
> password: admin
