<?php
function translate($content, $data = [])
{
   return strtolower(trim(str_replace(".", "", $content)));
}
function translateValidationErrorsOfApi($content, $data = [])
{
    return translate($content, $data);
}

function frontTrans($details, $data = [])
{
    return translate($details, $data);
}


