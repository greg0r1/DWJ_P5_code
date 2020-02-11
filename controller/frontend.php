<?php

use App\frontend\PostManager;
use App\frontend\CommentManager;

function listPosts()
{
    $postManager = new PostManager();
    // Pagination
    $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
    $limit = 6;
    $start = ($page - 1) * $limit;
    $number_total_posts = $postManager->paging();
    $number_of_pages = ceil($number_total_posts / $limit);


    $posts = $postManager->getPosts($limit, $start);

    require('./view/frontend/listPostsView.php');
}

function post($postId)
{
    $postManager = new PostManager();
    $commentManager = new CommentManager();

    $comments = $commentManager->getComments($postId);
    $post = $postManager->getPost($postId);
    if ($comments == false) {
        throw new Exception("Error de récupération des commentaires.", 1);
    } elseif ($post == false) {
        throw new Exception("Error de récupération du post.", 1);
    } else {
        require('./view/frontend/postView.php');
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
