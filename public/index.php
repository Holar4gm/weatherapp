<?php
// public/index.php

require_once __DIR__ . '/../config/config.php';  // Include the config file

// Function to fetch current weather data
function getWeatherData($city) {
    $url = BASE_URL . "?q=" . urlencode($city) . "&appid=" . API_KEY . "&units=metric";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Function to fetch weather forecast data
function getWeatherForecast($city) {
    $url = FORECAST_URL . "?q=" . urlencode($city) . "&appid=" . API_KEY . "&units=metric";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Get the city from the query string or default to 'London'
$city = isset($_GET['city']) ? $_GET['city'] : 'Lagos';

// Fetch the weather data
$currentWeather = getWeatherData($city);
$weatherForecast = getWeatherForecast($city);

// Calculate the local time for the city
$timezoneOffset = isset($currentWeather['timezone']) ? $currentWeather['timezone'] : 0; // Default to 0 if not set
$currentTimeUtc = new DateTime('now', new DateTimeZone('UTC')); // Current time in UTC
$currentTimeUtc->modify("+{$timezoneOffset} seconds"); // Adjust time by timezone offset

// Include the view file to display the weather data
require_once __DIR__ . '/../views/home.php';
?>
