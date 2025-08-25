<?php

declare(strict_types=1);

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ItemController extends AbstractActionController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function viewAction()
    {
      $currencyName = $this->params()->fromRoute('currency', null);

      if (!$currencyName) {
          return $this->notFoundAction();
      }
      $icon = '';

      // fetch all occurrences of that currency
      $items = $this->entityManager->getRepository(\Application\Entity\CurrencyRate::class)
          ->findBy(['currency' => $currencyName, 'league'=>'Mercenaries']);

      // fetch item definition info
      $definition = $this->entityManager->getRepository(\Application\Entity\Item::class)
          ->findOneBy(['name' => $currencyName]);

      $description = $definition?->getDescription() ?? '';
      $directions  = $definition?->getDirections() ?? '';
      $stackSize   = $definition?->getStackSize() ?? null;

      if (empty($items)) {
          return $this->notFoundAction();
      }

      foreach ($items as $item) {
          if ($item->getIcon()) {
              $icon = $item->getIcon();
              break;
          }
      }

      return new ViewModel([
        'currency'    => $currencyName,
        'items'       => $items,
        'icon'        => $icon,
        'description' => $description,
        'directions'  => $directions,
        'stackSize'   => $stackSize,
      ]);
    }
}


