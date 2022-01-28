<?php 
namespace App\Util;

class Paginator
{
    protected $perPage;
    protected $nPages = 1;
    protected $firstItem = 1;
    protected $currentPage = 1;

    public function __construct(int $nItems, ?int $currentPage = 1, ?int $perPage = 25 )
    {
        $this->perPage = $perPage;
        // Calcule du nombre de pages total 
        if ($nItems > $perPage) {
            $this->nPages = ceil($nItems / $this->perPage);
            // Calcul du 1er item de la page 
            $this->firstItem = ($currentPage * $this->perPage) - $this->perPage;
        }
    }

    public function getNPages(): int
    {
        return $this->nPages;
    }

    public function getFirstitem(): int
    {
        return $this->firstItem;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }
}