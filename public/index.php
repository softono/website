<?php
// Front controller: the web server sends every request that is not a real file here.
require __DIR__ . '/../system/bootstrap.php';
dispatch();
