<?php

namespace App\Exceptions;

use App\Jobs\JobSendNotificationsTelegram;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Pheanstalk\Exception\ServerException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     *
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if (env('NOTIFY_ERROR')
            && !$exception instanceof RouteCollection
            && !$exception instanceof ServerException
            && !$exception instanceof CommandNotFoundException
            && !$exception instanceof AuthenticationException) {
            JobSendNotificationsTelegram::dispatch($this->_getTextForNotify($exception))->onQueue(env('TUBE_TELEGRAM'))->delay(rand(5, 10));
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return Response
     *
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof UnauthorizedException) {
            return response()->json([
                'message' => 'You do not have the required authorization.',
                'code' => $exception->getCode(),
            ], Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'code' => $exception->getCode(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Not found page.',
                'code' => $exception->getCode(),
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof ServerException) {
            return response()->json([
                'message' => 'Server Exception.',
                'code' => $exception->getCode(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof Exception) {
            $data = [
                'message' => 'Server error.',
                'exception' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ];

            JobSendNotificationsTelegram::dispatch($this->_getTextForNotify($exception))->onQueue(env('TUBE_TELEGRAM'))->delay(rand(5, 15));

            return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof ValidationException) {
            JobSendNotificationsTelegram::dispatch($this->_getTextForNotify($exception))->onQueue(env('TUBE_TELEGRAM'))->delay(rand(5, 15));

            return response()->json([
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return parent::render($request, $exception);
    }
}
