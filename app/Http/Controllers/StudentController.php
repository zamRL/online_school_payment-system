<?php

namespace App\Http\Controllers;

use App\AssignedStudent;
use App\SClass;
use App\Section;
use App\StudentPayment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class StudentController extends Controller
{
    private $redirect_to;
    private $dummy_password;

    public function __construct()
    {
        $this->redirect_to = 'admin.students.index';
        $this->dummy_password = 'sofps_v1_dummy_password_12345';
    }

    public function index()
    {
        $classes = SClass::orderBy('name', 'asc')->get();
        $students = User::where('role', 'student')->orderBy('id', 'desc')->get();
        return view('students.index')->with(compact('classes', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'student_id' => 'required|max:255|unique:users',
        ]);

        $request->merge([
            'password' => Hash::make($this->dummy_password)
        ]);

        $student = User::create($request->except(['section_id']));

        if ($student) {
            if ($request->has('section_id') && !empty($request->input('section_id'))) {
                $assigned_student_data = [
                    'user_id' => $student->id,
                    'section_id' => $request->input('section_id')
                ];

                $assigned_student = AssignedStudent::create($assigned_student_data);
            }

            return redirect()->route($this->redirect_to)->with('success', 'Student successfully created.');
        }

        return redirect()->route($this->redirect_to)->with('error', 'Student could not be created. Try again.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'student_id' => 'required|max:255|unique:users,student_id,'.$id,
        ]);

        $student = User::findOrFail($id);

        if ($student) {
            $student = $student->update($request->all());

            if ($student) {
                return redirect()->route($this->redirect_to)->with('success', 'Student successfully updated.');
            }
        }

        return redirect()->route($this->redirect_to)->with('error', 'Student could not be updated. Try again.');
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);

        if ($student) {
            $assigned_student = AssignedStudent::firstWhere('user_id', $id);
            if ($assigned_student) {
                $assigned_student->delete();
            }

            if ($student->delete()) {
                return redirect()->route($this->redirect_to)->with('success', 'Student successfully deleted.');
            }
        }

        return redirect()->route($this->redirect_to)->with('error', 'Student could not be deleted. Try again.');
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'section_id' => 'required'
        ]);

        $assigned_student = AssignedStudent::firstWhere('user_id', $id);

        if ($assigned_student) {
            $assigned_student->update($request->only(['section_id']));
        } else {
            $request->merge(['user_id' => $id]);
            AssignedStudent::create($request->all());
        }

        return redirect()->route($this->redirect_to)->with('success', 'Student successfully assigned.');
    }

    public function payment(Request $request, $id)
    {
        $request->validate([
            'month' => 'required',
        ]);

        $request->merge(['user_id' => $id]);
        $payment = StudentPayment::create($request->all());

        if (!$payment) {
            return redirect()->route('admin.home')->with('error', 'Student payment could not be updated. Try again.');
        }

        return redirect()->route('admin.home')->with('success', 'Student payment successfully updated.');
    }

    public function invoice(Request $request, $id)
    {
        $student_payment = StudentPayment::findOrFail($id);

        if (!$student_payment) {
            return redirect()->route('admin.home')->with('error', 'No student payment record found.');
        }

        if (!empty($student_payment->payment_invoice_file)) {
            Storage::disk('public')->delete('invoice/' . $student_payment->payment_invoice_file);
        }

        $pdf = new Mpdf();
        $view = view('pdf.invoice', compact('student_payment'));
        $pdf->WriteHTML(utf8_encode($view));

        $file_tracking_code = time() . time();
        $file_name = $file_tracking_code . '.pdf';
        $storage_path = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix();
        $dir_path = $storage_path . 'invoice/';
        $file = $dir_path . $file_name;
        \File::isDirectory($dir_path) or \File::makeDirectory($dir_path, 0775, true, true);
        $pdf->Output($file, \Mpdf\Output\Destination::FILE);

        $exists = Storage::disk('public')->exists('invoice/' . $file_name);
        $file_url = null;
        if ($exists) {
            $student_payment->update([
                'payment_tracking_code' => $file_tracking_code,
                'payment_invoice_file' => $file_name
            ]);

            return Storage::disk('public')->download('invoice/' . $file_name);
        }

        return redirect()->route('admin.home')->with('error', 'Something went wrong. Try again.');
    }
}
