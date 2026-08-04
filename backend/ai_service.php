<?php
// ai_service.php
include_once 'load_env.php';

class AIService {
    private $apiKey;
    private $apiUrl = 'https://openrouter.ai/api/v1/chat/completions';

    public function __construct() {
        $this->apiKey = getenv('AI_API_KEY');
    }

    public function generateQuestions($role, $experience, $page = 1) {
        if (!$this->apiKey) {
            error_log("AIService: No API Key found.");
            return ["error" => "No API Key configuration found in backend."];
        }

        $systemPrompt = "You are an expert Senior Technical Recruiter and Engineering Manager hiring for the role of '{$role}'. 
        Your goal is to assess a candidate with '{$experience}' experience using realistic, scenario-based interview questions.
        
        INSTRUCTIONS:
        1. Generate exactly 5 unique, challenging questions.
        2. Focus on real-world scenarios, system design, debugging, or architectural decisions.
        3. Avoid generic textbook definitions (e.g., 'What is polymorphism?').
        4. Return STRICTLY valid JSON.
        
        JSON STRUCTURE:
        {
            \"questions\": [
                {
                    \"id\": \"q-1\",
                    \"type\": \"theory\" | \"coding\",
                    \"question\": \"The actual interview question...\",
                    \"answer\": \"A high-quality, concise model answer (2 sentences max).\",
                    \"code\": \"// Starter code if type is coding, else null\"
                }
            ]
        }";

        $userPrompt = "Generate 5 senior-level questions for page {$page}. ensure they are different from previous sets.";

        $data = [
            "model" => "openai/gpt-4o-mini",
            "messages" => [
                ["role" => "system", "content" => $systemPrompt],
                ["role" => "user", "content" => $userPrompt]
            ],
            "response_format" => ["type" => "json_object"],
            "temperature" => 0.8,
            "max_tokens" => 800
        ];

        $response = $this->makeApiCall($data);

        if (!$response || isset($response['error'])) {
            if (isset($response['error'])) {
                error_log("AIService API Error: " . json_encode($response['error']));
                return ["error" => $response['error']];
            } else {
                error_log("AIService: API returned NULL.");
                return ["error" => "OpenAI API Info: " . json_encode($this->lastDebugInfo)];
            }
        }

        if ($response && isset($response['choices'][0]['message']['content'])) {
            $content = $response['choices'][0]['message']['content'];
            
            // Clean Markdown code blocks if present
            $content = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
            
            $json = json_decode($content, true);
            
            if ($json === null) {
                error_log("AIService: JSON Decode Failed. Raw Content: " . $content);
                return ["error" => "Failed to parse OpenAI response JSON."];
            }

            if (isset($json['questions']) && is_array($json['questions'])) {
                foreach ($json['questions'] as $index => &$q) {
                    $q['id'] = "gen-p{$page}-i{$index}-" . uniqid(); 
                    $q['source'] = 'ai';
                }
                return $json['questions'];
            }
        }

        error_log("AIService: Invalid response structure.");
        return ["error" => "Invalid response structure from OpenAI."];
    
    }

    public function generateExplanation($question, $role) {
        if (!$this->apiKey) {
            return ["content" => "Error: No API Key configured."];
        }

        $systemPrompt = "You are a Principal Engineer mentoring a candidate for the role of {$role}. 
        The candidate was asked: \"{$question}\".
        Provide a comprehensive 'Deep Dive' explanation.
        
        STRUCTURE:
        - Use Markdown.
        - Start with the Core Concept.
        - Explain the 'Why' and 'How' in depth.
        - Provide a 'Pro Tip' or 'Senior Perspective'.
        - Keep it engaging and educational.
        
        OUTPUT:
        Return strictly valid JSON: { \"content\": \"...markdown string...\" }";

        $userPrompt = "Explain this question in depth.";

        $data = [
            "model" => "openai/gpt-4o-mini",
            "messages" => [
                ["role" => "system", "content" => $systemPrompt],
                ["role" => "user", "content" => $userPrompt]
            ],
            "response_format" => ["type" => "json_object"],
            "temperature" => 0.7,
            "max_tokens" => 1000
        ];

        $response = $this->makeApiCall($data);
        
        if (!$response || isset($response['error'])) {
            error_log("AIService Deep Dive Error: " . json_encode($response));
            return ["content" => "Error: OpenAI API Error - " . json_encode($response)];
        }

        if ($response && isset($response['choices'][0]['message']['content'])) {
            $content = $response['choices'][0]['message']['content'];
            
            // Scaled Markdown clean
            $content = preg_replace('/^```json\s*|\s*```$/', '', trim($content));

            $json = json_decode($content, true);
            if ($json && isset($json['content'])) {
                return $json; 
            }
            // Fallback: If JSON decode failed or 'content' missing, return valid content if length > 0
            if (strlen(trim($content)) > 0) {
                return ["content" => $content];
            }
        }

        return ["content" => "Error: Failed to generate explanation (Invalid JSON)."];
    }

    private $lastDebugInfo = [];

    private function makeApiCall($data) {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->apiKey,
            "HTTP-Referer: http://localhost:5173",
            "X-Title: AI Interview Prepper"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        // Timeout & SSL Configuration
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        
        $result = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($error) {
            error_log("AIService Curl Error: " . $error);
            $this->lastDebugInfo = ["curl_error" => $error];
            return null;
        }
        
        if ($info['http_code'] !== 200) {
            error_log("AIService HTTP Error {$info['http_code']}: " . $result);
            $decoded = json_decode($result, true);
            $this->lastDebugInfo = ["http_code" => $info['http_code'], "response" => $result];
            return $decoded ? $decoded : ["error" => ["message" => "HTTP {$info['http_code']}: $result"]];
        }

        $decoded = json_decode($result, true);
        
        if ($decoded === null) {
            $this->lastDebugInfo = ["json_error" => json_last_error_msg(), "raw_response" => $result];
            error_log("AIService JSON Decode Error: " . json_last_error_msg());
            return null;
        }

        return $decoded;
    }

    // ----------------------------------------------------------------
    // FALLBACK MOCK DATA (Kept for Offline Mode / Error Handling)
    // ----------------------------------------------------------------

    private function getMockQuestions($role, $experience, $page) {
        $roleKey = strtolower(str_replace(' ', '_', $role));
        $questions = [];
        $baseId = ($page - 1) * 5;
        
        $topics = [
            'System Architecture', 'Scalability', 'Security', 'Performance Optimization', 
            'Database Design', 'API Design', 'Testing Strategies', 'Deployment Pipelines'
        ];

        for ($i = 0; $i < 5; $i++) {
            $id = $baseId + $i;
            $topic = $topics[$id % count($topics)];
            
            $questions[] = [
                "id" => "mock-{$roleKey}-{$id}",
                "type" => ($id % 2 == 0) ? "coding" : "theory",
                "source" => "offline", // Explicit source flag
                "question" => "{$role} Question about {$topic}.", 
                "answer" => "This is a fallback answer generated because the AI service is unreachable.",
                "code" => "// Fallback code starter\nfunction example() {\n  // TODO\n}"
            ];
        }
        return $questions;
    }

    private function getMockExplanation($question, $role) {
        return [
            "content" => "# Offline Mode\n\nWe could not reach the AI service. \n\n### Why?\n- Check your internet connection.\n- Check API Key configuration.\n\nThis is a placeholder explanation."
        ];
    }
    public function analyzeResume($resumeText, $jobDescription = null) {
        $jdContext = $jobDescription ? "Specific Job Description:\n" . $jobDescription : "Standard Industry Standards for this role.";
        
        $prompt = "You are an expert AI Resume Coach and ATS System. Analyze the following resume text against the provided job context.\\n\\n";
        $prompt .= "RESUME CONTENT:\\n" . substr($resumeText, 0, 5000) . "\\n\\n"; // Truncate to safe limit
        $prompt .= "CONTEXT:\\n" . substr($jdContext, 0, 2000) . "\\n\\n";
        
        $prompt .= "Generate a structured JSON response with the following keys strictly:\\n";
        $prompt .= "- resumeScore (0-100 integer)\\n";
        $prompt .= "- atsFriendly (boolean)\\n";
        $prompt .= "- atsCompatibility (Low/Medium/High)\\n";
        $prompt .= "- keywordMatch (Low/Medium/High)\\n";
        $prompt .= "- missingKeywords (array of strings)\\n";
        $prompt .= "- suggestions (array of strings, improvements for grammar/action verbs)\\n";
        $prompt .= "- optimizedResumeText (string, the full rewritten resume content optimized for the job)\\n\\n";
        $prompt .= "Return ONLY valid JSON.";

        $data = [
            "model" => "openai/gpt-4o-mini",
            "messages" => [
                ["role" => "system", "content" => "You are a helpful AI Resume Assistant. Return pure JSON."],
                ["role" => "user", "content" => $prompt]
            ],
            "response_format" => ["type" => "json_object"],
            "max_tokens" => 2000
        ];

        $response = $this->makeApiCall($data);
        
        if (isset($response['choices'][0]['message']['content'])) {
             $content = $response['choices'][0]['message']['content'];
             $content = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
             return json_decode($content, true);
        }
        return null;
    }
    public function analyzeInterviewAnswer($question, $answer, $role) {
        if (!$this->apiKey) {
            return ["error" => "No API Key configured."];
        }

        $systemPrompt = "You are an AI Interview Evaluator.

STRICT RULES (do not break):

1. Before giving any score or feedback, FIRST check:
   - Is the user answer EMPTY or missing?
   - Is the answer OFF-TOPIC or RELEVANT to the question?

2. If the user answer is EMPTY or mic was used but no answer was provided:
   - Do NOT give a score (set score to 0)
   - Do NOT list strengths or improvements
   - Clearly say: \"The user did not provide an answer for this question.\"
   - Provide a PERFECT SAMPLE ANSWER explaining how this question should be answered.

3. If the user answer is OFF-TOPIC:
   - Give a low score (1-3)
   - Clearly explain why the answer is off-topic
   - Provide a PERFECT SAMPLE ANSWER from scratch (do not reuse user content)

4. If the user answer is RELEVANT:
   - Give an appropriate score (1-10)
   - Clearly list strengths and areas for improvement
   - Provide an IMPROVED / POLISHED VERSION of the user's own answer

5. Always display:
   - User Answer separately
   - AI Feedback separately
   - Sample Response separately

OUTPUT FORMAT:
Return STRICTLY valid JSON with the following structure:
{
    \"is_off_topic\": boolean,
    \"score\": number,
    \"feedback_narrative\": \"string (Professional feedback)\",
    \"strengths\": \"string (e.g. 'Strong use of STAR method. / Good technical depth.')\",
    \"weaknesses\": \"string (Areas to improve)\",
    \"missing_points\": \"string (What key concepts were missed)\",
    \"star_analysis\": \"string (Did they use Situation, Task, Action, Result?)\",
    \"sample_response_text\": \"string (The perfect or polished answer)\",
    \"sample_response_type\": \"refined\" | \"perfect\"
}";

        $userPrompt = "Role: {$role}\nQuestion: \"{$question}\"\nUser Answer: \"{$answer}\"";

        $data = [
            "model" => "openai/gpt-4o-mini",
            "messages" => [
                ["role" => "system", "content" => $systemPrompt],
                ["role" => "user", "content" => $userPrompt]
            ],
            "response_format" => ["type" => "json_object"],
            "temperature" => 0.7,
            "max_tokens" => 1500
        ];

        $response = $this->makeApiCall($data);

        if (!$response || isset($response['error'])) {
            error_log("AIService Interview Analysis Error: " . json_encode($response));
            return ["error" => "AI Service Error"];
        }

        if ($response && isset($response['choices'][0]['message']['content'])) {
            $content = $response['choices'][0]['message']['content'];
            $content = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
            $json = json_decode($content, true);
            if ($json) {
                return $json;
            }
        }

        return ["error" => "Failed to parse AI response"];
    }
}
// End of file
