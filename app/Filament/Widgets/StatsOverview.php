<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User; 
use App\Models\Question; 
use App\Models\QuestionBank; 
use App\Models\Subject; 
use App\Models\Topic;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
           Stat::make('Total Users', User::count()), 
           Stat::make('Total Questions', Question::count()),
           Stat::make('Total Subjects', Subject::count()),
           Stat::make('Total Question Banks', QuestionBank::count()),
           Stat::make('Total Topics', Topic::count())   
        ];
    }
}
