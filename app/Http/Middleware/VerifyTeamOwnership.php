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
        $probe = $request->route('probe');
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
