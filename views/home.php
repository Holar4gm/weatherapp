<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body> 
<div class="container mt-5">
    <div class="d-flex flex-row justify-content-center align-items-center">
        <div class="weather__card">
            <div class="d-flex flex-row justify-content-center align-items-center">
                <div class="p-3">
                    <!-- Display Current Temperature -->
                    <h2>
                        <?= isset($currentWeather['main']['temp']) ? round($currentWeather['main']['temp']) . '&deg;C' : 'N/A'; ?>
                    </h2>
                </div>
                <div class="p-3">
                    <!-- Display Weather Icon -->
                    <?php if (isset($currentWeather['weather'][0]['icon'])): ?>
                        <img src="http://openweathermap.org/img/wn/<?= $currentWeather['weather'][0]['icon'] ?>@2x.png" alt="Weather Icon">
                    <?php endif; ?>
                </div>
                <div class="p-3">
                    <!-- Display Date, Time, and Location -->
                    <h5><?= $currentTimeUtc->format('l, h:i A') ?></h5>
                    <h3><?= isset($currentWeather['name']) ? $currentWeather['name'] : 'Unknown Location'; ?></h3>
                    <!-- Display Weather Description -->
                    <span class="weather__description">
                        <?= isset($currentWeather['weather'][0]['description']) ? ucfirst($currentWeather['weather'][0]['description']) : 'N/A'; ?>
                    </span>
                </div>
            </div>
            <div class="weather__status d-flex flex-row justify-content-center align-items-center mt-3">
                <div class="p-4 d-flex justify-content-center align-items-center">
                    <img src="https://svgur.com/i/oHw.svg" alt="Humidity Icon">
                    <!-- Display Humidity -->
                    <span>
                        <?= isset($currentWeather['main']['humidity']) ? $currentWeather['main']['humidity'] . '%' : 'N/A'; ?>
                    </span>
                </div>
                <div class="p-4 d-flex justify-content-center align-items-center">
                    <img src="https://svgur.com/i/oH_.svg" alt="Pressure Icon">
                    <!-- Display Pressure -->
                    <span>
                        <?= isset($currentWeather['main']['pressure']) ? $currentWeather['main']['pressure'] . ' mB' : 'N/A'; ?>
                    </span>
                </div>
                <div class="p-4 d-flex justify-content-center align-items-center">
                    <img src="https://svgur.com/i/oKS.svg" alt="Wind Icon">
                    <!-- Display Wind Speed -->
                    <span>
                        <?= isset($currentWeather['wind']['speed']) ? $currentWeather['wind']['speed'] . ' km/h' : 'N/A'; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Weather Forecast -->
<div class="weather__forecast d-flex flex-row justify-content-center align-items-center mt-3">
    <?php 
    $currentDate = new DateTime('now', new DateTimeZone('UTC'));
    $forecastDays = [];
    
    // Group forecasts by date
    foreach ($weatherForecast['list'] as $forecast) {
        $forecastDate = new DateTime($forecast['dt_txt']);
        $forecastDate->setTimezone(new DateTimeZone('UTC'));
        $dateKey = $forecastDate->format('Y-m-d');
        
        if (!isset($forecastDays[$dateKey])) {
            $forecastDays[$dateKey] = [
                'date' => $dateKey,
                'day' => $forecastDate->format('l'),
                'temp_max' => $forecast['main']['temp_max'],
                'temp_min' => $forecast['main']['temp_min'],
                'icon' => $forecast['weather'][0]['icon'],
                'description' => $forecast['weather'][0]['description']
            ];
        } else {
            if ($forecast['main']['temp_max'] > $forecastDays[$dateKey]['temp_max']) {
                $forecastDays[$dateKey]['temp_max'] = $forecast['main']['temp_max'];
            }
            if ($forecast['main']['temp_min'] < $forecastDays[$dateKey]['temp_min']) {
                $forecastDays[$dateKey]['temp_min'] = $forecast['main']['temp_min'];
            }
        }
    }
    
    // Sort by date
    ksort($forecastDays);
    
    // Display only the next 7 days
    $i = 0;
    foreach ($forecastDays as $forecastDay) {
        if ($i >= 7) break;
        ?>
        <div class="p-4 d-flex flex-column justify-content-center align-items-center">
            <span><?= $forecastDay['day'] ?>, <?= $forecastDay['date'] ?></span>
            <!-- Display Forecast Icon -->
            <?php if (isset($forecastDay['icon'])): ?>
                <img src="http://openweathermap.org/img/wn/<?= $forecastDay['icon'] ?>@2x.png" alt="Forecast Icon">
            <?php endif; ?>
            <!-- Display Weather Description -->
            <span><?= ucfirst($forecastDay['description']) ?></span>
            <!-- Display Max Temperature -->
            <span><?= round($forecastDay['temp_max']) ?>&deg;C</span>
        </div>
        <?php
        $i++;
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
