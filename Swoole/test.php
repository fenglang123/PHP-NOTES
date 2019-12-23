<?php

for ($i = 0; $i < 4; $i++) {
    Swoole\Coroutine::create(function () use ($i) {
        sleep(1);
        echo microtime(true) . ": hello $i \n";
    });
};