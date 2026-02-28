<?php

use App\Http\Middleware\SetFilamentLocale;
use Illuminate\Http\Request;

it('forces english locale for filament admin middleware', function () {
    app()->setLocale('es');

    $middleware = new SetFilamentLocale;
    $request = Request::create('/admin', 'GET');

    $middleware->handle($request, function (Request $request) {
        return response('ok');
    });

    expect(app()->getLocale())->toBe('en');
});
