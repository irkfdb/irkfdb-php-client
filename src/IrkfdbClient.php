<?php

namespace Irkfdb;

class IrkfdbClient
{
    const API_URL = 'https://api.irkfdb.in/facts/';
    const VERSION_NUMBER = '1.0';
    const API_TYPE = 'PHP_CLIENT';

    private $firstName;
    private $lastName;

    private $categories;
    private $limitFactsCategories = [];
    private $excludeFactsCategories = [];

    private $isRandom = false;

    /**
     * Sets the first name and the last name.
     *
     * @param $firstName
     * @param $lastName
     *
     * @return $this
     */
    public function setName($firstName, $lastName)
    {
        if (!empty($firstName) && !empty($lastName)) {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
        } else {
            // TODO: Name are empty - throw appropriate exception
            throw new \Exception("firstName & lastName can't be empty");
        }

        return $this;
    }

    /**
     * Set categories you want the facts from.
     *
     * @param1 - categories separated by commas or an array
     *
     * @return $this
     */
    public function fromCategories($categories)
    {
        if (is_string($categories)) {
            $this->limitFactsCategories = array_merge($this->limitFactsCategories, explode(',', $categories));
        }

        if (is_array($categories)) {
            $this->limitFactsCategories = array_merge($this->limitFactsCategories, $categories);
        }

        return $this;
    }

    /**
     * Set categores you dont want the facts from.
     *
     * @param1 - categories separated by commas or an array
     *
     * @return $this
     */
    public function excludeCategories($categories)
    {
        if (is_string($categories)) {
            $this->excludeFactsCategories = array_merge($this->excludeFactsCategories, explode(',', $categories));
        }

        if (is_array($categories)) {
            $this->excludeFactsCategories = array_merge($this->excludeFactsCategories, $categories);
        }

        return $this;
    }

    /**
     * @return array - array of random fact
     */
    public function getRandomFact()
    {
        $this->isRandom = true;

        return $this->makeApiCall();
    }

    /**
     * @return array - array of categories
     */
    public function getCategories()
    {
        $this->categories = true;

        return $this->makeApiCall();
    }

    /**
     * Forms the API url.
     *
     * @return - string the url  of the api call
     */
    private function makeUrl()
    {
        $apiCall = self::API_URL;

        $queryParams = [
            'api_type'       => self::API_TYPE,
            'version_number' => self::VERSION_NUMBER,
        ];

        $strParams = '';

        if ($this->categories === true) {
            $strParams = '?'.http_build_query($queryParams);

            return $apiCall.'categories'.$strParams;
        }

        if (!empty($this->firstName) && !empty($this->lastName)) {
            $queryParams['firstname'] = $this->firstName;
            $queryParams['lastname'] = $this->lastName;
        }

        // checks if random is set
        if ($this->isRandom === true) {
            $apiCall .= 'random';
        }

        // checks if the limitFactsTo is set & adds to the queryParams
        if (count($this->limitFactsCategories) > 0) {
            $queryParams['limitFactsTo'] = implode(',', $this->limitFactsCategories);
        }
        // checks if the excludeFactsFrom is set & adds to the queryParams
        if (count($this->excludeFactsCategories) > 0) {
            $queryParams['excludeFactsFrom'] = implode(',', $this->excludeFactsCategories);
        }

        // check if queryParams exist if yes build the queryString of it
        if (count($queryParams) > 0) {
            $strParams = '?'.http_build_query($queryParams);
        }

        return $apiCall.$strParams;
    }

    /**
     * Makes an api call.
     */
    private function makeApiCall()
    {
        ini_set('display_errors', 0);
        error_reporting(0);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->makeUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        try {
            $data = curl_exec($ch);
            if (curl_errno($ch)) {
                //TODO: throw appropriate exception for curl error
                $data = json_encode([
                    'status'        => 'FAIL',
                    'errorMessage'  => 'Api Unavailable: '.curl_error($ch).', Error Number: '.curl_errno($ch),
                ]);
            }
        } catch (\Exception $e) {
            $data = json_encode([
                'status'        => 'FAIL',
                'errorMessage'  => 'Api Unavailable: '.$e->getMessage(),
            ]);
        }

        return json_decode($data, true);
    }
}
