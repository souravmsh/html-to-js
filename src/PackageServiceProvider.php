<?php 

namespace Souravmsh\HtmlToJs;

use Illuminate\Support\ServiceProvider;
use Souravmsh\HtmlToJs\Libs\ConvertHTMLtoJs;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('HtmlToJs', function () {
            return new ConvertHTMLtoJs;
        });
    }
}