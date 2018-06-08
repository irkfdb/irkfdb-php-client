<?php

namespace Irkfdb;

use PHPUnit\Framework\TestCase;

class IrkfdbClientTest extends TestCase
{
    private function getApiClient()
    {
        return new IrkfdbClient();
    }

    private function parseResponse($response)
    {
        $status = $response['status'];
        $resultSet = (isset($response['resultSet'])) ? $response['resultSet'] : null;
        $data = (isset($resultSet['data'])) ? $resultSet['data'] : null;

        $message = (isset($response['errorMessage'])) ? $response['errorMessage'] : '';

        return [
            'status'        => $status,
            'data'          => $data,
            'errorMessage'  => $message,
        ];
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

    public function testCustomNameFact()
    {
        $firstName = 'Swapnil';
        $lastName = 'Sarwe';
        $response = $this->parseResponse($this->getApiClient()->setName($firstName, $lastName)->getRandomFact());
        $this->assertEquals('OK', $response['status']);
        $this->assertEquals(true, (stristr($response['data'][0]['fact'], $firstName) !== false || stristr($response['data'][0]['fact'], $lastName) !== false));
    }
}
