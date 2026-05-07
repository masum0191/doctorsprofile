<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIContentController extends Controller
{
    public function generateContent(Request $request)
    {
        $request->validate([
            'headline' => 'required|string|min:5',
            'doctor_name' => 'sometimes|string',
            'specialty' => 'sometimes|string'
        ]);

        try {
            $headline = $request->headline;
            $doctorName = $request->doctor_name ?? 'Dr. Smith';
            $specialty = $request->specialty ?? 'medical professional';

            // Generate content using AI (you can integrate with OpenAI, Gemini, etc.)
            $content = $this->generateAIContent($headline, $doctorName, $specialty);

            return response()->json([
                'success' => true,
                'content' => $content
            ]);

        } catch (\Exception $e) {
            Log::error('AI Content Generation Failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate content suggestions'
            ], 500);
        }
    }

    private function generateAIContent($headline, $doctorName, $specialty)
    {
        // Option 1: Integration with OpenAI GPT
        // return $this->generateWithOpenAI($headline, $doctorName, $specialty);

        // Option 2: Integration with Google Gemini
        // return $this->generateWithGemini($headline, $doctorName, $specialty);

        // Option 3: Fallback to rule-based generation
        return $this->generateRuleBasedContent($headline, $doctorName, $specialty);
    }

    /**
     * Rule-based content generation (fallback)
     */
    private function generateRuleBasedContent($headline, $doctorName, $specialty)
    {
        // Analyze headline keywords to generate relevant content
        $keywords = $this->extractKeywords($headline);

        $content = [
            'subheadline' => $this->generateSubheadline($headline, $keywords),
            'tagline' => $this->generateTagline($keywords),
            'about_short' => $this->generateShortAbout($doctorName, $specialty, $keywords),
            'about_long' => $this->generateLongAbout($doctorName, $specialty, $keywords),
        ];

        return $content;
    }

    private function extractKeywords($text)
    {
        $commonWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by'];
        $words = str_word_count(strtolower($text), 1);
        $keywords = array_diff($words, $commonWords);

        return array_values($keywords);
    }

    private function generateSubheadline($headline, $keywords)
    {
        $templates = [
            "Comprehensive {keyword} care for your wellbeing",
            "Expert {keyword} services with compassionate approach",
            "Your trusted partner in {keyword} health journey",
            "Advanced {keyword} treatments for better health outcomes",
            "Personalized {keyword} care for optimal results"
        ];

        $keyword = $keywords[0] ?? 'health';
        $template = $templates[array_rand($templates)];

        return str_replace('{keyword}', $keyword, $template);
    }

    private function generateTagline($keywords)
    {
        $taglines = [
            "Excellence in Patient Care",
            "Compassionate Medical Expertise",
            "Your Health, Our Priority",
            "Advanced Care, Personal Touch",
            "Trusted Medical Professionals",
            "Innovative Healthcare Solutions"
        ];

        return $taglines[array_rand($taglines)];
    }

    private function generateShortAbout($doctorName, $specialty, $keywords)
    {
        $keyword = $keywords[0] ?? 'healthcare';

        $templates = [
            "{$doctorName} provides comprehensive {$keyword} services with a patient-centered approach. With years of experience in {$specialty}, we're committed to delivering exceptional medical care.",
            "Experience expert {$keyword} care with {$doctorName}. Our focus on {$specialty} ensures you receive personalized treatment and compassionate support.",
            "{$doctorName} specializes in advanced {$keyword} treatments. Our dedication to {$specialty} means you get the highest quality medical attention."
        ];

        return $templates[array_rand($templates)];
    }

    private function generateLongAbout($doctorName, $specialty, $keywords)
    {
        $keyword = $keywords[0] ?? 'healthcare';

        $content = "{$doctorName} is a dedicated {$specialty} professional with extensive experience in {$keyword}. ";

        $paragraphs = [
            "Our practice is built on the foundation of providing comprehensive medical care that addresses both immediate health concerns and long-term wellness goals.",
            "We believe in a holistic approach to healthcare, combining advanced medical treatments with personalized attention to ensure each patient receives the care they deserve.",
            "With a focus on preventive medicine and patient education, we empower individuals to take control of their health journey while providing expert guidance every step of the way.",
            "Our commitment to excellence means we stay current with the latest medical advancements and treatment methodologies to offer the most effective care possible."
        ];

        $selectedParagraphs = array_rand($paragraphs, 2);
        foreach ($selectedParagraphs as $index) {
            $content .= $paragraphs[$index] . " ";
        }

        return trim($content);
    }

    /**
     * Integration with OpenAI GPT (Example)
     */
    private function generateWithOpenAI($headline, $doctorName, $specialty)
    {
        $apiKey = config('services.openai.api_key');

        if (!$apiKey) {
            return $this->generateRuleBasedContent($headline, $doctorName, $specialty);
        }

        $prompt = "As a medical content writer, generate compelling content for a doctor's profile based on this headline: \"{$headline}\"

        Doctor: {$doctorName}
        Specialty: {$specialty}

        Please provide:
        1. A compelling subheadline (max 10 words)
        2. A professional tagline (max 5 words)
        3. A short about section for hero section (2-3 sentences)
        4. A detailed about section (4-5 sentences)

        Format as JSON:
        {
            \"subheadline\": \"...\",
            \"tagline\": \"...\",
            \"about_short\": \"...\",
            \"about_long\": \"...\"
        }";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7
            ]);

            $content = json_decode($response->body(), true);

            if (isset($content['choices'][0]['message']['content'])) {
                return json_decode($content['choices'][0]['message']['content'], true);
            }

        } catch (\Exception $e) {
            Log::error('OpenAI API Error: ' . $e->getMessage());
        }

        return $this->generateRuleBasedContent($headline, $doctorName, $specialty);
    }
}
