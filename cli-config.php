<?php
require_once __DIR__ . '/database/bootstrap.php';

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(

));

return $helperSet;
