<?php

namespace App\Http\Controllers;

use App\SClass;
use App\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    private $redirect_to;

    public function __construct()
    {
        $this->redirect_to = 'admin.sections.index';
    }

    public function index()
    {
        $classes = SClass::orderBy('name', 'asc')->get();
        $sections = Section::orderBy('id', 'desc')->get();
        return view('sections.index')->with(compact('classes', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'name' => 'required|max:255',
        ]);

        $section = Section::create($request->all());

        if (!$section) {
            return redirect()->route($this->redirect_to)->with('error', 'Section could not be stored. Try again.');
        }
        return redirect()->route($this->redirect_to)->with('success', 'Section successfully stored.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required',
            'name' => 'required|max:255',
        ]);

        $section = Section::findOrFail($id);
        if (!$section) {
            return redirect()->route($this->redirect_to)->with('error', 'Section could not be updated. Try again.');
        }

        $section = $section->update($request->all());
        if (!$section) {
            return redirect()->route($this->redirect_to)->with('error', 'Section could not be updated. Try again.');
        }

        return redirect()->route($this->redirect_to)->with('success', 'Section successfully updated.');
    }

    public function destroy($id)
    {
        $section = Section::findOrFail($id);

        if (!$section || !$section->delete()) {
            return redirect()->route($this->redirect_to)->with('error', 'Section could not be deleted. Try again.');
        }

        return redirect()->route($this->redirect_to)->with('success', 'Section successfully deleted.');
    }
}
