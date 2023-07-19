<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatGPTService
{
    private const CHAT_GPT_ENDPOINT = "https://api.openai.com/v1/chat/completions";

    public function __construct(private $API_KEY, private HttpClientInterface $httpClient)
    {
    }

    public function getAnswerFromBot(?string $question = null): string
    {
        if ($question === null) {
            return '';
        }

        $responseData = $this->sendRequest($question, $this->API_KEY);
        $answer = $responseData['choices'][0]['message']['content'] ?? null;

        if (null === $answer) {
            throw new Exception('Pas de reponse');
        }

        return $answer;
    }

    public function sendRequest($question)
    {
        $response = $this->httpClient->request(
            Request::METHOD_POST,
            self::CHAT_GPT_ENDPOINT,
            [
                'headers' => [
                    'Authorization' => "Bearer " . $this->API_KEY,
                ],
                'json' => [
                    "messages" => array(
                        array(
                            "role" => "user",
                            "content" => $question
                        )
                    ),
                    'max_tokens' => 300,
                    'temperature' => 0,
                    'model' => 'gpt-3.5-turbo',
                ],
            ]
        );

        return $response->toArray();
    }
}
