<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExpenseCategoryService
{
    // List all categories
    public function index(Request $request)
    {
        $categories = $request->user()->expenseCategory()->get();

        return view('pages.admin.expense_category.index', [
            'categories' => $categories,
        ]);
    }

    // Show create form
    public function create()
    {
        return view('pages.admin.expense_category.create');
    }

    // Store new category
    public function store(CreateExpenseCategoryRequest $request)
    {
        $data = $request->validated();

        $category = new ExpenseCategory();
        $category->name = $data['name'];
        $category->description = $data['description'] ?? null;
        $category->code = Str::random(6);
        $category->user_id = Auth::id();
        $category->save();

        return redirect()->route('admin.expense_category.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Expense category '{$category->name}' created successfully.",
        ]);
    }

    // Show edit form
    public function edit(Request $request, string $id)
    {
        $category = $request->user()->expenseCategory()->find($id);

        if (!$category) {
            return redirect()->route('admin.expense_category.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Expense category not found.",
            ]);
        }

        return view('pages.admin.expense_category.edit', compact('category'));
    }

    // Update category
    public function update(UpdateExpenseCategoryRequest $request, string $id)
    {
        $data = $request->validated();
        $category = $request->user()->expenseCategory()->find($id);

        if (!$category) {
            return redirect()->route('admin.expense_category.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Expense category not found.",
            ]);
        }

        $category->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? $category->description,
        ]);

        return redirect()->route('admin.expense_category.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Expense category '{$category->name}' updated successfully.",
        ]);
    }

    // Delete category/categories
    public function destroy(DeletableRequest $request)
    {
        $ids = $request->input('deletable_ids');

        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => 'No categories selected for deletion.',
            ]);
        }

        $deletedCount = $request->user()->expenseCategory()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount category(s) deleted successfully.",
        ]);
    }
}
