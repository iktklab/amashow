<?php

class Amashow {

    const AMASHOW_URL = 'http://amashow.com/rank.php?kwd=';

    public function getRank($asin) {
        $url = self::AMASHOW_URL.$asin;
        $content = $this->getHtml($url);
        $rank = $this->getRanktoHtml($content);
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

    public function getRanktoHtml($content) {
        $html = str_get_html($content);
        foreach ($html->find('div[id="main"] table font[color="blue"]') as $e) {
            $rank = intval(substr($e->innertext,0,-1));
        }
        if (!isset($rank)) {
            foreach ($html->find('div[id="main"] table') as $e) {
                if (preg_match('/本 - ([0-9]*)位/',$e->innertext, $matches)) {
                    $rank = intval($matches[1]);
                }
            }
        }
        $html->clear();
        if (!isset($rank)) return '';
        return $rank;
    }
}