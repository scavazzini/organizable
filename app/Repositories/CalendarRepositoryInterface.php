<?php

namespace App\Repositories;

use App\Calendar;
use App\User;

interface CalendarRepositoryInterface
{
    public function getCalendarFromMonth(int $year, int $month, User $user): Calendar;
}
