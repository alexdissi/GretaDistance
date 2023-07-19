<?php

namespace App\Tests\Service;

use App\Service\ChatGPTService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatGPTServiceTest extends TestCase
{
    public function testGetAnswerFromBotReturnsAnswer(): void
    {
        // Créez un double du HttpClientInterface
        $httpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Ceci est la réponse du bot.',
                        ],
                    ],
                ],
            ])),
        ]);

        // Instanciez le service avec le double du client HTTP et la clé API
        $chatGPTService = new ChatGPTService('API_KEY', $httpClient);

        // Appelez la méthode à tester
        $question = 'Comment vas-tu ?';
        $answer = $chatGPTService->getAnswerFromBot($question);

        // Vérifiez les résultats
        $this->assertSame('Ceci est la réponse du bot.', $answer);
    }

    public function testGetAnswerFromBotReturnsEmptyStringOnNullQuestion(): void
    {
        // Créez un double du HttpClientInterface
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Instanciez le service avec le double du client HTTP et la clé API
        $chatGPTService = new ChatGPTService('API_KEY', $httpClient);

        // Appelez la méthode à tester sans question
        $answer = $chatGPTService->getAnswerFromBot(null);

        // Vérifiez les résultats
        $this->assertSame('', $answer);
    }

    public function testGetAnswerFromBotThrowsExceptionOnNullAnswer(): void
    {
        // Créez un double du HttpClientInterface
        $httpClient = new MockHttpClient([
            new MockResponse(json_encode(['choices' => []])),
        ]);

        // Instanciez le service avec le double du client HTTP et la clé API
        $chatGPTService = new ChatGPTService('API_KEY', $httpClient);

        // Vérifiez que l'exception est levée lorsqu'il n'y a pas de réponse
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Pas de reponse');

        $question = 'Quelle est la capitale de la France ?';
        $chatGPTService->getAnswerFromBot($question);
    }

    public function testSendRequestReturnsResponseData(): void
    {
        // Créez un double du HttpClientInterface
        $httpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Ceci est la réponse du bot.',
                        ],
                    ],
                ],
            ])),
        ]);

        // Instanciez le service avec le double du client HTTP et la clé API
        $chatGPTService = new ChatGPTService('API_KEY', $httpClient);

        // Appelez la méthode à tester
        $question = 'Quelle est la capitale de la France ?';
        $responseData = $chatGPTService->sendRequest($question);

        // Vérifiez les résultats
        $expectedData = [
            'choices' => [
                [
                    'message' => [
                        'content' => 'Ceci est la réponse du bot.',
                    ],
                ],
            ],
        ];
        $this->assertSame($expectedData, $responseData);
    }
}
