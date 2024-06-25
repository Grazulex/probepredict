<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Traits\JsonResponses;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTeamOwnership
{
    use JsonResponses;
    public function handle(Request $request, Closure $next): Response
    {
        //check route to know if route is probe, metrics or rules
        if ('api/v1/probes' === $request->route()->getPrefix()) {
            $probe = $request->route('probe');
        } elseif ('api/v1/metrics' === $request->route()->getPrefix()) {
            $probe = $request->route('probeMetric')->probe;
        } elseif ('api/v1/rules' === $request->route()->getPrefix()) {
            $probe = $request->route('probeRule')->probe;
        } else {
            throw new HttpResponseException($this->errorResponse(
                message: 'Route not found.',
                code: Response::HTTP_NOT_FOUND,
            ));
        }

        if ($request->user()->currentTeam->id !== $probe->team_id) {
            throw new HttpResponseException($this->errorResponse(
                message: 'You do not own this probe.',
                code: Response::HTTP_FORBIDDEN,
            ));
        }

        return $next($request);
    }
}
