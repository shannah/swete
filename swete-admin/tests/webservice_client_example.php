<?php
/**
 * @param string $html The HTML content to be translated.
 * @param string $url The URL of the SWeTE proxy site.  Can be any URL in the proxy
 *      but will be treated as the Base HREF for the page content.
 * @param string $password The webservice secret key.  Should match the value of 
 *      the webservice_secret_key field of the website in SWeTE.
 * @returns string Translated version of $html.
 */
function translateContent($html, $url, $password, $contentType='text/html'){
    // Salt should be current time (unix timestamp).
    $salt = time();
    
    // Key should be sha1 hash of salt concatenated with password
    $key = sha1($salt.$password);
    
    // POST parameters to pass to the web service
    $data = array(
        'swete:input' => $html,
        'swete:salt' => $salt,
        'swete:key' => $key,
        'swete:content-type' => $contentType
    );
    
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

function translatePlainText($text, $url, $password){
    return translateContent($text, $url, $password, 'text/plain');
}

function translateHtml($html, $url, $password){
    return translateContent($html, $url, $password, 'text/html');
}
/*
$result = translateContent(
<<<END
    <h3>Writing with non-translatables</h3>
            <p>Hello, my name is <span data-swete-translate="0">Steve Hannah</span>.  The <span data-swete-translate="1">Blue Jays</span> are my favourite team.</p>
            <p>Hello, my name is <span data-swete-translate="0">John Smith</span>.  The <span data-swete-translate="1">Giants</span> are my favourite team.</p>
            <p>This is a new string</p>
END
,
    'http://test.swetedemo.weblite.ca/demosite4/index.html',
    'foobar'
);


var_dump($result);


$result2 = translatePlainText('Hello World', 'http://test.swetedemo.weblite.ca/demosite4/index.html', 'foobar' );
echo "Result : ".$result2;
 *
 */
$result = translateContent(
<<<END
    <h3>Hello World</h3>
    <p>Hello, my name is <span data-swete-translate="0">Steve Hannah</span>.  
       The <span data-swete-translate="1">Blue Jays</span> are my favourite team.
    </p>
END
,
    'http://test.swetedemo.weblite.ca/demosite4/index.html',
    'foobar'
);

echo "First Result:\n";
echo $result;


$result2 = translatePlainText(
    'Hello World', 
    'http://test.swetedemo.weblite.ca/demosite4/index.html', 
    'foobar' 
);


echo "\r\n\r\nSecond Result:\n";
echo $result2;