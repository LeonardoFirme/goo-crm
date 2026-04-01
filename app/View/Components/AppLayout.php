<?php
// app/View/Components/AppLayout.php
namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * O título da página para exibição na aba do navegador e cabeçalhos.
     */
    public $title;

    /**
     * Subtítulo ou descrição curta da página atual.
     */
    public $subtitle;

    /**
     * Create a new component instance.
     */
    public function __construct($title = null, $subtitle = null)
    {
        // Define um título padrão caso nenhum seja enviado
        $this->title = $title ?? config('app.name', 'GooCRM');
        $this->subtitle = $subtitle;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}