<?php

namespace Different\DifferentCore\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Prologue\Alerts\Facades\Alert;

class ChangeAccountController extends Controller
{
    public function changeAccount(Request $request)
    {
        $validated = $request->validate([
            'account_id' => [
                'required',
                'integer',
                Rule::exists('accounts', 'id'),
                Rule::in(
                    backpack_user()->selectable_accounts->pluck('id')->toArray()
                )
            ],
        ]);

        session()->put('account_id', $validated['account_id']);

        return back();
    }
}
