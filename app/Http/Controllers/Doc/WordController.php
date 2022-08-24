<?php

namespace App\Http\Controllers\Doc;

use App\Http\Controllers\Controller;
use App\Traits\ImageUtils;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

class WordController extends Controller
{
    use ImageUtils;

    public function wordConvertHtml(Request $request)
    {
        $request->validate([
            'word' => [
                'bail', 'required', 'file',
                'mimetypes:application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/wps-office.docx',
            ],
        ]);

        $pathInfo = pathinfo($request->word->getClientOriginalName());

        $extentsion = $request->file('word')->extension();

        if ($extentsion == 'doc') {
            $readerName = 'MsDoc';
        } else {
            $readerName = 'Word2007';
        }

        $path = $request->file('word')->storeAs('word', time().'.'.$extentsion, 'public');

        $realPath = storage_path('app/public/'.$path);

        $word = IOFactory::load($realPath, $readerName);

        $write = IOFactory::createWriter($word, 'HTML');

        $htmlDom = new DOMDocument();

        libxml_use_internal_errors(true);

        $htmlDom->loadHTML($write->getContent());

        $images = $htmlDom->getElementsByTagName('img');

        //将base64图片保存成文件存到本地
        foreach ($images as $key => $image) {
            $image->setAttribute('src', $this->saveBase64ImageToBin($image->getAttribute('src')));
        }

        $body = $htmlDom->getElementsByTagName('body')->item(0);

        $content = $htmlDom->saveHTML($body);

        $content = str_replace(['<body>', '</body>'], '', $content);

        return $content;
    }

    /**
     * 只能转成纯文本，不能保留样式，会报错，未解决,demo也报错
     * 此处改成转为纯文本样
     * https://github.com/PHPOffice/PHPWord/blob/develop/samples/Sample_26_Html.php
     *
     * @param Request $request
     * @return string
     */
    public function htmlConvertWord(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $response = Http::get($request->input('url'));

        if ($response->failed()) {
            return '抓取失败';
        }

        $html = $response->body();
        //dd($html);
        libxml_use_internal_errors(true);
        $htmlDom = new DOMDocument();
        @$htmlDom->loadHTML($html);
        //获取body节点
        $body = $htmlDom->getElementsByTagName('body')->item(0);
        //dd($body);
        //获取所有的scripts
        $scripts = $body->getElementsByTagName('script');
        //dd($scripts->length);
        $i = $scripts->length - 1;

        while ($i > -1) {
            $script = $scripts->item($i);
            //移除script标签
            $body->removeChild($script);
            $i--;
        }

        $htmlContent = $htmlDom->saveHTML($body);
        dd($htmlContent);
        //$htmlContent = \str_replace(["\t","\n"],"",$htmlContent);
        //$htmlContent = strip_tags($htmlContent,'<h1><h2><h3><h4><h5><h6><div><p><ul><li><span>');
        dump($htmlContent);
        $htmlContent = \strip_tags($htmlContent);
        dd($htmlContent);
        $phpword = new PhpWord();
        $section = $phpword->addSection();
        // Html::addHtml($section, $htmlContent, false, false);
        $section->addText($htmlContent);
        $fileName = time().'.docx';
        $path = 'app/public/word/'.$fileName;
        $path = storage_path($path);
        $phpword->save($path, 'Word2007', false);

        return response()->download($path);
    }
}
