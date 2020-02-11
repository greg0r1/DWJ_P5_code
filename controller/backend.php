<?php

use App\backend\Login;
use App\backend\PostManagerBo;
use App\backend\CommentManagerBo;

function loginForm()
{
    $twig = loadTwig();

    echo $twig->render('/backend/login.twig', ['title' => 'Connexion']);
}

function loginCnx()
{
    $loginAuth = new Login();
    $loginAuth->authAdmin();
}

function adminCnx()
{
    if (isset($_COOKIE['nameAdminConnected'])) {
        $twig = loadTwig();
        echo $twig->render('/backend/home.twig', ['title' => 'Accueil - Admin', 'cookieName' => $_COOKIE['nameAdminConnected']]);
    } else {
        throw new Exception('Erreur d\'authentification', 1);
    }
}

function createPost()
{
    $twig = loadTwig();
    echo $twig->render('/backend/createPost.twig', ['title' => 'Création de post']);
}

function addPost()
{
    $twig = loadTwig();

    $postManager = new PostManagerBo();

    $postManager->addNewPost();
    $name = $_COOKIE['nameAdminConnected'];
    echo $twig->render('/backend/addedPost.twig', ['name' => $name]);
}

function listPostsCRUD()
{
    $twig = loadTwig();

    $postManager = new PostManagerBo();

    // Pagination
    $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
    $limit = 10;
    $start = ($page - 1) * $limit;
    $number_total_posts = $postManager->paging();
    $number_of_pages = ceil($number_total_posts / $limit);

    $posts = $postManager->getPostsCRUD($limit, $start);

    echo $twig->render('/backend/listPosts.twig', ['posts' => $posts, 'page' => $page, 'number_of_pages' => $number_of_pages, 'number_total_posts' => $number_total_posts, 'title' => 'Gestionnaire de Billets']);
    $posts->closeCursor();
}

function editPost($postId)
{
    $twig = loadTwig();

    $postManager = new PostManagerBo();

    $post = $postManager->modifyPostCRUD($postId);
    echo $twig->render('/backend/editPost.twig', ['post' => $post, 'title' => 'Modification du billet']);
}

function updatePost($idPost, $title, $content)
{
    $twig = loadTwig();

    $postManager = new PostManagerBo();

    $postManager->updatingPost($idPost, $title, $content);
    echo $twig->render('/backend/updatedPost.twig', ['idPost' => $idPost]);
}

function deletingPost($idPost)
{
    $twig = loadTwig();

    $postManager = new PostManagerBo();

    $postManager->deletePost($idPost);
    echo $twig->render('/backend/deletedPost.twig', ['idPost' => $idPost]);
}

function listCommentsCRUD()
{
    $twig = loadTwig();

    $commentManager = new CommentManagerBo();

    // Pagination
    $page = (!empty(strip_tags($_GET['page']))) ? $_GET['page'] : 1;
    $limit = 10;
    $start = ($page - 1) * $limit;
    $number_total_comments = $commentManager->paging();
    $number_of_pages = ceil($number_total_comments / $limit);


    $comments = $commentManager->getCommentsCRUD($limit, $start);
    if ($comments == false) {
        throw new Exception("Erreur: Les commentaires n'ont pas été récupérés.", 1);
    } else {
        echo $twig->render('/backend/listComments.twig', ['comments' => $comments, 'page' => $page, 'number_total_comments' => $number_total_comments, 'number_of_pages' => $number_of_pages, 'title' => 'Gestionnaire de commentaires']);
        $comments->closeCursor();
    }
}

function editComment($commentId)
{
    $twig = loadTwig();

    $commentManager = new CommentManagerBo();

    $comment = $commentManager->modifyCommentCRUD($commentId);
    echo $twig->render('/backend/editComment.twig', ['comment' => $comment, 'title' => 'Modification du commentaire']);
}

function updateComment($idComment, $comment)
{
    $twig = loadTwig();

    $commentManager = new CommentManagerBo();

    $commentManager->updatingComment($idComment, $comment);
    echo $twig->render('/backend/updatedComment.twig', ['idComment' => $idComment]);
}

function reportedCommentsListCRUD()
{
    $twig = loadTwig();

    $commentManager = new CommentManagerBo();

    // Pagination
    $page = (!empty(strip_tags($_GET['page']))) ? $_GET['page'] : 1;
    $limit = 10;
    $start = ($page - 1) * $limit;
    $number_total_comments = $commentManager->pagingReportedCommentsList();
    $number_of_pages = ceil($number_total_comments / $limit);


    $comments = $commentManager->getReportedCommentsCRUD($limit, $start);
    if ($comments == false) {
        throw new Exception("Erreur: Les commentaires signalés n'ont pas été récupérés.", 1);
    } else {
        echo $twig->render('/backend/reportedCommentsList.twig', ['comments' => $comments, 'page' => $page, 'number_total_comments' => $number_total_comments, 'number_of_pages' => $number_of_pages, 'title' => 'Gestionnaire de commentaires reportés']);
        $comments->closeCursor();
    }
}

function deletingComment($idComment)
{
    $twig = loadTwig();

    $commentManager = new CommentManagerBo();

    $deleteComment = $commentManager->deleteComment($idComment);
    if ($deleteComment == false) {
        throw new Exception("Erreur: le commentaire n'a pas été supprimé.", 1);
    } else {
        echo $twig->render('/backend/deletedComment.twig', ['idComment' => $idComment]);
        $deleteComment->closeCursor();
    }
}
