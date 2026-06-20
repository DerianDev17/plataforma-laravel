<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            return $this->expiredSessionResponse($request);
        }

        return parent::render($request, $e);
    }

    private function expiredSessionResponse($request)
    {
        $message = 'La sesion expiro. Recarga la pagina e intentalo nuevamente.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 419);
        }

        $redirect = $request->headers->has('referer')
            ? redirect()->back()
            : redirect()->guest(route('login'));

        return $redirect
            ->withInput($request->except($this->dontFlash))
            ->withErrors(['_token' => $message]);
    }
}
