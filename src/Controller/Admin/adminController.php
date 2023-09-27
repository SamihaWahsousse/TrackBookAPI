<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Entity\Spotbooks;
use App\Entity\User;
use App\Entity\Category;


class adminController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //redirect admin dashboard page to admin.html.twig
        return $this->render('admin/admin.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TrackBookAPI');
    }

    public function configureMenuItems(): iterable
    {
        //configure Menu for admin dashboard page
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('SpotBooks', 'fas fa-boxes-stacked', Spotbooks::class);
        yield MenuItem::linkToCrud('Books', 'fas fa-book', Book::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-person', User::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
    }
}
