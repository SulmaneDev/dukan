<?php

namespace App\Services\Admin;

use App\Http\Requests\Common\DeletableRequest;
use App\Http\Requests\CreateExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseService
{
    // List all expenses for the current user
    public function index(Request $request)
    {
        $expenses = $request->user()->expense()->with('category')->get();

        return view('pages.admin.expense.index', [
            'expenses' => $expenses,
        ]);
    }

    // Show the create form
    public function create(Request $request)
    {
        $categories = $request->user()->expenseCategory()->get();
        return view('pages.admin.expense.create', [
            'categories' => $categories,
        ]);
    }

    // Store a new expense
    public function store(CreateExpenseRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Handle image upload
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('expenses', 'public'); // stores in storage/app/public/expenses
            $data['media'] = $path;
        }

        Expense::create($data);

        return redirect()->route('admin.expense.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Expense added successfully.",
        ]);
    }


    // Show edit form
    public function edit(Request $request, string $id)
    {
        $expense = $request->user()->expense()->find($id);
        if (!$expense) {
            return redirect()->route('admin.expense.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Expense not found.",
            ]);
        }

        $categories = $request->user()->expenseCategory()->get();
        return view('pages.admin.expense.edit', compact('expense', 'categories'));
    }

    // Update an existing expense
    public function update(UpdateExpenseRequest $request, string $id)
    {
        $expense = $request->user()->expense()->find($id);
        if (!$expense) {
            return redirect()->route('admin.expense.index')->with('alert', [
                'type' => 'warning',
                'title' => 'Warning',
                'message' => "Expense not found.",
            ]);
        }

        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('media')) {
            // Optionally delete old file
            if ($expense->media) {
                Storage::disk('public')->delete($expense->media);
            }

            $file = $request->file('media');
            $path = $file->store('expenses', 'public');
            $data['media'] = $path;
        }

        $expense->update($data);

        return redirect()->route('admin.expense.index')->with('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => "Expense updated successfully.",
        ]);
    }

    // Delete one or multiple expenses
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
                'message' => 'No expenses selected for deletion.',
            ]);
        }

        $deletedCount = $request->user()->expense()->whereIn('id', $ids)->delete();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => "$deletedCount expense(s) deleted successfully.",
        ]);
    }
}
