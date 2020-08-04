<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            font-size: 14px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        /*.invoice-box table tr td:nth-child(2) {*/
        /*text-align: right;*/
        /*}*/
        .firstTable tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 14px;
            line-height: 24px;
            color: #333;
        }
        .invoice-box table tr.top table td.title b {
            font-size: 18px;
            line-height: 28px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total a {
            background: #d5d5d5;
            font-size: 14px;
            border: 1px solid #666;
            padding: 6px 10px;
            cursor: pointer;
            text-decoration: none;
            color: #444;
        }

        .invoice-box table tr.total td:nth-child(2) {
            font-weight: bold;
        }

        .invoice-box table .copyright td{
            padding-top: 20px;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
</head>

<body>
<div class="invoice-box">
    <table class="firstTable" cellpadding="0" cellspacing="0">

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <b>INVOICE</b>
                        </td>

                        <td>
                            <b>{{ $student_payment->payment_tracking_code }}</b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <b>{{ $student_payment->user->name }}</b><br>
                            Email: {{ $student_payment->user->email }}<br>
                            Student ID: {{ $student_payment->user->student_id }}<br>
                            Class: {{ $student_payment->user->assigned_student->section->class->name }}<br>
                            Section: {{ $student_payment->user->assigned_student->section->name }}<br>

                        </td>
                        <td>
                            Paid Month: <b>{{ ucwords($student_payment->month) }},
                                {{ date('Y', strtotime($student_payment->updated_at)) }}</b><br>
                            Date of Payment: <b>{{ date('F j, Y', strtotime($student_payment->updated_at)) }}</b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0">
        <tr class="heading">
            <td>
                # Description
            </td>

            <td style="text-align: right;">
                Status
            </td>
        </tr>
        <tr class="item">
            <td>
                1. Payment Description
            </td>

            <td style="text-align: right;">
                <span style="@if($student_payment->status == 'paid')background: #155d10; @elseif($student_payment->status == 'unpaid') background: #ff0000; @else background: #0BCBB6; @endif font-size: 14px;  padding: 6px 10px;
                    text-decoration: none;
                    color: #fff;">
                                    @if($student_payment->status == 'paid')
                        {{ 'Paid' }}
                    @elseif($student_payment->status == 'unpaid')
                        {{ 'Unpaid' }}
                    @else
                        {{ 'Unpaid' }}
                    @endif
                </span>
            </td>
        </tr>
    </table>


</div>
</body>
</html>
