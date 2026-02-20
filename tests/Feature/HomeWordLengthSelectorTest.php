<?php

it('shows a word length dropdown with 4 to 11 options and 5 as default', function (): void {
    $response = $this->get(route('wordle'));

    $response->assertOk();
    $response->assertSee('id="word-length-select"', false);
    $response->assertSee('<option value="4">4 Letter Words</option>', false);
    $response->assertSee('<option value="5" selected>5 Letter Words</option>', false);
    $response->assertSee('<option value="11">11 Letter Words</option>', false);
    $response->assertSee('changeWordLength(this.value)', false);
});

it('places backspace before enter on the third keyboard row', function (): void {
    $response = $this->get(route('wordle'));

    $response->assertOk();
    $response->assertSeeInOrder([
        "handleInput('BACKSPACE')",
        "handleInput('ENTER')",
    ], false);
});
