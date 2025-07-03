<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\HeaderSetting;
use App\Models\Settings;

class Header extends Component
{
    public $logo;
    public $menuItems;
    public $settings;
    public $social_media_links;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Fetch header settings from the database
        $headerSettings = HeaderSetting::first();
        $this->settings = Settings::first();

        // Assign values to public properties
        $this->logo = $headerSettings ? $headerSettings->logo : null;
        if ($headerSettings && is_string($headerSettings->menu_items)) {
            // If it's a string, decode it into an array
            $this->menuItems = json_decode($headerSettings->menu_items, true);
        } else {
            // If it's already an array, use it directly
            $this->menuItems = $headerSettings ? $headerSettings->menu_items : [];
        }

        if($this->settings && is_string($this->settings->social_media_links)){
            $this->social_media_links = json_decode($this->settings->social_media_links, true);
        }else{
            $this->social_media_links = $this->settings->social_media_links ? $this->settings->social_media_links : [];
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // Return the view for the component
        return view('components.header');
    }
}

