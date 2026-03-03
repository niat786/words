<?php

it('shows a word length section with labels from 4 to 11 letters', function (): void {
    $response = $this->get(route('wordle'));

    $response->assertOk();
    $response->assertDontSee('id="word-length-select"', false);
    $response->assertSee('Play Wordle with Various Number of Letters');
    $response->assertSee('Choose a word puzzle with the length of the hidden word from 4 to 11 letters.');
    $response->assertSee('data-word-length-option="4"', false);
    $response->assertSee('data-word-length-option="11"', false);
    $response->assertSee('changeWordLength(4)', false);
    $response->assertSee('changeWordLength(11)', false);
});

it('places backspace before enter on the third keyboard row', function (): void {
    $response = $this->get(route('wordle'));

    $response->assertOk();
    $response->assertSeeInOrder([
        "handleInput('BACKSPACE')",
        "handleInput('ENTER')",
    ], false);
});
