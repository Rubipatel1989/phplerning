<?php

//          *  
//        *  *  
//      *  *  *  
//    *  *  *  *  
//  *  *  *  *  *  

$size = 4;
for ($i = 0; $i <= $size; $i++) {
    for ($j = 0; $j <= $size - $i; $j++) {
        echo "&nbsp;&nbsp;";
    }
    for ($k = 0; $k <= $i; $k++) {
        echo "*&nbsp;&nbsp;";
    }
    echo "<br />";
}
