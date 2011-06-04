Google_Weather
==============

This is a small PHP class to query the Google Weather API. As the use of this API is not officially
endorsed by Google please use this with appropriate care.

Usage
-----

To use this class just copy googleWeather.php into your project and use it from within your project files like so:

  $gw = new googleWeather();
  $gw->place = "berlin, germany";
  $gw->locale = "de";
  $test = $gw->get_weather();

get_weather() returns an array with all the information from the Google API. For a reference see below.
For an example on how to use the data from this array see the index.php in the root.

### Array Contents

  --location
    --city
    --zip
    --date
    --datetime

  --current
    --condition
    --temp_fahrenheit
    --temp_celsius
    --humidity
    --icon
    --wind
  
  --forecast
    --1
      --day_of_week
      --temp_lowpoint
      --temp_highpoint
      --icon
      --condition
    --2
      --day_of_week
      --temp_lowpoint
      --temp_highpoint
      --icon
      --condition
    .
    .
    .

Features
--------

1. query the (unofficial) Google Weather API
2. possibility to set a language for the returned data strings 

Wishlist (a.k.a. the stuff I will add when I get around to it)
--------------------------------------------------------------

1. caching of a queried results
2. add the (official) Yahoo! Weather API as a fallback/2nd option (possibly renaming the project)