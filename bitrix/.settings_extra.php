<?php
return array (
    'cache' => array(
        'value' => array (
            'type' => 'memcache',
            'memcache' => array(
                'host' => 'unix:///tmp/memcached.sock',
                'port' => '0'
            ),
            'sid' => $_SERVER["DOCUMENT_ROOT"]."#01"
        ),
    ),
);
?>