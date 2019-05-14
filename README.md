# Cloud Beds Test

A interval price manager

## Getting Started

Both project are running with docker just run docker-compose in the backend and docker run -d 80 on the front project

The url of the project is : http://ec2-34-214-96-161.us-west-2.compute.amazonaws.com/app/views/list.html

There is a button call Migrate Database that deletes all the values inside the db an re migrates database.

### Prerequisites
The only requisite is docker and docker compose the last versions.


## Deployment

For Backend : Run docker-compose up -d this will lunch phpmyadmin, mysql, ngina, php-fpm

For Frontend: Run  docker build -t html-server-image:v1 and then docker run -d -p 80:80 html-server-image:v1 

## Built With

* PHP
* MYSQL
* Docker

## Authors

* **Alexander Medina Escalante ** - *Initial work* - 


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details