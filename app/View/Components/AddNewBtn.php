<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddNewBtn extends Component
{
    public $btnId;
    /**
     * Create a new component instance.
     */
    public function __construct($btnId)
    {
        $this->btnId=$btnId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.add-new-btn');
    }
}
