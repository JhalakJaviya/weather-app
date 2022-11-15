# Weather App

## Installation

Clone the repository

    git clone https://github.com/JhalakJaviya/weather-app.git

Switch to the repo folder

    cd weather-app

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Update OPENWEATHERMAP_API_KEY in the .env file

    OPENWEATHERMAP_API_KEY=

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve


## Front-End

Visit the home page and type the valid name of the city to get the weather data


## APIs

### Add New City / Weather DATA for Front-End API
```http
POST /api/cities
```
##### Parameters:
```javascript
{
  "name" : "NAME OF THE CITY"
}
```

### List of all cities with Weather Data API
```http
GET /api/cities
```

### Single City details with Weather Data API
```http
GET /api/cities/{city-id}
```