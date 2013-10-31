<?php

class Amashow {

    const AMASHOW_URL = 'http://amashow.com/rank.php?kwd=';

    public function getRank($asin, $category = '') {
        $url = self::AMASHOW_URL.$asin;
        $content = $this->getHtml($url);
        $rank = $this->getRanktoHtml($content, $category);
        return $rank;
    }

    public function getHtml($url, $timeout = 60) {
        $user_agent = 'Mozilla/5.0 (Windows NT 5.1; rv:21.0) Gecko/20130401 Firefox/21.0';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // UserAgentの設定
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        // 結果を文字列として返す
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // redirect先も取得
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // timeout値
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        $response = curl_exec($ch);

        if ($response) {
            return $response;
        } else {
            echo curl_error($ch);
            return '';
        }
        curl_close($ch);
    }

    public function getRanktoHtml($content, $category) {
        $html = str_get_html($content);
        if ($category == 'Books') {
            foreach ($html->find('div[id="main"] table') as $e) {
                if (preg_match('/本 - ([0-9]*)位/',$e->innertext, $matches)) {
                    $rank = $matches[1];
                }
            }
        } else {
            foreach ($html->find('div[id="main"] table font[color="blue"]') as $e) {
                $rank = substr($e->innertext,0,-1);
            }
        }
        if (!$rank) return '';
        return $rank;
    }
}