<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GOMainTwigExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter']),
            new TwigFilter('nombre', [$this, 'nombreFilter']),
            new TwigFilter('sum', [$this, 'sumFilter']),
        ];
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ','): string
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        return '$' . $price;
    }

    public function nombreFilter($number, $decimals = 0, $decPoint = ',', $thousandsSep = ' '): string
    {
        return number_format($number, $decimals, $decPoint, $thousandsSep);
    }

    public function sumFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ','): string
    {
        return number_format($number, $decimals, $decPoint, $thousandsSep);
    }
}
