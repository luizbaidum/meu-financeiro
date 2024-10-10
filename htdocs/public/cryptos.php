<?php  
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);

    $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';

    $find_class = 'YMlKec fxKbKc';

    $pagina = curl_init();
    curl_setopt($pagina, CURLOPT_USERAGENT, $agent);
    curl_setopt($pagina, CURLOPT_URL, 'https://www.google.com/finance/quote/BTC-BRL');
    curl_setopt($pagina, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($pagina);
    curl_close($pagina);
    
    $dom->loadHTML($html);
    $tag = $dom->getElementsByTagName('div');

    foreach ($tag as $value) {
        if (strpos($value->getAttribute('class'), $find_class) !== false){
            $content = $value->nodeValue;
            
            print_r('btc: ' . $content);

            break;
        }
    }

    echo '<br>';

    $pagina = curl_init();
    curl_setopt($pagina, CURLOPT_USERAGENT, $agent);
    curl_setopt($pagina, CURLOPT_URL, 'https://www.google.com/finance/quote/LTC-BRL');
    curl_setopt($pagina, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($pagina);
    curl_close($pagina);
    
    $dom->loadHTML($html);
    $tag = $dom->getElementsByTagName('div');

    foreach ($tag as $value) {
        if (strpos($value->getAttribute('class'), $find_class) !== false){
            $content = $value->nodeValue;
            
            print_r('ltc: ' . $content);

            break;
        }
    }

    echo '<br>';

    $tag_2 = $dom->getElementsByTagName('div');

    foreach ($tag_2 as $value) {
        /*if (strpos($value->getAttribute('class'), 'ushogf') !== false){
            $content = $value->nodeValue;
            
            print_r($content);

            break;
        }*/

        //print_r($value);
    }
?>