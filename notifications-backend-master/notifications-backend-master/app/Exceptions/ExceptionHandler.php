<?php


namespace App\Exceptions;

use App\Jobs\JobSendNotificationsTelegram;
use Exception;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHandler extends Handler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        JobSendNotificationsTelegram::dispatch($this->_getTextForNotify($exception))->onQueue(env('TUBE_TELEGRAM'))->delay(rand(5, 15));
        return parent::render($request, $exception);
    }

    /**
     * @param Exception $exception
     * @return string
     */
    protected function _getTextForNotify(Exception $exception): string
    {
        $text = date('Y-m-d H:i:s') . ' Error (code: ' . $exception->getCode() . '): ' . $exception->getMessage() . ' in ' . $exception->getFile() . '(' . $exception->getLine() . ")\n\n";

        $file = 'Unknown';
        $line = 'Unknown';

        if (isset($exception->getTrace()[0])) {
            if (isset($exception->getTrace()[0]['file'])) {
                $file = $exception->getTrace()[0]['file'];
            }
        }

        if (isset($exception->getTrace()[0])) {
            if (isset($exception->getTrace()[0]['line'])) {
                $line = $exception->getTrace()[0]['line'];
            }
        }


        $text .= sprintf(
            "Uncaught exception '%s' with message '%s' in %s:%d\n\n",
            get_class($exception),
            $exception->getMessage(),
            $file,
            $line
        );

        foreach ($exception->getTrace() as $k => $t) {
            $text .= sprintf(
                "Trace ($k) : '%s' on line '%s' function %s\n",
                (isset($t['file'])) ? preg_replace('/[*`]/', '', $t['file']) : 'Unknown',
                (isset($t['line'])) ? preg_replace('/[*`]/', '', $t['line']) : 'Unknown',
                (isset($t['function'])) ? preg_replace('/[*`]/', '', $t['function']) : 'Unknown'
            );

            if ($k > 3) {
                break;
            }
        }

        return $text;
    }
}