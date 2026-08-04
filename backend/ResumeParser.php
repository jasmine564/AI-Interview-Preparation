<?php

class ResumeParser {

    /**
     * =========================
     * MAIN ENTRY
     * =========================
     */
    public function parse($filePath) {

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($extension === 'docx') {
            $text = $this->parseDocx($filePath);
        } elseif ($extension === 'pdf') {
            $text = $this->parsePdf($filePath);
        } else {
            throw new Exception("Unsupported file format. Only PDF or DOCX resumes are allowed.");
        }

        // 🔴 RESUME VALIDATION (IMPORTANT)
        if (!$this->isResumeContent($text)) {
            throw new Exception(
                "Invalid document uploaded. Please upload a valid resume only."
            );
        }

        return $text;
    }

    /**
     * =========================
     * DOCX PARSER
     * =========================
     */
    private function parseDocx($filePath) {

        if (!class_exists('ZipArchive')) {
            throw new Exception(
                "PHP Zip extension missing. Enable extension=zip in php.ini and restart Apache."
            );
        }

        $zip = new ZipArchive();
        $content = "";

        if ($zip->open($filePath) === TRUE) {
            $index = $zip->locateName("word/document.xml");
            if ($index !== false) {
                $data = $zip->getFromIndex($index);
                $dom = new DOMDocument();
                $dom->loadXML($data, LIBXML_NOERROR | LIBXML_NOWARNING);
                $content = strip_tags($dom->saveXML());
            }
            $zip->close();
        }

        return trim($content);
    }

    /**
     * =========================
     * PDF PARSER (POPPLER)
     * =========================
     */
    private function parsePdf($filePath) {

        // 🔴 CHANGE THIS PATH ONLY IF USERNAME IS DIFFERENT
        $pdftotextPath = "C:\\Users\\jasmi\\Downloads\\Release-25.12.0-0\\poppler-25.12.0\\Library\\bin\\pdftotext.exe";

        // DEBUG: confirm function execution
        file_put_contents(__DIR__ . "/debug_pdf_step1.txt", "parsePdf() called");

        if (!file_exists($pdftotextPath)) {
            throw new Exception("pdftotext.exe not found at configured path.");
        }

        $command = '"' . $pdftotextPath . '" ' . escapeshellarg($filePath) . ' - 2>&1';
        $output = shell_exec($command);

        // DEBUG: save extracted text
        file_put_contents(__DIR__ . "/debug_extracted_text.txt", $output);

        if (!$output || strlen(trim($output)) < 30) {
            throw new Exception(
                "PDF text extraction failed. Upload a text-based (selectable) PDF."
            );
        }

        return trim($output);
    }

    /**
     * =========================
     * RESUME CONTENT VALIDATOR
     * =========================
     */
    private function isResumeContent($text) {

        $resumeKeywords = [
            'experience', 'education', 'skills', 'projects',
            'internship', 'certification', 'objective',
            'summary', 'profile', 'achievements'
        ];

        $nonResumeKeywords = [
            'abstract', 'this paper', 'proposed system',
            'methodology', 'experimental results',
            'implementation', 'algorithm', 'research'
        ];

        $textLower = strtolower($text);

        $resumeScore = 0;
        foreach ($resumeKeywords as $word) {
            if (strpos($textLower, $word) !== false) {
                $resumeScore++;
            }
        }

        $nonResumeScore = 0;
        foreach ($nonResumeKeywords as $word) {
            if (strpos($textLower, $word) !== false) {
                $nonResumeScore++;
            }
        }

        // Resume must clearly dominate
        return ($resumeScore >= 3 && $resumeScore > $nonResumeScore);
    }
}
?>
