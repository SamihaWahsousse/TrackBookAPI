<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('uuid'),
            TextField::new('name'),
            TextField::new('email'),
            ImageField::new('avatar')
                ->setBasePath('/uploads/avatar/')
                ->setUploadDir('public/uploads/avatar')
                ->setRequired(false),
            CollectionField::new('roles'),
            TextField::new('password'),

        ];
    }
}
