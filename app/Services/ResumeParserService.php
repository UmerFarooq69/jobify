<?php
namespace App\Services;

use Smalot\PdfParser\Parser;

class ResumeParserService
{
    public function __construct(protected LlmService $llm) {}

    public function parse(string $filePath): array
    {
        $text = $this->extractText($filePath);
        if (empty($text)) return [];

        set_time_limit(120);
        $result = $this->llm->chatJson([
            ['role' => 'system', 'content' => 'You are a resume parser. Extract structured data from the resume text and return valid JSON only with these fields: name, email, phone, location, gender, headline, summary, skills (array of strings), experience (array of {title, company, start_date, end_date, description}), education (array of {degree, institution, year}), certifications (array of strings).'],
            ['role' => 'user', 'content' => "Parse this resume:\n\n{$text}"],
        ]);

        return $result ?? [];
    }

    private function extractText(string $filePath): string
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            try {
                $parser = new Parser();
                $pdf    = $parser->parseFile($filePath);
                return $pdf->getText();
            } catch (\Exception $e) {
                return '';
            }
        }
        // For non-PDF, try reading as text
        return is_readable($filePath) ? file_get_contents($filePath) : '';
    }
}
