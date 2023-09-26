<?php 

namespace Kaizerenrique\Consultabcv;

use Illuminate\Support\Facades\Http;

class Consultabcv{

	/**
	* Esta función no recibe ningún parámetro 
	*
	* @return Retorna un número decimal.
	*/

	public function valorbcv()
    {
	    try {
	    	$url = 'http://bcv.org.ve/';

	    	$response = Http::withOptions([
				'verify' => false,
			])->get($url);
			
	    	$respuesta = $response->getBody()->getContents();// accedemos a el contenido
			$text = strip_tags($respuesta); //limpiamos

			$findme = 'USD'; 
			$pos = strpos($text, $findme);

			$rempl = array('USD');

		    $r = trim(str_replace($rempl, '|', self::limpiarCampo($text)));
		    $resource = explode("|", $r);
		    $datos = explode(" ", self::limpiarCampo($resource[2])); 

		    $usd = $datos[0]; //obtenemos el valor del USD
		    $num=str_replace(',','.',$usd); //reemplazamos la coma por un punto
		    $valor = floatval($num); //convertimos en un numero
	    	return  $valor;

    	} catch (\Illuminate\Http\Client\ConnectionException $e) {
	        report($e);	 
	        return false;
	        
	    }
    }

    public static function limpiarCampo($valor) {//Con esto limpiamos los errores de la pagina
        $rempl = array('\n', '\t');
        $r = trim(str_replace($rempl, ' ', $valor));
        return str_replace("\r", "", str_replace("\n", "", str_replace("\t", "", $r)));
    }
}