<?php

function console_dump($value)
{ 
    echo "<script>console.log(" . json_encode($value) . ");</script>";
}
