<?php

namespace App\View\Composers;

use App\Services\MenuService;
use Illuminate\View\View;

class MenuComposer
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with('topbarMenu', $this->menuService->getMenuByLocation('topbar'));
        $view->with('mainMenu', $this->menuService->getMenuByLocation('main'));
    }
}
