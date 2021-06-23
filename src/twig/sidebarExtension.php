<?php

namespace App\twig;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;
use Twig\Error\RuntimeError;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class sidebarExtension extends AbstractExtension
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var Environment
     */
    private $twig;


    public function __construct(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        UserRepository $userRepository,
        TagRepository $tagRepository,
        Environment $twig
    )
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
        $this->tagRepository = $tagRepository;
        $this->twig = $twig;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sidebar', [$this, 'getSidebar'], ['is_safe' => ['html']])
        ];
    }

    public function getSidebar(): string
    {
        $articles = $this->articleRepository->popularArticles();
        $articlesAll = $this->articleRepository->findAll();
        $categories = $this->categoryRepository->sidebarCategories();
        $categoriesAll = $this->categoryRepository->findAll();
        $users = $this->userRepository->findAll();
        $views = $this->articleRepository->totalViews();
        $tags = $this->tagRepository->findAll();

        // return $this->twig->render('home/sidebar.html.twig', [
        //     'articles' => $articles,
        //     'articlesAll' => $articlesAll,
        //     'commentaires' => $commentaires,
        //     'commentairesAll' => $commentairesAll,
        //     'categories' => $categories,
        //     'users' => $users,
        //     'vues' => $vues
        // ]);

        try {
            return $this->twig->render('home/sidebar.html.twig',
                compact('articles', 'articlesAll', 'categories', 'categoriesAll', 'users', 'views', 'tags'));
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
        }
    }
}
