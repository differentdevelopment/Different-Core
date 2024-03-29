<?php

namespace Different\DifferentCore\app\Http\Controllers;

use Different\DifferentCore\app\Models\LoginToken;
use Different\DifferentCore\app\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MagicLinkController extends Controller
{
    /**
     * @param  Request  $request
     */
    public function getLogin()
    {
        return view('different-core::auth.magic-link', [
            'title' => __('different-core::magic-link.login'),
            'username' => 'email',
        ]);
    }

    /**
     * @param  Request  $request
     */
    public function postLogin(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);
        User::whereEmail($data['email'])->first()->sendLoginLink();
        session()->flash('success', true);

        return redirect()->back();
    }

    public function verifyLogin(Request $request, $token)
    {
        $token = LoginToken::whereToken(hash('sha256', $token))->firstOrFail();
        abort_unless($request->hasValidSignature() && $token->isValid(), 401);

        $token->consume();
        backpack_auth()->login($token->user);

        return redirect(backpack_url('/'));
    }
}
