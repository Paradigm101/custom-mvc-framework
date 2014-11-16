<?php

/**
 * TV show page model
 */
class Tv_Show_PAG_M extends Base_PAG_M {

    // Retrieve data
    static public function getTVShowList() {

        $result = Curl_LIB::send('http://en.wikipedia.org/w/api.php?action=parse&page=Baseball&format=json&prop=text&section=0', null);
//        $result = Curl_LIB::send('http://en.wikipedia.org/w/api.php?format=jsonfm&action=query&titles=Main%20Page&prop=revisions&rvprop=content', null);

$result = json_decode($result);

$result = $result->{'parse'}->{'text'}->{'*'}; // get the main text content of the query (it's parsed HTML)

// pattern for first match of a paragraph
$pattern = '#<p>(.*)</p>#Us'; // http://www.phpbuilder.com/board/showthread.php?t=10352690
if(preg_match($pattern, $result, $matches))
{
    // print $matches[0]; // content of the first paragraph (including wrapping <p> tag)
    $result = strip_tags($matches[1]); // Content of the first paragraph without the HTML tags.
}
//        Log_LIB::trace($result);
        
        return $result;
    }
}
