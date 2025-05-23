<?php

Schedule::command("model:prune")->daily();
Schedule::command("queue:work --stop-when-empty")
    ->everyMinute()
    ->withoutOverlapping();
Schedule::command("attachments:cleanup")->daily();
Schedule::command("post-body:change")->daily();
Schedule::command("currency:refresh-rates")->daily();
Schedule::command("orders:cleanup-abandoned")->everyTenMinutes();

// Prepare new orders for DHL every 10 minutes
Schedule::command("dhl:prepare-orders")
    ->everyTenMinutes()
    ->withoutOverlapping();

Schedule::command("sitemap:generate")->daily()->withoutOverlapping();

// Process tracking updates from DHL every 5 minutes
Schedule::command("dhl:process-tracking")
    ->everyTenMinutes()
    ->withoutOverlapping();

Schedule::command("tabby:capture-payments")
    ->everyTenMinutes()
    ->withoutOverlapping();

Schedule::command("catalog:export-facebook")->dailyAt("00:00");
Schedule::command("catalog:export-tiktok")->dailyAt("00:00");
