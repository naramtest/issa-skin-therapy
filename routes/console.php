<?php

Schedule::command("model:prune")->daily();
Schedule::command("attachments:cleanup")->daily();
Schedule::command("post-body:change")->daily();
Schedule::command("orders:cleanup-abandoned")->everyTenMinutes();
Schedule::command("queue:work --stop-when-empty")
    ->everyMinute()
    ->withoutOverlapping();
