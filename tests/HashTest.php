<?php

/**
 * @coversDefaultClass \App\Http\Controllers\HashController
 */
class HashTest extends TestCase
{
    /**
     * data provider for function testHash
     *
     * @return array
     */
    public function hashProvider(){
        return array(
            array(
                'dataSet' => array(
                    'stringToBeHashed' => 'hello'
                ),
                'expectedResult' => array(
                    'statusCode' => 200,
                    'response' => array(
                        'hash' => "49ejotyDINSX27chmrwBGLQV05afkpuzEJOTY38dinsxCHMRW16bglqvAFKPUZ49ejotyDINSX27chmrwBGLQV05afkpuzEJOTY38dinsxCHMRW16bglqvAFKPUZ49ej",
                    ),
                ),
            ),
            array(
                'dataSet' => array(
                    'stringToBeHashed' => ''
                ),
                'expectedResult' => array(
                    'statusCode' => 200,
                    'response' => array(),
                ),
            ),
        );
    }
    /**
     * /hash [GET]
     *
     * @covers
     * @dataProvider hashProvider
     */
    public function testHash($dataSet, $expectedResult)
    {
        $this->get('/hash/'.$dataSet['stringToBeHashed']);
        $this->seeStatusCode($expectedResult['statusCode']);
        $this->seeJson($expectedResult['response']);
    }
}
