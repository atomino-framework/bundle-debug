<?php
fwrite($fp = fopen("php://stdout", "w"), "\n".trim(file_get_contents('php://input'))."\n");
fclose($fp);
