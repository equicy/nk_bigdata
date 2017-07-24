<?php

foreach (glob(__DIR__ . '/Controllers/*.php') as $file)
    require_once $file;
foreach (glob(__DIR__ . '/Model/*.php') as $file)
    require_once $file;
