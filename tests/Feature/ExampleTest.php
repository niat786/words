<?php

test('home page loads the wordle game by default', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('id="word-length-select"', false)
        ->assertSee('Wordly Game');
});
