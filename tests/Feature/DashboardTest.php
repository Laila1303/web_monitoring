<?php

test('dashboard renders successfully and contains main components', function () {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('VORTEX')
        ->assertSee('GLOBAL LOGISTICS CONTROL')
        ->assertSee('id="map"', false)
        ->assertSee('Active Shipments Log')
        // Verify mock cargo IDs are output or represented in script/markup
        ->assertSee('CRG-4098-CN')
        ->assertSee('CRG-9021-US')
        ->assertSee('CRG-3051-BR');
});
