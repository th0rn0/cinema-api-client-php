<?php

namespace Tests\Functional\Api\Chain;

use DCG\Cinema\Exception\UnexpectedResponseContentException;
use DCG\Cinema\Exception\UnexpectedStatusCodeException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Functional\Mocks\MockGuzzleClientDi;
use Tests\Functional\Mocks\MockGuzzleClientFactory;
use Tests\Functional\Mocks\MockSession;

class ChainsProviderTest extends TestCase
{
    public function testItReturnsTheExpectedChains()
    {
        $mockGuzzleClientFactory = new MockGuzzleClientFactory(
            new MockHandler([
                new Response(200, [], json_encode([
                    'data' => [
                        [
                            'id' => 'idValue0',
                            'name' => 'nameValue0',
                            'maximum_number_of_tickets' => 100,
                            'introduction_instructions' => 'introValue0',
                            'how_to_redeem' => 'howToRedeemValue0',
                        ],
                        [
                            'id' => 'idValue1',
                            'name' => 'nameValue1',
                            'maximum_number_of_tickets' => 101,
                            'introduction_instructions' => 'introValue1',
                            'how_to_redeem' => 'howToRedeemValue1',
                        ],
                    ],
                ]))
            ])
        );

        $di = MockGuzzleClientDi::buildMockDi(MockSession::createWithActiveUserToken(), $mockGuzzleClientFactory);

        $chains = $di->getChainsProvider()->getChains();

        $this->assertCount(2, $chains);

        $this->assertEquals('idValue0', $chains[0]->getId());
        $this->assertEquals('nameValue0', $chains[0]->getName());
        $this->assertEquals(100, $chains[0]->getMaxTickets());
        $this->assertEquals('introValue0', $chains[0]->getIntroductionInstructions());
        $this->assertEquals('howToRedeemValue0', $chains[0]->getHowToRedeem());

        $this->assertEquals('idValue1', $chains[1]->getId());
        $this->assertEquals('nameValue1', $chains[1]->getName());
        $this->assertEquals(101, $chains[1]->getMaxTickets());
        $this->assertEquals('introValue1', $chains[1]->getIntroductionInstructions());
        $this->assertEquals('howToRedeemValue1', $chains[1]->getHowToRedeem());

        $requestHistory = $mockGuzzleClientFactory->getHistory();
        $this->assertCount(1, $requestHistory);
        $this->assertEquals('GET', $requestHistory[0]['request']->getMethod());
        $this->assertEquals('chains', (string)$requestHistory[0]['request']->getUri());
    }

    public function testItThrowsOnUnexpectedStatusCode()
    {
        $di = MockGuzzleClientDi::buildMockDi(
            MockSession::createWithActiveUserToken(),
            new MockGuzzleClientFactory(new MockHandler([new Response(201)]))
        );

        $this->expectException(UnexpectedStatusCodeException::class);

        $di->getChainsProvider()->getChains();
    }

    public function testItThrowsOnUnexpectedResponseContent()
    {
        $di = MockGuzzleClientDi::buildMockDi(
            MockSession::createWithActiveUserToken(),
            new MockGuzzleClientFactory(
                new MockHandler([
                    new Response(200, [], json_encode([
                        'data' => [
                            [
                                'id' => 'idValue0',
                            ],
                        ],
                    ]))
                ])
            )
        );

        $this->expectException(UnexpectedResponseContentException::class);

        $di->getChainsProvider()->getChains();
    }
}
