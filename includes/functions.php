<?php
function formatMoney($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to get active class for sidebar
function is_active($currect_page){
  $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
  $url = end($url_array);  
  if($currect_page == $url){
      return 'active'; //class name in css 
  } 
  return '';
}
?>
