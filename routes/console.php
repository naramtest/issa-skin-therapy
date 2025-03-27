<?php

Schedule::command("model:prune")->daily();
Schedule::command("queue:work --stop-when-empty")
    ->everyMinute()
    ->withoutOverlapping();

Schedule::command("tabby:capture-payments")
    ->everyTenMinutes()
    ->withoutOverlapping();
