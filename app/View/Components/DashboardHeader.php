<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Footer as FooterModel;

class DashboardHeader extends Component
{
    public $logo;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $footer = FooterModel::first();
        $this->logo = $footer ? $footer->logo : null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-header');
    }
}
