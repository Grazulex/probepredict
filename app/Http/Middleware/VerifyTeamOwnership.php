<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Controllers\API\BaseController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTeamOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        //check route to know if route is probe, metrics or rules
        if ('api/probes' === $request->route()->getPrefix()) {
            $probe = $request->route('probe');
        } elseif ('api/metrics' === $request->route()->getPrefix()) {
            $probe = $request->route('probeMetrics')->probe;
        } elseif ('api/rules' === $request->route()->getPrefix()) {
            $probe = $request->route('probeRules')->probe;
        } else {
            $response = new BaseController();
            return $response->sendError(
                error: 'Route not found.',
                status: Response::HTTP_NOT_FOUND,
            );
        }

        if ($request->user()->currentTeam->id !== $probe->team_id) {
            $response = new BaseController();
            return $response->sendError(
                error: 'You do not own this probe.',
                status: Response::HTTP_FORBIDDEN,
            );
        }

        return $next($request);
    }
}
