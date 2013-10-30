<?php

require_once dirname(__FILE__).'/../lib/amashow.php';
require_once dirname(__FILE__).'/../vendor/simple_html_dom.php';

// ここにASINコードを入力してください
$asin = 'XXXXXXXXXX';

$amashow = new Amashow();
echo $amashow->getRank($asin);
