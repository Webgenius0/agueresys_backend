<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\GodsCounter;
use App\Models\IndividualVote;
use App\Models\Vote;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VoteController extends Controller
{

    /**
     * Render the view for the vote reset page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.layouts.reset_vote.index');
    }
    /**
     * Reset all votes for the "BEST GOD FOR THE ROLE" by deleting them
     * from the database. If successful, redirects back with a success message.
     * Logs an error and redirects back with an error message if something goes wrong.
     *
     * @return \Illuminate\Http\RedirectResponse
     */

    public function ResetRoleVotes()
    {
        try {
            $votes = Vote::get();
            // Delete all votes
            $votes->each(function ($vote) {
                $vote->delete();
            });
            return redirect()->back()->with('success', 'BEST GOD FOR THE ROLE Votes reset successfully');
        } catch (Exception $e) {
            Log::error('VoteController::ResetRoleVotes' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function ResetSingleGodVotes()
    {
        try {
            $votes = IndividualVote::get();
            // Delete all votes
            $votes->each(function ($vote) {
                $vote->delete();
            });
            return redirect()->back()->with('success', 'BEST ROLE FOR A SPECIFIC GOD votes reset successfully');
        } catch (Exception $e) {
            Log::error('VoteController::ResetSingleGodVotes' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function ResetCounterVotes()
    {
        try {
            $votes = GodsCounter::get();
            // Delete all votes
            $votes->each(function ($vote) {
                $vote->delete();
            });
            return redirect()->back()->with('success', 'BEST COUNTER PICK FOR THIS GOD votes reset successfully');
        } catch (Exception $e) {
            Log::error('VoteController::ResetCounterVotes' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

}
