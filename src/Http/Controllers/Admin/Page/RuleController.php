<?php

namespace Kakhura\CheckRequest\Http\Controllers\Admin\Page;

use Kakhura\CheckRequest\Http\Controllers\Admin\Controller;
use Kakhura\CheckRequest\Models\Rule\Rule;
use Kakhura\CheckRequest\Http\Requests\Rule\Request;
use Kakhura\CheckRequest\Services\Rule\RuleService;

class RuleController extends Controller
{
    public function rules()
    {
        $rules = Rule::first();
        return view('vendor.site-bases.admin.rules.edit', compact('rules'));
    }

    public function storeRules(Request $request, RuleService $ruleService)
    {
        $rules = Rule::first();
        if (!is_null($rules)) {
            $ruleService->update($request->validated(), $rules);
        } else {
            $ruleService->create($request->validated());
        }
        return back()->with(['success' => 'ინფორმაცია წარმატებით შეიცვალა']);
    }
}
