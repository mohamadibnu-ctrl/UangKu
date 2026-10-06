<?php

class AIService {
    
    private $apiKey = AI_API_KEY;

    public function askAI($userQuestion, $financialData)
    {
        // 1. Validasi Mode Development / Mock Mode
        if (empty($this->apiKey) || $this->apiKey === 'your_api_key_here') {
            return $this->mockResponse($userQuestion, $financialData);
        }

        // 2. Real API Call (Menggunakan NVIDIA API / OpenAI-Compatible Format)
        $url = 'https://integrate.api.nvidia.com/v1/chat/completions';

        // Persiapkan Prompt Terstruktur (System Instruction)
        $systemPrompt = "Anda adalah AI Assistant Keuangan Pribadi bernama 'UangKu AI'.\n";
        $systemPrompt .= "Tugas Anda adalah menjawab pertanyaan user terkait data keuangannya.\n";
        $systemPrompt .= "Data keuangan user (dalam format JSON) adalah sebagai berikut:\n\n";
        $systemPrompt .= json_encode($financialData, JSON_PRETTY_PRINT) . "\n\n";
        $systemPrompt .= "Aturan penting:\n";
        $systemPrompt .= "1. Jangan pernah mengarang data. Jika data tidak ada, katakan Anda tidak memiliki data tersebut.\n";
        $systemPrompt .= "2. Jika ditanya selain urusan keuangan, tolak dengan sopan.\n";
        $systemPrompt .= "3. Berikan jawaban yang singkat, ramah, dan mudah dimengerti (gunakan bahasa Indonesia).\n";

        $data = [
            "model" => "meta/llama-3.1-8b-instruct", // Model default di Nvidia API
            "messages" => [
                [
                    "role" => "system",
                    "content" => $systemPrompt
                ],
                [
                    "role" => "user",
                    "content" => $userQuestion
                ]
            ],
            "max_tokens" => 1024,
            "temperature" => 0.5
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            return "Maaf, terjadi kesalahan koneksi jaringan: " . curl_error($ch);
        }
        
        curl_close($ch);

        $responseData = json_decode($response, true);

        // Parsing response format OpenAI
        if (isset($responseData['choices'][0]['message']['content'])) {
            return $responseData['choices'][0]['message']['content'];
        }

        if (isset($responseData['error'])) {
            return "Error dari AI Provider: " . $responseData['error']['message'];
        }

        return "Maaf, saya tidak dapat memproses permintaan Anda saat ini.";
    }

    private function mockResponse($question, $data)
    {
        $question = strtolower($question);
        
        if (strpos($question, 'bulan ini') !== false && strpos($question, 'pengeluaran') !== false) {
            $total = number_format($data['current_month']['total_expense'], 0, ',', '.');
            return "*(Mock Mode)* Total pengeluaran Anda bulan ini adalah Rp " . $total . " dari " . $data['current_month']['transaction_count'] . " transaksi.";
        }
        
        if (strpos($question, 'kategori') !== false && strpos($question, 'paling banyak') !== false) {
            if (!empty($data['current_month']['categories'])) {
                $highest = $data['current_month']['categories'][0];
                return "*(Mock Mode)* Kategori dengan pengeluaran terbesar Anda bulan ini adalah **" . $highest['name'] . "** sebesar Rp " . number_format($highest['total'], 0, ',', '.') . ".";
            } else {
                return "*(Mock Mode)* Belum ada data pengeluaran bulan ini.";
            }
        }
        
        return "*(Mock Mode)* Halo! Sistem AI sedang dalam mode development karena API Key belum dikonfigurasi. Berikut adalah ringkasan mentah data Anda bulan ini: Total Rp " . number_format($data['current_month']['total_expense'], 0, ',', '.') . ". Ajukan pertanyaan spesifik seperti 'Berapa pengeluaran saya bulan ini?'.";
    }
}
