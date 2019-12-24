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

End with an example of getting some data out of the system or using it for a little demo

## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Dropwizard](http://www.dropwizard.io/1.0.2/docs/) - The web framework used
* [Maven](https://maven.apache.org/) - Dependency Management
* [ROME](https://rometools.github.io/rome/) - Used to generate RSS Feeds

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Billie Thompson** - *Initial work* - [PurpleBooth](https://github.com/PurpleBooth)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc
