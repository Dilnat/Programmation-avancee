<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

interface FlashMessageHelperInterface
{
    public function addFormErrorsAsFlash(FormInterface $form) : void;
    public function addErrorsAsFlash(String $errorMsg) : void;
}