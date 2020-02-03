<?php

namespace App\frontend;

class CommentManager extends Manager
{
    public function getComments($postId)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare("SELECT p5_oc_comments.id, p5_oc_comments.author, p5_oc_comments.comment, p5_oc_comments.comment_date, DATE_FORMAT(comment_date, ', le %d/%m/%Y à %Hh%imin') AS comment_date_formatted FROM `p5_oc_comments` INNER JOIN `p5_oc_posts` ON `post_id` = p5_oc_posts.id WHERE p5_oc_posts.id = ?");
        $comments->execute(array($postId));

        return $comments;
    }

    public function postComment($postId, $author, $comment)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare("INSERT INTO `p5_oc_comments`(`post_id`, `author`, `comment`, `comment_date`) VALUES (?,?,?,NOW())");
        $insertline = $comments->execute(array($postId, $author, $comment));

        return $insertline;
    }

    public function reportComment($idComment)
    {
        $db = $this->dbConnect();
        $addReportComment = $db->prepare("UPDATE `p5_oc_comments` SET `reported` = '1' WHERE `id` = ?");
        $addReportComment->execute(array($idComment));

        if ($addReportComment == false) {
            throw new \Exception("Error: Le commentaire n'a pas pu être signalé", 1);
        } else {
            $alertMessReq = $db->prepare("SELECT `author` FROM `p5_oc_comments` WHERE `id` = ?");
            $alertMessReq->execute(array($idComment));

            $alertMess = $alertMessReq->fetch();

            echo '<script>alert("Le commentaire de ' . $alertMess['author'] . ' a bien été signalé")</script>';
        }

        $alertMessReq->closeCursor();
        $addReportComment->closeCursor();
    }
}
