<?php

namespace App\backend;

use App\frontend\Manager;

class Login extends Manager
{
    public function authAdmin()
    {
        // algo password: PASSWORD_BCRYPT
        if (isset($_POST['inputLogin']) && isset($_POST['inputPassword'])) {
            $nameAuth = 'Jean' || 'greg';
            if ($_POST['inputLogin'] == $nameAuth || $_COOKIE['nameAdminConnected'] == $nameAuth) {
                $db = $this->dbConnect();
                $passwd_bdd = $db->prepare("SELECT `password`,`first_name` FROM `p5_oc_admin_users` WHERE `login` = ?");
                $passwd_bdd->execute(array($_POST['inputLogin']));
                $passwd_result = $passwd_bdd->fetch();
                if (password_verify($_POST['inputPassword'], $passwd_result['password'])) {
                    $name = $passwd_result['first_name'];
                    setcookie('passwordAdminConnected', $_POST['inputPassword'], time() + 365 * 24 * 3600, null, null, false, true);
                    setcookie('nameAdminConnected', $name, time() + 365 * 24 * 3600, null, null, false, true);
                    header('location:index.php?action=adminCnx&name=' . $name);
                } else {
                    echo '<script>alert("Le mot de passe est invalide")</script>';
                    echo '<script>document.location.href="index.php?action=loginForm"</script>';
                }
            } else {
                echo 'Login invalide.';
            }
        } else {
            echo '<script>alert("Erreur d\'authentification")</script>';
            echo '<script>document.location.href="index.php?action=loginForm"</script>';
        }
    }
}
