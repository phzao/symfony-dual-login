# Login Authentication with Symfony 5 and Oauth2

This repository is ideal for new projects to start with a login control. 
To persist the data, PostgreSQL was used using Docker. This image can be found at [Postgresql-db](git@github.com:phzao/postgresql-db.git). 
If you want to use MySQL, you will need some changes to Dockerfile, feel free to change anything.

## Getting Started

First of all, you'll need the Docker and the docker-composing. I will consider that it is already installed.

### Prerequisites

Get a copy of this repository.  

```
git clone git@github.com:phzao/symfony-dual-login.git
```
```
cd symfony-dual-login
```
### Installing

The entire project can be installed with one command:
```
make up
```
Or if you prefer
```
docker-compose up
```
Open another prompt and
```
docker exec -it sym-php composer install
```
```
docker exec -it sym-php php binn/console doctrine:migration:migrate
```

### Routes

The routes available are specified below:

```
Register User       ->  http://localhost:8888/register [POST]
Login Local         ->  http://localhost:8888/authenticate [POST]
Login Google        ->  http://localhost:8888/oauth-authenticate [POST]
Show User Detail    ->  http://localhost:8888/api/v1/users/{uuid} [GET]
List all Users      ->  http://localhost:8888/api/v1/users [GET]
```