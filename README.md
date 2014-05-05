# PHPModerator

Static text analysis for inapporiate content.

## Setup

Install using composer

    $ composer.phar require chtombleson\php-moderator

## Usage

    <?php
    require_once(__DIR__ . /vendor/autoload.php');
    use chtombleson\PHPModerator;

    $moderator = new PHPModerator();
    $message = "Long message from user you want to statically moderate";
    $result = $moderator->moderate($message);

    if ($result['status'] == 'pass') {
        echo "Message is below the threshold of: " . ($moderator->getThreshold() * 100) . "%";
        echo "Messgae contained the following amount of bad words: " . $result['bad_words'];
        echo "Message contained the following amount of spam words: " . $result['spam_words'];
        ehco "Message contains the following number of words: " . str_word_count($message);
    } else {
        echo "Message fails static analysis of a threshold of:  . ($moderator->getThreshold() * 100) . "%";
        echo "Messgae contained the following amount of bad words: " . $result['bad_words'];
        echo "Message contained the following amount of spam words: " . $result['spam_words'];
        ehco "Message contains the following number of words: " . str_word_count($message);
    }

## License

See LICENSE
