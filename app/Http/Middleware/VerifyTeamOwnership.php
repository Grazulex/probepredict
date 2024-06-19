<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Controllers\API\V1\BaseController;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTeamOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        //check route to know if route is probe, metrics or rules
        if ('api/v1/probes' === $request->route()->getPrefix()) {
            $probe = $request->route('probes');
        } elseif ('api/v1/metrics' === $request->route()->getPrefix()) {
            $probe = $request->route('probeMetrics')->probe;
        } elseif ('api/v1/rules' === $request->route()->getPrefix()) {
            $probe = $request->route('probeRules')->probe;
        } else {
            $response = new BaseController();
            throw new HttpResponseException($response->sendError(
                error: 'Route not found.',
                status: Response::HTTP_NOT_FOUND,
            ));
        }

        if ($request->user()->currentTeam->id !== $probe->team_id) {
            $response = new BaseController();
            throw new HttpResponseException($response->sendError(
                error: 'You do not own this probe.',
                status: Response::HTTP_FORBIDDEN,
            ));
        }

        return $next($request);
    }
}
