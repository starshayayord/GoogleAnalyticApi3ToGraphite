<?php

class GoogleAnalyticsToGraphite {
    /** @var float Graphite ignores values of 0, so we put in a real small value to get the datapoint recorded */
    const GRAPHITE_ALMOST_ZERO = 0.0001;
  //  $realm = str_replace('.','_',$realm);
    private  $graphite_data_prefix = 'GoogleAnalytics';

    public function __construct($graphite_host, $graphite_port) {
        $this->graphite_connection = fsockopen($graphite_host, $graphite_port, $error_number, $error_string, 100);

        if (!$this->graphite_connection) {
            throw new Exception("Cannot connect to graphite: $error_string ($error_number)\n");
        }

    }
public function pipeForGraphite(
	$metric_name, $metric_value, $realm
){	
        $graphite_format = '';
	$time = time();
        $graphite_format .= $this->graphite_data_prefix . '.' . $realm . '.' . $metric_name . ' ' . $metric_value . ' ' . $time ."\n";          
        print_r($graphite_format);
        fwrite($this->graphite_connection, $graphite_format);
	}
}
?>
