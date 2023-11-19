<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>
<script>
    function checkFunction(id, check) {
        let textVar = $('#id').val();
        if ($('#check').checked) {
            textVar.style.background = 'red';
        } else {
            textVar.style.background = 'white';
        }
    }

</script>