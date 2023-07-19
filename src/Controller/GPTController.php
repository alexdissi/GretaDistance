<?php

namespace App\Controller;

use App\Service\ChatGPTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GPTController extends AbstractController
{
    #[Route('/api/gpt', name: 'api_gpt', methods: ['POST'])]
    public function processForm(Request $request, ChatGPTService $chatGPTService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $question = "genere moi un plan de cours dans les grande lignes sur  " . $data['question'] . " en quelque mots" ?? null;

        $answer = $chatGPTService->getAnswerFromBot($question);

        $responseData = [
            'answer' => $answer,
            'question' => $question,
        ];

        return new JsonResponse($responseData);
    }
}
