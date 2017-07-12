<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/IrkfdbClient.php';

class IrkfdbClientTest extends PHPUnit_Framework_TestCase
{
    private function getApiClient()
    {
        return new Irkfdb\IrkfdbClient();
    }

    private function parseResponse($response)
    {
        $status = $response['status'];
        $resultSet = (isset($response['resultSet'])) ? $response['resultSet'] : null;
        $data = (isset($resultSet['data'])) ? $resultSet['data'] : null;

        $message = (isset($response['errorMessage'])) ? $response['errorMessage'] : '';

        return array(
            'status' => $status,
            'data' => $data,
            'errorMessage' => $message
        );
    }

    public function testApiWorking()
    {
        $response = $this->parseResponse($this->getApiClient()->getRandomFact());

        $this->assertEquals('OK', $response['status']);
        $this->assertGreaterThanOrEqual(1, count($response['data']));
        $this->assertEmpty($response['errorMessage']);
    }

    public function testProperCategoryFact()
    {
        $response = $this->parseResponse($this->getApiClient()->fromCategories('geeky')->getRandomFact());

        $this->assertEquals('OK', $response['status']);
        $this->assertEquals(true, in_array('geeky', $response['data'][0]['categories']));
    }
}