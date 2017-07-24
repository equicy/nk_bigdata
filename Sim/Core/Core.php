<?php

namespace Sim\Core;

class Core {
    public static function start() {
        Route::exec();
    }
}