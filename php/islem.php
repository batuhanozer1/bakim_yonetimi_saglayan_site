<?php 
ob_start();
session_start();

require 'baglan.php';

if(strip_tags(trim(isset($_POST['kayit'])))){
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars(md5($_POST['password']));
    $password_again =  htmlspecialchars(md5(@$_POST['password_again']));

    if(!$username){
        echo "<span style='color:red; background-color:blue'>kullanıcı adı giriniz</span>";
        header('Refresh:3; index.php');
    }elseif(!$password && !$password_again){
        echo "şifrenizi giriniz.";
        header('Refresh:3; index.php');
    }elseif($password != $password_again){
        echo 'girdiğiniz şifreler birbiriyle eşleşmiyor.';
        header('Refresh:3; kayit.php');
        
    }else{
        $sorgu = $db->prepare('INSERT INTO kullanicilar SET kullaniciAd = ?, sifre = ?');
        $ekle = $sorgu->execute([
            $username, $password
        ]);
        if($ekle){
            echo 'kayıt başarıyla gerçekleşti.';
            header('Refresh:3; index.php');
        }else{
            echo 'bir hata oluştu bilgilerinizi tekrar kontrol ediniz.';
            header('Refresh:3; index.php');
            
        }
    }
}
if(strip_tags(trim(isset($_POST['giris'])))){
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars(md5($_POST['password']));

    if(!$username && !$password){
        echo 'kullanıcı adı ve şifre giriniz';
        header('Refresh:3; index.php');
    }elseif(!$username){
        echo 'kullanıcı adı giriniz';
        header('Refresh:3; index.php');
    }elseif(!$password){
        echo 'şifrenizi girin';
        header('Refresh:3; index.php');
    }else{
        $kullSor = $db->prepare('SELECT * FROM kullanicilar WHERE kullaniciAd = ? && sifre = ?');
        $kullSor -> execute([
            $username, $password
        ]);

        $say = $kullSor->rowCount();
        if($say==1){
            $_SESSION['username']=$username;
            echo 'başarıyla giriş yapıldı. yönlendiriliyorsunuz..';
            header('Refresh:3; anasayfa.php');
        }else{
            echo 'kullanıcı ad ya da şifre yanlış. bilgilerinizi tekrar kontrol edin.';
            header('Refresh:3; index.php');
        }
    }
}

?>