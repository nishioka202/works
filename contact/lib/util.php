<?php
// XSS対策のためのHTMLエスケープ
function es(array|string $data, string $charset='UTF-8'):mixed{
    // $dataが配列のとき
    if (is_array($data)){
        // 再起呼び出し
        return array_map(__METHOD__, $data);
    }else{
        // HTMLエスケープを行う（<>などをHTMLタグとして機能しないようにする）
        return htmlspecialchars(string:$data, flags:ENT_QUOTES, encoding:$charset);
    }
}

// // 配列の文字エンコードのチェックを行う
// function cken(array $data): bool{
//     $result = true; // 想定内であれば（特に問題なければ）trueを返す（＝何もせずそのまま）
//     foreach ($data as $key => $value){ //key:0 / value:Shift-JISの「こんにちは。」
//         if (is_array($value)){
//             // 含まれている値が配列のとき文字列に連結する
//             $value = implode("", $value);
//         }
//         if (!mb_check_encoding($value)){
//             // 文字コードが一致しないとき
//             $result = false;
//             // foreachでの走査をブレイクする
//             break; //1つでもfalseがあったら抜ける
//         }
//     }
//     return $result;
// }

// ※ PHP7.2.0 以降なら、cken 関数の処理は次のように1行でまとめられ文字列と多次元配列にも対応
function cken(mixed $data):bool{
    return mb_check_encoding($data);
}