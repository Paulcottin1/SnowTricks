# SnowTricks

Community snowboard trick sharing website

# Installation

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

Use admin account to administrate the website

> login: admin@gmail.com
>
> password: admin
