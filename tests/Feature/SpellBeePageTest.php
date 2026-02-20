<?php

it('loads spell bee page with playable controls', function (): void {
    $response = $this->get(route('spell-bee'));

    $response->assertOk();
    $response->assertSee('SpellBee');
    $response->assertSee('id="spellbee-hive"', false);
    $response->assertSee('id="spellbee-input"', false);
    $response->assertSee('id="spellbee-submit"', false);
    $response->assertSee('id="spellbee-delete"', false);
    $response->assertSee('id="spellbee-shuffle"', false);
    $response->assertSee('id="hint_button"', false);
    $response->assertSee('id="spellbee-ranking-trigger"', false);
    $response->assertSee('id="spellbee-ranking-modal"', false);
    $response->assertSee('id="spellbee-hints-modal"', false);
    $response->assertSee('id="spellbee-hints-table-body"', false);
    $response->assertSee('id="nav-theme-toggle"', false);
    $response->assertDontSee('id="spellbee-theme-toggle"', false);
    $response->assertSee('window.toggleTheme = window.toggleSpellBeeTheme;', false);
    $response->assertSee('startGame();', false);
});
