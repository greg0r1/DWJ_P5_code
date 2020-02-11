<?php

use App\frontend\PostManager;
use App\frontend\CommentManager;

function listPosts()
{
    $twig = loadTwig();
    $postManager = new PostManager();
    // Pagination
    $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
    $limit = 6;
    $start = ceil(($page - 1) * $limit);
    $number_total_posts = $postManager->paging();
    $number_of_pages = ceil($number_total_posts / $limit);


    $posts = $postManager->getPosts($limit, $start);

    echo $twig->render('/frontend/listPosts.twig', ['posts' => $posts, 'page' => $page, 'number_of_pages' => $number_of_pages, 'title' => 'Liste des articles']);
    $posts->closeCursor();
}

function post($postId)
{
    $twig = loadTwig();

    $postManager = new PostManager();
    $commentManager = new CommentManager();

    $comments = $commentManager->getComments($postId);
    $post = $postManager->getPost($postId);
    if ($comments == false) {
        throw new Exception("Error de récupération des commentaires.", 1);
    } elseif ($post == false) {
        throw new Exception("Error de récupération du post.", 1);
    } else {
        echo $twig->render('/frontend/postView.twig', ['post' => $post, 'comment' => $comments, 'title' => $post['title']]);
        $post->closeCursor();
        $comments->closeCursor();
    }
}

function addComment($postId, $author, $comment)
{
    $commentManager = new CommentManager();

    $insertline = $commentManager->postComment($postId, $author, $comment);

    if ($insertline == false) {
        throw new Exception("Impossible d\'ajouter le commentaire !", 1);
    } else {
        header('location:index.php?action=post&id=' . $postId);
    }
}

function addReportComment($idComment)
{
    $commentManager = new CommentManager();

    $commentManager->reportComment($idComment);
}
