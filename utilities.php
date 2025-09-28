<?php

function filter($string) {
    return htmlspecialchars(trim(strip_tags($string)));
}