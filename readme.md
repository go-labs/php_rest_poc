## PHP + Laravel + Rest + Swagger

###Installation

	$ git clone git@github.com:go-labs/php_rest_poc.git
	$ cd php_rest_poc
	$ composer install
	$ cp .env.example .env
	$ edit .env file with database information
	$ php artisan key:generate
	$ php artisan migrate
	$ php artisan serve
	$ open http://localhost:8000 in your favorite browser

### Running unit tests
* Run **phpunit** (if exists any problem, execute **vendor/phpunit/phpunit/phpunit** )
* Make sure to check code coverage reports on the reports directory

###Swagger
![Swagger UI](https://raw.githubusercontent.com/go-labs/php_rest_poc/master/screenshots/swagger_ui.png "Swagger UI")

#### Unit Tests
![Main view](https://raw.githubusercontent.com/go-labs/php_rest_poc/master/screenshots/unit_tests.png "Unit tests")

#### Code Coverage
![Code Coverage](https://raw.githubusercontent.com/go-labs/php_rest_poc/master/screenshots/code_coverage.png "Code Coverage")
