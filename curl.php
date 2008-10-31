<?php

/**
* Open an url on https using curl and return content
*
* @author hatem <info@phptunisie.net>
* @param string url            The url to open
* @param string refer        Referer (optional)
* @param mixed usecookie    If true, cookie.txt    will be used as default, or the usecookie value.
* @return string
*/
function open_https_url($url,$use_post = false,$usecookie = false) {

    if ($usecookie) {
        if (file_exists($usecookie)) {
            if (!is_writable($usecookie)) {
                return "Can't write to $usecookie cookie file, change file permission to 777 or remove read only for windows.";
            }
        } else {
            $usecookie = "cookie.txt";
            if (!is_writable($usecookie)) {
                return "Can't write to $usecookie cookie file, change file permission to 777 or remove read only for windows.";
            }
        }
    }
    $ch = curl_init();
    if( !curl_setopt($ch, CURLOPT_URL, $url) )
    {
      fclose($fp); // to match fopen()
      curl_close($ch); // to match curl_init()
      return "FAIL: curl_setopt(CURLOPT_URL)";
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    //curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    if ($usecookie) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $usecookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $usecookie);   
    }

    if ($use_post) {
        curl_setopt($ch, CURLOPT_POST, true );
    }
    else {
        curl_setopt($ch, CURLOPT_HTTPGET, true );
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $result =curl_exec ($ch);
    curl_close ($ch);
   
    return $result;
}
?>

