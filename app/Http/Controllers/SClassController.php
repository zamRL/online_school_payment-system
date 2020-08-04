<?php

namespace App\Http\Controllers;

use App\SClass;
use Illuminate\Http\Request;
use PhpParser\Node\Scalar;

class SClassController extends Controller
{
    private $redirect_to;

    public function __construct()
    {
        $this->redirect_to = 'admin.classes.index';
    }

    public function index()
    {
        $classes = SClass::orderBy('id', 'desc')->get();
        return view('sclasses.index')->with(compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:classes',
        ]);

        $class = SClass::create($request->all());

        if (!$class) {
            return redirect()->route($this->redirect_to)->with('error', 'Class could not be stored. Try again.');
        }
        return redirect()->route($this->redirect_to)->with('success', 'Class successfully stored.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255|unique:classes,name,'.$id,
        ]);

        $class = SClass::findOrFail($id);
        if (!$class) {
            return redirect()->route($this->redirect_to)->with('error', 'Class could not be updated. Try again.');
        }

        $class = $class->update($request->all());
        if (!$class) {
            return redirect()->route($this->redirect_to)->with('error', 'Class could not be updated. Try again.');
        }

        return redirect()->route($this->redirect_to)->with('success', 'Class successfully updated.');
    }

    public function destroy($id)
    {
        $class = SClass::findOrFail($id);

        if (!$class || !$class->delete()) {
            return redirect()->route($this->redirect_to)->with('error', 'Class could not be deleted. Try again.');
        }

        return redirect()->route($this->redirect_to)->with('success', 'Class successfully deleted.');
    }
}
