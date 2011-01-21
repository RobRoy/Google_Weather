<?

	// This is just an example usage for the Google_Weather class.
	
	require_once('lib/googleWeather.php');
	
	$gw = new googleWeather();
	$gw->place = "berlin, germany";
	$gw->locale = "de";
	$test = $gw->get_weather();
?>	
<p>
<? echo '<img src="'.$test['current']['icon'].'">'; ?>
</p>
<p>
<? echo $test['current']['condition']; ?>
</p>
<p>
<? echo $test['current']['temp_celsius']; ?>
</p>
			
