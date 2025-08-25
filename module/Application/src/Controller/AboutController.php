<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AboutController extends AbstractActionController
{
  public function aboutAction()
  {
    $page = $this->params()->fromRoute('page', 'me');

    if (!in_array($page, ['me', 'project'])) {
      return $this->notFoundAction();
    }

    $view = new ViewModel([
      'page' => $page
    ]);

    $view->setTemplate("application/about/{$page}");
    return $view;
  }
}
