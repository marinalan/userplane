<?php
$value = "01/30/1967";
if (!empty($value) && 
    ereg("([0-9]{2})/([0-9]{2})/([0-9]{4})", $value, $regs)) {

  echo "$regs[3]-$regs[1]-$regs[2]";        
    }
else {
    echo "$value is not properly formatted";
}
