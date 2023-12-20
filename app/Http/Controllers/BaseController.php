<?php

namespace App\Http\Controllers;

use App\Traits\GeneralFunction;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class BaseController extends Controller
{

    use GeneralFunction;

    protected $lang;

    public function __construct()
    {
        $this->changeLang(request()->header('Accept-Language') ?? "en");
        $this->lang = App::getLocale();
    }
}
