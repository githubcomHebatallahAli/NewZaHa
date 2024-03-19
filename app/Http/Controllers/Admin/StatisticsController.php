<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Project;
use App\Models\UserProject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    public function showStatistics()
    {
        $this->authorize('manage_users');
        $projectsCount = Project::count();
        $clientsCount = Client::count();
        $commentsCount = Comment::count();
        $teamsCount = Team::count();
        $salesCount = UserProject::sum('numberSales');
        $statistics = [
            'Projects_count' => $projectsCount,
            'Clients_count' => $clientsCount,
            'Comments_count' => $commentsCount,
            'Teams_count' => $teamsCount,
            'NumberSales_count' => $salesCount,

        ];

        return response()->json($statistics);
    }
}
