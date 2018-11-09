<?php

function getBrowserSignature() {
    global $CFG;

    $look_at = array( 'x-forwarded-proto', 'x-forwarded-port', 'host',
    'accept-encoding', 'cf-ipcountry', 'user-agent', 'accept', 'accept-language');

    $headers = getallheaders();

    $concat = \Tsugi\Util\Net::getIP();
    if ( isset($CFG->cookiepad) ) $concat .= ':::' . $CFG->cookiepad;
    if ( isset($CFG->cookiesecret) ) $concat .= ':::' . $CFG->cookiesecret;
    $used = array();
    foreach($headers as $k => $v ) {
        if ( ! in_array(strtolower($k), $look_at) ) continue;
        if ( is_string($v) ) { 
            $used[$k] = $v;
            $concat .= ':::' . $k . '=' . $v;
            continue; 
        }
    }

    foreach($_COOKIE as $k => $v ) {
        if ( $k == getTsugiStateCookieName() ) continue;
        $concat .= '===' . $k . '=' . $v;
    }


    $h = hash('sha256', $concat);
    return $h;
}

function getTsugiStateCookieName() {
    return "tsugi-state-lti-advantage";
}
