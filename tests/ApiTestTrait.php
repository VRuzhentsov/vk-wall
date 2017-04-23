<?php

namespace Tests;

use Illuminate\Http\ResponseTrait;

trait ApiTestTrait
{
    use ResponseTrait;
    
    public function assertApiResponse(Array $actualData)
    {
        $this->assertApiSuccess();
        
        $response = json_decode($this->response->getContent(), true);
        $responseData = isset($response['data'][0]) ? $response['data'][0] : $response['data'];
        
        $this->assertNotEmpty($responseData['id']);
        $this->assertModelData($responseData, $actualData);
    }
    
    public function assertApiSuccess()
    {
        $this->response->assertStatus(200);
        $this->response->assertJson(['success' => true]);
    }
    
    public function assertModelData(Array $actualData, Array $expectedData)
    {
        foreach ($expectedData as $key => $value) {
            if ( ! in_array($key, ['password', 'remember_token'])) {
                $this->assertEquals($actualData[$key], $expectedData[$key]);
            }
        }
    }
}