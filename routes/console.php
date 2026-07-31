<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('pages:publish-scheduled')->everyMinute();
