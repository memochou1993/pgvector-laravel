<?php

use App\Models\Embedding;
use Illuminate\Support\Facades\Artisan;
use OpenAI\Laravel\Facades\OpenAI;
use Pgvector\Laravel\Vector;

Artisan::command('insert', function() {
    $sayings = [
        'Felines say meow',
        'Canines say woof',
        'Birds say tweet',
        'Humans say hello',
    ];

    $result = OpenAI::embeddings()->create([
        'model' => 'text-embedding-ada-002',
        'input' => $sayings
    ]);

    foreach ($sayings as $key=>$saying) {
        Embedding::query()->create([
            'embedding' => $result->embeddings[$key]->embedding,
            'metadata' => [
                'saying' => $saying,
            ]
        ]);
    }
});

Artisan::command('search', function() {
    $result = OpenAI::embeddings()->create([
        'model' => 'text-embedding-ada-002',
        'input' => 'What do dogs say?',
    ]);

    $embedding = new Vector($result->embeddings[0]->embedding);

    $this->table(
        ['saying'],
        Embedding::query()
            ->orderByRaw('embedding <-> ?', [$embedding])
            ->take(2)
            ->pluck('metadata')
    );
});
