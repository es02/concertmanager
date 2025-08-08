<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Email;
use App\Models\Email_Account;
use App\Models\Email_Template;
use App\Models\Email_Thread;

class EmailController extends Controller
{
    public function sendEmail(String $to, $template, Array $data){
        Log::debug('Building email: {template} for: {to} with data: {data}', ['template' => $template, 'to' => $to ,'data' => $data]);

        $bladeContent = Email_Template::where('name', $template)->first();
        $bladeContent = $bladeContent->template;
        $bladeContent = Blade::render($bladeContent, $data);

        log::debug('{template}', ['template' => $bladeContent]);


    }
}
