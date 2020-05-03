<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Preprocessing
{
    public function process($text, $stopword = true, $lower = true, $filter = true)
    {
        $ret = [];
        $file_stopword = "application/stopword/stopword.txt";
        $GLOBALS['stopwords'] = explode("\n", file_get_contents($file_stopword));

        if ($lower) {
            $text_lower = strtolower($text);
        } else {
            $text_lower = $text;
        }

        if ($filter) {
            $text_filtered = preg_replace("/[^a-zA-Z0-9\s .]/", "", $text_lower);
        } else {
            $text_filtered = $text_lower;
        }
        
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
        $stemmer  = $stemmerFactory->createStemmer();

        if ($lower) {
            $text_segment_stem = $stemmer->stem($text_filtered);
        } else {
            $text_segment_stem = $text_filtered;
        }

        $text_tokenization = explode(" ", $text_segment_stem);

        if ($stopword) {
            $text_stopwordremove = array_filter($text_tokenization, function ($key) {
                return !in_array($key, $GLOBALS['stopwords']);
            });
        } else {
            $text_stopwordremove = $text_tokenization;
        }

        return $text_stopwordremove;
    }
}
