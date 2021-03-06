<?php

namespace DCG\Cinema\Tests\Unit\Request;

use DCG\Cinema\ActiveUserToken\ActiveUserTokenProvider;
use DCG\Cinema\Exception\UserNotAuthenticatedException;
use DCG\Cinema\Request\Client;
use DCG\Cinema\Request\ClientResponse;
use DCG\Cinema\Request\GuzzleClientFactoryInterface;
use DCG\Cinema\Request\RequestSender;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class ClientTest extends MockeryTestCase
{
    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetSuccess()
    {
        $clientResponse = new ClientResponse(['metaKey' => 'metaValue'], ['dataKey' => 'dataValue']);

        $guzzleClientMock = Mockery::mock(\GuzzleHttp\ClientInterface::class);

        $guzzleClientFactoryMock = Mockery::mock(GuzzleClientFactoryInterface::class);
        $guzzleClientFactoryMock->shouldReceive('create')->with('userTokenValue')->andReturn($guzzleClientMock)->once();

        $activeUserTokenProviderMock = Mockery::mock(ActiveUserTokenProvider::class);
        $activeUserTokenProviderMock->shouldReceive('getUserToken')->andReturn('userTokenValue')->atLeast()->once();

        $requestSenderMock = Mockery::mock(RequestSender::class);
        $requestSenderMock
            ->shouldReceive('sendRequest')
            ->with($guzzleClientMock, 'GET', 'pathValue', ['queryKey' => 'queryValue'], null, [300])
            ->andReturn($clientResponse)
            ->once();

        $client = new Client($guzzleClientFactoryMock, $activeUserTokenProviderMock, $requestSenderMock);
        $result = $client->get('pathValue', ['queryKey' => 'queryValue'], [300]);

        $this->assertEquals($clientResponse, $result);
    }

    public function testGetThrowsWhenUserNotAuthenticated()
    {
        $guzzleClientFactoryMock = Mockery::mock(GuzzleClientFactoryInterface::class);
        $guzzleClientFactoryMock->shouldNotReceive('create');

        $activeUserTokenProviderMock = Mockery::mock(ActiveUserTokenProvider::class);
        $activeUserTokenProviderMock->shouldReceive('getUserToken')->andReturn(null)->atLeast()->once();

        $requestSenderMock = Mockery::mock(RequestSender::class);
        $requestSenderMock->shouldNotReceive('sendRequest');

        $this->expectException(UserNotAuthenticatedException::class);

        $client = new Client($guzzleClientFactoryMock, $activeUserTokenProviderMock, $requestSenderMock);
        $client->get('pathValue', ['queryKey' => 'queryValue'], [300]);
    }

    public function testPostSuccess()
    {
        $clientResponse = new ClientResponse(['metaKey' => 'metaValue'], ['dataKey' => 'dataValue']);

        $guzzleClientMock = Mockery::mock(\GuzzleHttp\ClientInterface::class);

        $guzzleClientFactoryMock = Mockery::mock(GuzzleClientFactoryInterface::class);
        $guzzleClientFactoryMock->shouldReceive('create')->with('userTokenValue')->andReturn($guzzleClientMock)->once();

        $activeUserTokenProviderMock = Mockery::mock(ActiveUserTokenProvider::class);
        $activeUserTokenProviderMock->shouldReceive('getUserToken')->andReturn('userTokenValue')->atLeast()->once();

        $requestSenderMock = Mockery::mock(RequestSender::class);
        $requestSenderMock
            ->shouldReceive('sendRequest')
            ->with($guzzleClientMock, 'POST', 'pathValue', [], 'bodyValue', [300])
            ->andReturn($clientResponse)
            ->once();

        $client = new Client($guzzleClientFactoryMock, $activeUserTokenProviderMock, $requestSenderMock);
        $result = $client->post('pathValue', 'bodyValue', [300]);

        $this->assertEquals($clientResponse, $result);
    }

    public function testPostThrowsWhenUserNotAuthenticated()
    {
        $guzzleClientFactoryMock = Mockery::mock(GuzzleClientFactoryInterface::class);
        $guzzleClientFactoryMock->shouldNotReceive('create');

        $activeUserTokenProviderMock = Mockery::mock(ActiveUserTokenProvider::class);
        $activeUserTokenProviderMock->shouldReceive('getUserToken')->andReturn(null)->atLeast()->once();

        $requestSenderMock = Mockery::mock(RequestSender::class);
        $requestSenderMock->shouldNotReceive('sendRequest');

        $this->expectException(UserNotAuthenticatedException::class);

        $client = new Client($guzzleClientFactoryMock, $activeUserTokenProviderMock, $requestSenderMock);
        $client->post('pathValue', 'bodyValue', [300]);
    }

    public function testPatchSuccess()
    {
        $clientResponse = new ClientResponse(['metaKey' => 'metaValue'], ['dataKey' => 'dataValue']);

        $guzzleClientMock = Mockery::mock(\GuzzleHttp\ClientInterface::class);

        $guzzleClientFactoryMock = Mockery::mock(GuzzleClientFactoryInterface::class);
        $guzzleClientFactoryMock->shouldReceive('create')->with('userTokenValue')->andReturn($guzzleClientMock)->once();

        $activeUserTokenProviderMock = Mockery::mock(ActiveUserTokenProvider::class);
        $activeUserTokenProviderMock->shouldReceive('getUserToken')->andReturn('userTokenValue')->atLeast()->once();

        $requestSenderMock = Mockery::mock(RequestSender::class);
        $requestSenderMock
            ->shouldReceive('sendRequest')
            ->with($guzzleClientMock, 'PATCH', 'pathValue', [], 'bodyValue', [300])
            ->andReturn($clientResponse)
            ->once();

        $client = new Client($guzzleClientFactoryMock, $activeUserTokenProviderMock, $requestSenderMock);
        $result = $client->patch('pathValue', 'bodyValue', [300]);

        $this->assertEquals($clientResponse, $result);
    }

    public function testPatchThrowsWhenUserNotAuthenticated()
    {
        $guzzleClientFactoryMock = Mockery::mock(GuzzleClientFactoryInterface::class);
        $guzzleClientFactoryMock->shouldNotReceive('create');

        $activeUserTokenProviderMock = Mockery::mock(ActiveUserTokenProvider::class);
        $activeUserTokenProviderMock->shouldReceive('getUserToken')->andReturn(null)->atLeast()->once();

        $requestSenderMock = Mockery::mock(RequestSender::class);
        $requestSenderMock->shouldNotReceive('sendRequest');

        $this->expectException(UserNotAuthenticatedException::class);

        $client = new Client($guzzleClientFactoryMock, $activeUserTokenProviderMock, $requestSenderMock);
        $client->patch('pathValue', 'bodyValue', [300]);
    }

    public function testGetUnauthenticated()
    {
        $clientResponse = new ClientResponse(['metaKey' => 'metaValue'], ['dataKey' => 'dataValue']);

        $guzzleClientMock = Mockery::mock(\GuzzleHttp\ClientInterface::class);

        $guzzleClientFactoryMock = Mockery::mock(GuzzleClientFactoryInterface::class);
        $guzzleClientFactoryMock->shouldReceive('createUnauthenticated')->andReturn($guzzleClientMock)->once();

        $activeUserTokenProviderMock = Mockery::mock(ActiveUserTokenProvider::class);
        $activeUserTokenProviderMock->shouldNotReceive('getUserToken');

        $requestSenderMock = Mockery::mock(RequestSender::class);
        $requestSenderMock
            ->shouldReceive('sendRequest')
            ->with($guzzleClientMock, 'GET', 'pathValue', ['queryKey' => 'queryValue'], null, [300])
            ->andReturn($clientResponse)
            ->once();

        $client = new Client($guzzleClientFactoryMock, $activeUserTokenProviderMock, $requestSenderMock);
        $result = $client->getUnauthenticated('pathValue', ['queryKey' => 'queryValue'], [300]);

        $this->assertEquals($clientResponse, $result);
    }

    public function testPostUnauthenticated()
    {
        $clientResponse = new ClientResponse(['metaKey' => 'metaValue'], ['dataKey' => 'dataValue']);

        $guzzleClientMock = Mockery::mock(\GuzzleHttp\ClientInterface::class);

        $guzzleClientFactoryMock = Mockery::mock(GuzzleClientFactoryInterface::class);
        $guzzleClientFactoryMock->shouldReceive('createUnauthenticated')->andReturn($guzzleClientMock)->once();

        $activeUserTokenProviderMock = Mockery::mock(ActiveUserTokenProvider::class);
        $activeUserTokenProviderMock->shouldNotReceive('getUserToken');

        $requestSenderMock = Mockery::mock(RequestSender::class);
        $requestSenderMock
            ->shouldReceive('sendRequest')
            ->with($guzzleClientMock, 'POST', 'pathValue', [], 'bodyValue', [300])
            ->andReturn($clientResponse)
            ->once();

        $client = new Client($guzzleClientFactoryMock, $activeUserTokenProviderMock, $requestSenderMock);
        $result = $client->postUnauthenticated('pathValue', 'bodyValue', [300]);

        $this->assertEquals($clientResponse, $result);
    }
}
