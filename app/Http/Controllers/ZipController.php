<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Zipper;

class ZipController extends Controller
{
    public function index()
    {
        $logs = File::files(storage_path('logs'));
        return view('zip', compact('logs'));
    }

    public function download(Request $request)
    {
        // 打包文件名
        $name = 'logs-'.time().'.zip';
        // 创建 zip 文件
        $zipper = Zipper::make($name)->folder('logs');

        foreach($request->logs as $log) {
            // 检查提交的文件是否存在
            $path = storage_path('logs/'.basename($log));
            if (!File::exists($path)) {
                continue;
            }

            // 将文件加入 zip 包
            $zipper->add($path);
        }
        // 关闭zip，一定要调用
        $zipper->close();

        // 返回下载响应，下载完成后删除文件
        return response()->download(public_path($name))->deleteFileAfterSend(true);
    }

    public function upload(Request $request)
    {
        if ($request->logs) {
            $zipper = Zipper::make($request->logs);

            // 可以使用 listFiles() 查看 zip 文件内容
            logger('zip file list:');
            logger($zipper->listFiles());
            $zipper->folder('logs')->extractMatchingRegex(storage_path('logs'), '/\.log$/');
        }

        return redirect()->route('zip.index');
    }
}
