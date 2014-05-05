<?php
namespace chtombleson;

class PHPModerator
{
    protected $lists = array(),
        $threshold;

    public function __construct($threshold = null, $bad_word_list = null, $spam_word_list = null)
    {
        $threshold = empty($threshold) ? 0.10 : $threshold;
        $this->setThreshold($threshold);

        $bad_word_list  = empty($bad_word_list) ? __DIR__ . '/lists/bad-words.json' : $bad_word_list;
        $this->setBadWordList($bad_word_list);

        $spam_word_list = empty($spam_word_list) ? __DIR__ . '/lists/spam-words.json' : $spam_word_list;
        $this->setSpamWordList($spam_word_list);
    }

    public function getThreshold()
    {
        return $this->threshold;
    }

    public function setThreshold($threshold)
    {
        $this->threshold = $threshold;
    }

    public function getBadWordList()
    {
        return $this->lists['bad_words'];
    }

    public function setBadWordList($bad_word_list)
    {
        if (!file_exists($bad_word_list)) {
            throw new PHPModeratorException('Bad word file: ' . $bad_word_list . ' does not exist!');
        } else {
            $this->lists['bad_words'] = $bad_word_list;
        }
    }

    public function getSpamWordList()
    {
        return $this->lists['spam_words'];
    }

    public function setSpamWordList($spam_word_list)
    {
        if (!file_exists($spam_word_list)) {
            throw new PHPModeratorException('Spam word file: ' . $bad_word_list . ' does not exist!');
        } else {
            $this->lists['spam_words'] = $spam_word_list;
        }
    }

    public function moderate($content)
    {
        $word_count = $this->getWordCount($content);
        $bad_word_count = $this->getBadWordCount($content);
        $spam_word_count = $this->getSpamWordCount($content);

        $percentage = (($bad_word_count + $spam_word_count) / $word_count);

        $result = array(
            'bad_words'     => $bad_word_count,
            'spam_words'    => $spam_word_count,
        );

        if ($percentage <= $this->getThreshold()) {
            $result['status'] = 'pass';
        } else {
            $result['status'] = 'fail';
        }

        return $result;
    }

    private function getWordCount($content)
    {
        return str_word_count($content);
    }

    private function getBadWordCount($content)
    {
        $bad_words = $this->loadJson($this->getBadWordList());
        $bad_word_count = 0;

        foreach ($bad_words as $bad_word) {
            if (strripos($content, $bad_word) !== false) {
                $bad_word_count++;
            }
        }

        return $bad_word_count;
    }

    private function getSpamWordCount($content)
    {
        $spam_words = $this->loadJson($this->getSpamWordList());
        $spam_word_count = 0;

        foreach ($spam_words as $spam_word) {
            if (strripos($content, $spam_word) !== false) {
                $spam_word_count++;
            }
        }

        return $spam_word_count;
    }

    private function loadJson($file)
    {
        $file = file_get_contents($file);
        return json_decode($file);
    }
}

class PHPModeratorException extends \Exception {}
