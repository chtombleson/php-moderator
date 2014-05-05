<?php
require(dirname(__DIR__) . '/src/PHPModerator.php');
use chtombleson\PHPModerator;

class ModeratorTest extends PHPUnit_Framework_TestCase
{
    public function testModerator()
    {
        $data = $this->getTestData();
        $moderator = new PHPModerator();

        $result = $moderator->moderate($data->pass);
        $this->assertEquals('pass', $result['status']);

        $result = $moderator->moderate($data->bad_words);
        $this->assertEquals('fail', $result['status']);

        $result = $moderator->moderate($data->spam_words);
        $this->assertEquals('fail', $result['status']);

        $result = $moderator->moderate($data->spam_and_bad_words);
        $this->assertEquals('fail', $result['status']);

        $result = $moderator->moderate($data->spam_email);
        $this->assertEquals('fail', $result['status']);
    }

    public function testModerateHighThreshold()
    {
        $data = $this->getTestData();
        $moderator = new PHPModerator(1.0);

        $result = $moderator->moderate($data->pass);
        $this->assertEquals('pass', $result['status']);

        $result = $moderator->moderate($data->bad_words);
        $this->assertEquals('pass', $result['status']);

        $result = $moderator->moderate($data->spam_words);
        $this->assertEquals('fail', $result['status']);

        $result = $moderator->moderate($data->spam_and_bad_words);
        $this->assertEquals('pass', $result['status']);

        $result = $moderator->moderate($data->spam_email);
        $this->assertEquals('pass', $result['status']);
    }

    public function testModerateNoThreshold()
    {
        $data = $this->getTestData();
        $moderator = new PHPModerator(0.0);

        $result = $moderator->moderate($data->pass);
        $this->assertEquals('pass', $result['status']);

        $result = $moderator->moderate($data->bad_words);
        $this->assertEquals('fail', $result['status']);

        $result = $moderator->moderate($data->spam_words);
        $this->assertEquals('fail', $result['status']);

        $result = $moderator->moderate($data->spam_and_bad_words);
        $this->assertEquals('fail', $result['status']);

        $result = $moderator->moderate($data->spam_email);
        $this->assertEquals('fail', $result['status']);
    }

    private function getTestData()
    {
        $file = file_get_contents(__DIR__ . '/test-data.json');
        return json_decode($file);
    }
}
