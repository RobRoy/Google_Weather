<?php
/**
 * Parse Google's weather API (not an official API - part of the calendar API)
 * 
 * PHP >=5 required
 *
 * @author Robert Schmidl
 * @version 0.5
 **/

class googleWeather{
	/**
	 * The place for which the weather should be displayed
	 *
	 * @var string
	 **/
	public $place;
	
	/**
	 * (optional) language string - please use ISO-3166-1 compliant 2-letter codes  
	 * see: http://www.iso.org/iso/country_codes/iso_3166_code_lists/english_country_names_and_code_elements.htm
	 *
	 * defaults to "en"
	 *
	 * @var string
	 **/
	public $locale = "en";
	
	/**
	 * Google weather API endpoint
	 *
	 * @var string
	 */
	private $api_url = 'http://www.google.de/ig/api?weather=';
	
	/**
	 * storage for the result
	 *
	 * @var string
	 **/
	public $api_result;
	
	/**
	 * global error storage
	 *
	 * @var string
	 **/
	private $query_error;
	
	/**
	 * public function to call
	 *
	 * @return array
	**/
	public function get_weather()
	{
		if (strlen($this->place) == 0) {
			die('Please specify a place.');
		}
		
		$this->api_url = $this->api_url . urlencode($this->place) . "&hl=" . $this->locale;
		
		$weather = array();
		
		if(!($this->query_google())) {
			die('Something went wrong. Error: '.$this->query_error);
		}
		else {
			$xml = new SimpleXMLElement($this->api_result);
			
			// location data.
			
			$weather['location']['city'] = $xml->weather->forecast_information->city['data'];
			$weather['location']['zip'] = $xml->weather->forecast_information->postal_code['data'];
			$weather['location']['date'] = $xml->weather->forecast_information->forecast_date['data'];
			$weather['location']['date_time'] = $xml->weather->forecast_information->current_date_time['data'];
			
			// How is the weather currently?
			
			$weather['current']['condition'] = $xml->weather->current_conditions->condition['data'];
			$weather['current']['temp_fahrenheit'] = $xml->weather->current_conditions->temp_f['data'];
			$weather['current']['temp_celsius'] = $xml->weather->current_conditions->temp_c['data'];
			$weather['current']['humidity'] = $xml->weather->current_conditions->humidity['data'];
			$weather['current']['icon'] = 'http://www.google.com' . $xml->weather->current_conditions->icon['data'];
			$weather['current']['wind'] = $xml->weather->current_conditions->wind_condition['data'];

			// How will the weather be in the next couple of days? (all temperature units will correspond to the chosen locale)
		
			for ($i = 0; $i < count($xml->weather->forecast_conditions); $i++){
				$data = $xml->weather->forecast_conditions[$i];
				$weather['forecast'][$i]['day_of_week'] = $data->day_of_week['data'];
				$weather['forecast'][$i]['temp_lowpoint'] = $data->low['data'];
				$weather['forecast'][$i]['temp_highpoint'] = $data->high['data'];
				$weather['forecast'][$i]['icon'] = 'http://img0.gmodules.com/' . $data->icon['data'];
				$weather['forecast'][$i]['condition'] = $data->condition['data'];
			}
		}
		
		return $weather;
	}
	
	/**
	 * queries the weather api
	 *
	 * @return void
	 **/
	private function query_google()
	{
		// create a new cURL resource
		$cr = curl_init();

		// a few nifty options
		curl_setopt ($cr, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cr, CURLOPT_URL, $this->api_url);
		curl_setopt($cr, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($cr, CURLOPT_TIMEOUT, 60);
		curl_setopt($cr, CURLOPT_FOLLOWLOCATION, true);

		if(($this->api_result = curl_exec($cr)) === false)
		{
		    $this->query_error = curl_error($cr);
				curl_close($cr);
				return false;
		}
		else
		{
			 curl_close($cr);
		}
		
		if (empty($this->api_result)) return false;
		
		return true;
		
	}
	
} // END class 


?>