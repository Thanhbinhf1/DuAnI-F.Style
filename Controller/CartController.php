<?php
if(session_status() === PHP_SESSION_NONE) session_start();

class CartController {

    public function addToCart(){
        $id    = $_GET['id'];
        $name  = $_GET['name'];
        $price = $_GET['price'];
        $img   = $_GET['img'];

        if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        if(isset($_SESSION['cart'][$id])){
            $_SESSION['cart'][$id]['quantity']++;
        }else{
            $_SESSION['cart'][$id] = [
                "name"=>$name,
                "price"=>$price,
                "image"=>$img,
                "quantity"=>1
            ];
        }

        header("Location: index.php?ctrl=cart&act=viewcart");
    }

    public function decrease(){
        $id = $_GET['id'];

        if($_SESSION['cart'][$id]['quantity'] > 1){
            $_SESSION['cart'][$id]['quantity']--;
        }else{
            unset($_SESSION['cart'][$id]);
        }

        header("Location: index.php?ctrl=cart&act=viewcart");
    }

    public function remove(){
        $id = $_GET['id'];
        unset($_SESSION['cart'][$id]);

        header("Location: index.php?ctrl=cart&act=viewcart");
    }

    public function clear(){
        unset($_SESSION['cart']);
        header("Location: index.php?ctrl=cart&act=viewcart");
    }

    public function viewcart(){
        include_once './Views/users/cart.php';
    }
}
