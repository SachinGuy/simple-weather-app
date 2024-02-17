<?php 
include "credentials.php";

$city = isset($_POST["city"]) && !empty($_POST["city"]) ? $_POST["city"] : "pokhara";

// Initialize cURL session: Client for URLs Handles sending & receiving files over HTTP.
$ch = curl_init(); 

// Set cURL options
curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return as string instead of ouputting directly.

// Execute cURL session and get the response
$weatherContent = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    die("Error fetching weather data: " . curl_error($ch));
}

// Close cURL session
curl_close($ch);

// Decode JSON to PHP associative array
$weather = json_decode($weatherContent, true);

// Check for API error i.e. if cod element exists & if it isn't 200 throw error.
if (isset($weather['cod']) && $weather['cod'] != 200) {
    die("Error from OpenWeatherMap API: " . $weather['message']);
}

// Print the results!
echo "<p> City: " . $weather['name'] . "<br>";
echo "Latitude: " . $weather['coord']['lat'] . "<br>";
echo "Longitude: " . $weather['coord']['lon'] . "<br>";
echo "Temperature: " . $weather['main']['temp'] . "°C </p>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Weather App"?></title>
</head>
<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="city">City: </label>
        <input type="text" id="city" name="city">
        <input type="submit" value="Submit">
    </form>
</body>
</html>