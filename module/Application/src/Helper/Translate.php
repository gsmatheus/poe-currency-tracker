<?php

namespace Application\Helper;

use Laminas\View\Helper\AbstractHelper;


// a simple translate helper so i dont have to set traslations in every page (if needed)
class Translate extends AbstractHelper
{
    private array $translations;

    public function __construct(array $translations)
    {
        $this->translations = $translations;
    }

    public function __invoke(string $key): string
    {
        return $this->translations[$key] ?? $key;
    }
}
