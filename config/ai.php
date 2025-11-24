<?php

return [
    'prompt' => [
        'general_forecasts' => '۵ سوال پیش‌بینی درباره قیمت طلا در سال ۲۰۲۵ تولید کن',
    ],
    'openai_instructions' => [
        'general' => 'You are a Forecasting Question Generator Assistant.
            Your purpose is to create prediction questions in Persian (Farsi) that are about future events that have not happened yet, and which can later be resolved objectively.
            When generating questions:
            Language: all output must be in Persian (Farsi).
            Nature: Each question should describe a predictive event (e.g., politics, sports, economics, technology, etc.).
            No correct answers — these are forecasts, not knowledge questions.
            Options: Provide a variable number of answer options (minimum 2, maximum 10). Each option should represent a possible outcome.
            Description:
            Must clearly describe the prediction, its context, and how it will be resolved.
            Must remove any ambiguity about what exactly is being predicted.
            Must include the resolution rule (how we will determine which option was correct when the event occurs).
            Example: “در صورت برگزاری بازی فوتبال تیم الف و ب در تاریخ ۲۴ مهر، اگر تیم الف برنده شود طبق نتایج رسمی فیفا گزینه بله صحیح محسوب می‌شود. در صورت لغو یا تغییر تاریخ، سوال باطل می‌شود.”
            Metadata:
            category (e.g. سیاست، اقتصاد، ورزش، فناوری، جامعه، محیط‌زیست)
            topic — one short label summarizing the main subject (e.g. “انتخابات روسیه ۲۰۲۵”)
            tags — an array of keywords (e.g. ["روسیه", "جنگ", "تحریم"])
            You may also include any other useful metadata that helps with search or classification.
            Output format:
            Respond only in valid JSON — no text outside the JSON.
            Use the following schema exactly.',
    ],
    'openai_schema' => [
        "name" => "forecast_question",
        "schema" => [
            "type" => "object",
            "properties" => [
                "questions" => [
                    "type" => "array",
                    "items" => [
                        "type" => "object",
                        "properties" => [
                            "id"          => ["type" => "string"],
                            "language"    => ["type" => "string", "enum" => ["fa"]],
                            "category"    => ["type" => "string"],
                            "topic"       => ["type" => "string"],
                            "tags"        => ["type" => "array", "items" => ["type" => "string"]],
                            "difficulty"  => ["type" => "string", "enum" => ["easy", "medium", "hard"]],
                            "question"    => ["type" => "string"],
                            "options"     => ["type" => "array", "items" => ["type" => "string"]],
                            "description" => ["type" => "string"],
                            "created_at"  => ["type" => "string", "format" => "date-time"]
                        ],
                        "required" => [
                            "id","language","category","topic","tags",
                            "difficulty","question","options","description","created_at"
                        ],
                        "additionalProperties" => false
                    ]
                ]
            ],
            "required" => ["questions"],
            "additionalProperties" => false
        ],
        "strict" => true
    ]
];
