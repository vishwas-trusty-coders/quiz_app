<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Footer as FooterModel;
use App\Models\Settings;

class Footer extends Component
{
    public $footer;
    public $settings;
    public $menuitems;
    public $custom_links;
    public $social_media_links;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->footer = FooterModel::first();
        $this->settings = Settings::first();

        if($this->footer && is_string($this->footer->social_links)){
            $this->social_media_links = json_decode($this->footer->social_links, true);
        }else{
            $this->social_media_links = $this->footer->social_links ? $this->footer->social_links : [];
        }

        if($this->footer && is_string($this->footer->links)){
            $this->menuitems = json_decode($this->footer->links, true);
        }else{
            $this->menuitems = $this->footer->links ? $this->footer->links : [];
        }

        if($this->footer && is_string($this->footer->custom_links)){
            $this->custom_links = json_decode($this->footer->custom_links, true);
        }else{
            $this->custom_links = $this->footer->custom_links ? $this->footer->custom_links : [];
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer');
    }
}
