<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AssistantWidget extends Component
{
    public $userInput = '';
    public $messages = [];
    public $sessionId;

    public function mount()
    {
        $this->sessionId = Session::getId();
        // Cargar mensajes desde Redis
        try {
            $this->messages = Cache::store('redis')->get('chatbot_messages_' . $this->sessionId, [
                ['role' => 'assistant', 'content' => '¡Hola! Soy tu asistente botánico. ¿Buscas algo fresco para el día o cálido para la noche?']
            ]);
        } catch (\Throwable $e) {
            // Fallback si Redis falla
            Log::error('Redis Error en Chatbot: ' . $e->getMessage());
            $this->messages = [
                ['role' => 'assistant', 'content' => '¡Hola! Soy tu asistente botánico. ¿Buscas algo fresco para el día o cálido para la noche?']
            ];
        }
    }

    public function sendMessage()
    {
        if (trim($this->userInput) === '') return;

        // Añadir mensaje de usuario
        $this->messages[] = ['role' => 'user', 'content' => $this->userInput];
        $this->userInput = '';

        $this->saveToRedis();

        // Preparar el array de mensajes para Ollama incluyendo el System Prompt
        $apiMessages = [
            [
                'role' => 'system',
                'content' => 'Eres un Asistente Olfativo Botánico experto para la tienda Esencia. Tu objetivo es ayudar a los clientes a elegir la fragancia o aroma perfecto basándote en sus preferencias (fresco, cálido, amaderado, cítrico, etc.). Eres amable, elegante, sutil y usas analogías relacionadas con la naturaleza y la botánica. Tus respuestas deben ser muy concisas y amigables. Recomienda a los usuarios buscar en la tienda por familias aromáticas. Nunca reveles que eres una IA generativa, siempre mantén tu personaje de sommelier botánico.'
            ]
        ];

        // Añadir el historial de la conversación
        foreach ($this->messages as $msg) {
            $apiMessages[] = $msg;
        }

        try {
            // Llamada a la API de Groq (OpenAI compatible)
            $response = Http::timeout(45)
                ->withToken(env('GROQ_API_KEY'))
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
                    'messages' => $apiMessages,
                    'stream' => false,
                ]);

            if ($response->successful()) {
                // Formato OpenAI: choices[0].message.content
                $botMessage = $response->json('choices.0.message.content');

                if ($botMessage) {
                    $this->messages[] = ['role' => 'assistant', 'content' => $botMessage];
                } else {
                    $this->messages[] = ['role' => 'assistant', 'content' => 'No pude procesar tu solicitud adecuadamente.'];
                }
            } else {
                // Maneja 401, 403, 404, 500
                $this->messages[] = ['role' => 'assistant', 'content' => 'Estoy teniendo problemas técnicos para conectarme con mis servidores. Por favor, intenta de nuevo más tarde.'];
                Log::error('Chatbot API Error: ' . $response->status() . ' - ' . $response->body());
            }
        } catch (\Throwable $e) {
            // Captura errores de Timeout o Host Not Found sin que Livewire crashee
            $this->messages[] = ['role' => 'assistant', 'content' => 'Mis sistemas están desconectados o tardan mucho en responder. Disculpa las molestias.'];
            Log::error('Chatbot Exception: ' . $e->getMessage());
        }

        $this->saveToRedis();
    }

    public function resetChat()
    {
        $this->messages = [
            ['role' => 'assistant', 'content' => '¡Hola! Soy tu asistente botánico. ¿Buscas algo fresco para el día o cálido para la noche?']
        ];
        $this->saveToRedis();
    }

    private function saveToRedis()
    {
        try {
            Cache::store('redis')->put('chatbot_messages_' . $this->sessionId, $this->messages, now()->addHours(2));
        } catch (\Throwable $e) {
            Log::error('Redis Error al guardar en Chatbot: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.assistant-widget');
    }
}
