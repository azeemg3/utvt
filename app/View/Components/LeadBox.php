<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LeadBox extends Component
{
    /**
     * Create a new component instance.
     */
    public $lead;

    public function __construct(array $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.lead-box');
    }
}
