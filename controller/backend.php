<?php

use App\backend\Login;
use App\backend\PostManagerBo;
use App\backend\CommentManagerBo;


function loginForm()
{
    require('./view/backend/login.php');
}

function loginCnx()
{
    $loginAuth = new Login();
    $loginAuth->authAdmin();
}

function adminCnx()
{
    if (isset($_COOKIE['nameAdminConnected'])) {
        require('./view/backend/home.php');
    } else {
        echo '<script>alert("Erreur d\'authentification")</script>';
        echo '<script>document.location.href="index.php?action=loginForm"</script>';
    }
}

function createPost()
{
    require('./view/backend/createPost.php');
}

function addPost()
{
    $postManager = new PostManagerBo();

    if (isset($_POST['tinymceTitle']) && isset($_POST['tinymceContent'])) {
        $postManager->addNewPost();
        require('./view/backend/home.php');
        echo '<script>alert(\'Votre billet à bien été ajouté ' . $_COOKIE['nameAdminConnected'] . '\')</script>';
    }
}

function listPostsCRUD()
{
    $postManager = new PostManagerBo();

    // Pagination
    $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
    $limit = 10;
    $start = ($page - 1) * $limit;
    $number_total_posts = $postManager->paging();
    $number_of_pages = ceil($number_total_posts / $limit);


    $posts = $postManager->getPostsCRUD($limit, $start);

    require('./view/backend/listPosts.php');
}

function editPost($postId)
{
    $postManager = new PostManagerBo();

    $post = $postManager->modifyPostCRUD($postId);
    require('./view/backend/editPost.php');
}

function updatePost($idPost, $title, $content)
{
    $postManager = new PostManagerBo();

    $postManager->updatingPost($idPost, $title, $content);
    require('./view/backend/updatedPost.php');
}

function deletingPost($idPost)
{
    $postManager = new PostManagerBo();

    $postManager->deletePost($idPost);
    require('./view/backend/deletedPost.php');
}

function listCommentsCRUD()
{
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
        require('./view/backend/listComments.php');
    }
}

function editComment($commentId)
{
    $commentManager = new CommentManagerBo();

    $comment = $commentManager->modifyCommentCRUD($commentId);
    require('./view/backend/editComment.php');
}

function updateComment($idComment, $comment)
{
    $commentManager = new CommentManagerBo();

    $commentManager->updatingComment($idComment, $comment);
    require('./view/backend/updatedComment.php');
}

function reportedCommentsListCRUD()
{
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
        require('./view/backend/reportedCommentsList.php');
    }
}

function deletingComment($idComment)
{
    $commentManager = new CommentManagerBo();

    $deleteComment = $commentManager->deleteComment($idComment);
    if ($deleteComment == false) {
        throw new Exception("Erreur: le commentaire n'a pas été supprimé.", 1);
    } else {
        require('./view/backend/deletedComment.php');
        $deleteComment->closeCursor();
    }
}
