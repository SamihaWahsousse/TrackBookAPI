<?php

namespace App\Controller\Admin;

use App\Entity\Spotbooks;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SpotbooksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Spotbooks::class;
    }

    //configure fields for the spot Box
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('street'),
            IntegerField::new('zipcode'),
            TextField::new('city'),
            ArrayField::new('goelocalisation'),
            IntegerField::new('capacity'),
        ];
    }
}
