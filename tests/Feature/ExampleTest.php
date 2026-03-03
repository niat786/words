<?php

test('home page loads the wordle game by default', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertDontSee('id="word-length-select"', false)
        ->assertSee('Play Wordle with Various Number of Letters')
        ->assertSee('id="game-board"', false);
});
