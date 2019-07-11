<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * @coversDefaultClass \App\Http\Controllers\Auth\AuthController
 */
class AuthTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * data provider for function testLogin
     *
     * @return array
     */
    public function loginProvider()
    {
        return array(
            array(
                'dataSet' => array(
                    'formData' => [
                        'email' => 'test@test.com',
                        'password' => 'secret',
                    ],
                ),
                'expectedResult' => array(
                    'statusCode' => 200,
                    'response' => array(
                        'responseType' => 'success',
                        'status' => 200,
                    ),
                ),
            ),
            array(
                'dataSet' => array(
                    'formData' => [
                        'email' => 'test@test.com',
                        'password' => 'invalidPassword',
                    ],
                ),
                'expectedResult' => array(
                    'statusCode' => 400,
                    'response' => array(
                        "responseType" => "error",
                        "error" => "Invalid Credentials",
                    ),
                ),
            ),
            array(
                'dataSet' => array(
                    'formData' => [
                        'email' => 'test@Invalid.com',
                        'password' => 'secret',
                    ],
                ),
                'expectedResult' => array(
                    'statusCode' => 400,
                    'response' => array(
                        "error" => "User does not exist",
                        "responseType" => "error",
                    ),
                ),
            ),
            array(
                'dataSet' => array(
                    'formData' => [
                        'email' => '',
                        'password' => 'secret',
                    ],
                ),
                'expectedResult' => array(
                    'statusCode' => 422,
                    'response' => array(
                        "email" => ["The email field is required."],
                    ),
                ),
            ),
            array(
                'dataSet' => array(
                    'formData' => [
                        'email' => 'test@test.com',
                        'password' => '',
                    ],
                ),
                'expectedResult' => array(
                    'statusCode' => 422,
                    'response' => array(
                        "password" => ["The password field is required."],
                    ),
                ),
            ),
            array(
                'dataSet' => array(
                    'formData' => [
                        'email' => '',
                        'password' => '',
                    ],
                ),
                'expectedResult' => array(
                    'statusCode' => 422,
                    'response' => array(
                        "password" => ["The password field is required."],
                        "email" => ["The email field is required."],
                    ),
                ),
            ),
        );
    }

    /**
     * /login [POST]
     * @covers
     * @dataProvider loginProvider
     */
    public function testLogin($dataSet, $expectedResult)
    {
        $this->post('/login', $dataSet['formData']);
        $this->seeStatusCode($expectedResult['statusCode']);
        $this->seeJson($expectedResult['response']);
    }

    /**
     * data provider for function testRegister
     *
     * @return array
     */
    public function registerProvider()
    {
        return array(
            array(
                'dataSet' => array(
                    'formData' => array(
                        'name' => 'Mukarram Ishaq',
                        'email' => 'test1@muk.com',
                        'password' => 'secret',
                    ),
                ),
                'expectedResult' => array(
                    'statusCode' => 200,
                    'response' => array(
                        'responseType' => 'success',
                        'status' => 200,
                    ),
                ),
            ),
            array(
                'dataSet' => array(
                    'formData' => array(
                        'name' => 'Mukarram Ishaq',
                        'email' => '',
                        'password' => 'secret',
                    ),
                ),
                'expectedResult' => array(
                    'statusCode' => 422,
                    'response' => array(
                        "email" => ["The email field is required."],
                    ),
                ),
            ),
            array(
                'dataSet' => array(
                    'formData' => array(
                        'name' => '',
                        'email' => 'test@muk.com',
                        'password' => 'secret',
                    ),
                ),
                'expectedResult' => array(
                    'statusCode' => 422,
                    'response' => array(
                        "name" => ["The name field is required."],
                    ),
                ),
            ),
            array(
                'dataSet' => array(
                    'formData' => array(
                        'name' => 'Mukarram Ishaq',
                        'email' => 'test@muk.com',
                        'password' => '',
                    ),
                ),
                'expectedResult' => array(
                    'statusCode' => 422,
                    'response' => array(
                        "password" => ["The password field is required."],
                    ),
                ),
            ),
            array(
                'dataSet' => array(
                    'formData' => array(
                        'name' => '',
                        'email' => '',
                        'password' => '',
                    ),
                ),
                'expectedResult' => array(
                    'statusCode' => 422,
                    'response' => array(
                        "password" => ["The password field is required."],
                        "name" => ["The name field is required."],
                        "email" => ["The email field is required."],
                    ),
                ),
            ),
        );
    }

    /**
     * /register [POST]
     * @covers
     * @dataProvider registerProvider
     */
    public function testRegister($dataSet, $expectedResult)
    {
        $this->post('/register', $dataSet['formData']);
        $this->seeStatusCode($expectedResult['statusCode']);
        $this->seeJson($expectedResult['response']);
    }
}
