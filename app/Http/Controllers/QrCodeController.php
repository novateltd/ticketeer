<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Writer;

class QrCodeController extends Controller
{
    public function __invoke()
    {

        $renderer = new ImageRenderer(
            new RendererStyle(800),
            new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);
        $writer->writeFile('https://rbrotary.org.uk/news/2024-piano-concert', 'qrcode.png');
        
    }
}
