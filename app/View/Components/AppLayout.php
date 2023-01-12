<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{
    public $layout, $dir, $options;

    public function __construct($layout = '', $dir=false, $options = [])
    {
        $this->layout = $layout;
        $this->dir = $dir;
        $this->options = $options;
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        switch($this->layout){
            // case 'horizontal':
            //     return view('layouts.dashboard.horizontal');
            // break;
            // case 'dualhorizontal':
            //     return view('layouts.dashboard.dual-horizontal');
            // break;
            // case 'dualcompact':
            //     return view('layouts.dashboard.dual-compact');
            // break;
            // case 'boxed':z
            //     return view('layouts.dashboard.boxed');
            // break;
            // case 'boxedfancy':
            //     return view('layouts.dashboard.boxed-fancy');
            // break;
            // case 'simple':
            //     return view('layouts.dashboard.simple');
            // break;
            // case 'admin':
            //     return view('layouts.dashboard.admin');
            //     break;
            default:
                return view('layouts.dashboard.dashboard', ['options' => $this->options]);
            break;
        }
    }
}
