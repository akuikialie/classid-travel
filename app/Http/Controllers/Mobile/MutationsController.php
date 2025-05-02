<?php

namespace App\Http\Controllers\Mobile;

use App\Enums\MutationInfo;
use App\Enums\MutationType;
use App\Models\Mutation\Mutation;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use App\Queries\MutationQuery;
use Dentro\Yalr\Attributes\Get;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Prefix;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[Prefix('mutations')]
#[Name('mutations', false, true)]
class MutationsController extends Controller
{

    /**
     * @param Request $request
     * @param VirtualAccount $virtualAccount
     * @return View
     */
    #[Get('{virtualAccount}', name: 'index')]
    public function index(Request $request, VirtualAccount $virtualAccount): View
    {
        $request->mergeIfMissing([
            'mutable_id' => $virtualAccount->hash,
            'mutable_type' => VirtualAccount::class,
            'latest' => true,
        ]);

        $mutations = MutationQuery::filterColumn()
            ->orderColumn()
            ->getAllDataPaginated();

        return view('pages.mobile.transaction.index', [
            'mutations' => $mutations,
        ]);
    }

    /**
     * @param Request $request
     * @param Mutation $mutation
     * @return View
     */
    #[Get('{mutation}/detail', name: 'show')]
    public function show(Request $request, Mutation $mutation): View
    {
        if ($mutation->info == MutationInfo::MOVE->value && $mutation->type == MutationType::OUT->value){
            $mutationFrom = $mutation;
            $mutationTo = $mutation->transaction->mutations->where('type', '!=', $mutation->type)->first();
        }else{
            $mutationTo = $mutation;
            $mutationFrom = $mutation->transaction->mutations->where('type', '!=', $mutation->type)->first();
        }
        return view('pages.mobile.transaction.show', [
            'mutation' => $mutation,
            'mutationFrom' => $mutationFrom,
            'mutationTo' => $mutationTo,
        ]);
    }

}
