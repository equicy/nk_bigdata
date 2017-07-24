<?php

foreach (glob(__DIR__ . '/Core/*.php') as $file)
    require_once $file;
foreach (glob(__DIR__ . '/*.php') as $file)
    require_once $file;